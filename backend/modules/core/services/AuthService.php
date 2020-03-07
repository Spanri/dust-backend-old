<?php

namespace app\modules\core\services;

use app\modules\core\components\Session;
use app\modules\core\components\UserRoleManager;
use app\modules\core\forms\LoginForm;
use app\models\StatusCode;
use app\models\User;
use app\models\UserSession;
use Yii;
use \yii\base\Exception;

/**
 * Сервис для работы с пользователями
 */
class UserService
{
    /**
     * @var UserRoleManager
     */
    private $roleManger;

    /**
     * Получение экземпляра менеджера ролей
     * @return UserRoleManager
     */
    public function getRoleManger()
    {
        if ($this->roleManger === null) {
            $this->roleManger = new UserRoleManager();
        }

        return $this->roleManger;
    }

    /**
     * Проверка времени жизни токена
     * @param UserSession $session
     * @throws \Exception
     */
    public static function checkTokenLifeTime(UserSession $session): void
    {
        if ((int)$session->lifetime === UserSession::LIFETIME_ETERNAL) {
            $result = true;
        } else {
            $result = ($session->lifetime + $session->last_activity) > (new \DateTime())->getTimestamp();
        }

        if (!$result) {
            throw new BadDataException('token lifetime is expired', 1);
        }
    }

    /**
     * Удаляет временный "блокирующий" файл сессии
     * @param UserSession $session
     * @throws Exception
     */
    public static function deleteExpiredSessionFile(UserSession $session)
    {
        $filePath = Session::getFilePath($session->token);
        if (file_exists($filePath) && is_file($filePath)) {
            error_log("removed $filePath");
            @unlink($filePath);
        } else {
            error_log("remove error $filePath");
        }
    }

    /**
     * Создание нового пользователя
     * @param SignupForm $form
     * @return User
     * @throws ApiException
     * @throws UserException
     */
    public function signup(SignupForm $form): User
    {
        if (!$form->validate()) {
            throw new UserException('cannot create user', StatusCode::NOT_ACCEPTABLE, null, $form->errors);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->email = $form->email;

            $user->status = User::STATUS_INACTIVE;
            $user->generateEmailConfirmToken();

            try {
                $user->setPassword($form->password);
            } catch (Exception $e) {
                throw new UserException($e->getMessage(), $e->getCode(), $e, []);
            }

            if (!$user->save()) {
                throw new UserException('cannot create user');
            }

            $profile = new UserProfile();
            $profile->user_id = $user->id;

            if (!$profile->load($form->attributes)) {
                throw new UserException('invalid profile data', StatusCode::NOT_ACCEPTABLE, null, $form->errors);
            }

            if (!$profile->validate()) {
                throw new UserException('cannot validate profile', StatusCode::NOT_ACCEPTABLE, null, $form->errors);
            }

            if (!$profile->save()) {
                throw new UserException('cannot save profile', StatusCode::NOT_ACCEPTABLE, null, $form->errors);
            }

            if(YII_ENV !== 'test') {
                $avatarManager = new AvatarManager(['profile' => $profile]);
                $avatarManager->makeAvatar();
            }

            // назначение роли пользователю
            $this->getRoleManger()->assignAtSignup($user, $form);

            //TODO xandr: акаунт (счет) пользователя создается в поведении UserAccountBehavior

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ApiException('internal error', StatusCode::INTERNAL_SERVER_ERROR);
        };

        $this->sendMailAfterSignup($user, $form->password);

        return $user;
    }

    /**
     * Авторизация пользователя.
     * @param LoginForm $form
     * @return array
     * @throws DbException
     * @throws UserException
     */
    public function login(LoginForm $form): array
    {
        if (!$form->validate()) {
            throw new UserException('incorrect pair login/password', StatusCode::FORBIDDEN, null, $form->errors);
        }

        try {
            /** @var User $user */
            $user = $form->getUser();
        } catch (DbException $e) {
            throw new UserException('user not found', StatusCode::FORBIDDEN, $e, $form->errors);
        }

        Yii::$app->user->login($user);

        $session = new UserSession(['user_id' => $user->id]);
        $session->generateNewToken();
        $session->data = SessionHelper::encode([User::SESSION_KEY_CURRENT_USER_ID => $user->id]);
        $session->updateDeviceData();

        if (!$session->save()) {
            throw new UserException('cannot save session', StatusCode::INTERNAL_SERVER_ERROR, null, $session->errors);
        }

        return [$session, $user];
    }

    /**
     * Подтверждение Email
     * @param EmailConfirmTokenForm $form
     * @return bool
     * @throws UserException
     */
    public function confirm(EmailConfirmTokenForm $form)
    {
        if (!$form->validate()) {
            throw new UserException('token is incorrect', StatusCode::FORBIDDEN, null, $form->errors);
        }

        try {
            $user = User::findByEmailConfirmToken($form->token);
            $user->checkEmailConfirmToken($form->token);

            $user->status = User::STATUS_ACTIVE;
            $user->removeEmailConfirmToken();
            $user->save();

        } catch (DbException $e) {
            throw new UserException($e->getMessage(), StatusCode::INTERNAL_SERVER_ERROR, $e, ['error' => 'Невозможно подтвердить Email.']);
        } catch (LogicException $e) {
            throw new UserException($e->getMessage(), StatusCode::INTERNAL_SERVER_ERROR, $e, ['error' => 'Невозможно подтвердить Email.']);
        }

        $this->sendMailAfterConfirm($user);

        return true;
    }

    /**
     * Выход из системы
     * @return bool
     * @throws UserException
     */
    public function logout()
    {
        if (!$token = AppHelper::getToken()) {
            throw new UserException('not acceptable', StatusCode::NOT_ACCEPTABLE);
        }

        try {
            $session = UserSession::find()->byToken($token)->one();
            $session->delete();
        } catch (DbException | \Throwable $e) {
        }

        return true;
    }

    /**
     * Отправка письма после регистрации Email
     * @param User $user
     * @param string $password
     */
    protected function sendMailAfterSignup(User $user, string $password)
    {
        try {
            Yii::$app->mailer->compose(
                ['html' => 'signup-html', 'text' => 'signup-text'],
                ['user' => $user, 'password' => $password]
            )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['supportEmailSender']])
                ->setTo($user->email)
                ->setSubject('Регистрация в DOOH.OOHDESK.RU')
                ->send();

        } catch (\Exception $e) {
            $message = 'Failed to send message: ' . $e->getMessage();
            error_log('NOT sent!');
            error_log($message);
            Yii::error($message);
        }
    }

    /**
     * Отправка письма после подтверждения Email
     * @param User $user
     */
    protected function sendMailAfterConfirm(User $user)
    {
        try {
            Yii::$app->mailer->compose(
                ['html' => 'signupAfterConfirm-html', 'text' => 'signupAfterConfirm-text'],
                ['user' => $user]
            )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['supportEmailSender']])
                ->setTo($user->email)
                ->setSubject('Подтверждение Email на DOOH.OOHDESK.RU')
                ->send();

        } catch (\Exception $e) {
            $message = 'Failed to send message: ' . $e->getMessage();
            error_log('NOT sent!');
            error_log($message);
            Yii::error($message);
        }
    }
}
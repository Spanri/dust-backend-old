<?php

namespace app\modules\core\controllers;

use app\modules\core\forms\LoginForm;
use app\models\StatusCode;
use app\models\User;
use app\models\UserSession;
use yii\web\Controller;

/**
 * Default controller for the `core` module
 */
class AuthController extends Controller
{
    /**
     * @var UserService Сервис авторизации
     */
    protected $userService;

    /**
     * AuthController конструктор.
     * @param string $id
     * @param $module
     * @param UserService $userService
     * @param array $config
     */
    public function __construct(string $id, $module, UserService $userService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
    }

    /**
     * @return User|array
     * @throws \Exception
     */
    public function actionLogin()
    {
        $form = new LoginForm();

        if (!$form->load(Yii::$app->request->bodyParams)) {
            throw new \Exception('invalid data');
        }
        try {
            $data = $this->userService->login($form);
        } catch (\Exception $e) {
            throw new \Exception('invalid data');
        }

        Yii::$app->response->statusCode = StatusCode::ACCEPTED;

        /** @var UserSession $session */
        /** @var User $user */
        [$session, $user] = $data;
        $user = $user->toArray();
        $user['token'] = $session->token;

        return $user;
    }

    /**
     * @return array
     */
    public function actionLogout()
    {
        try {
            $this->userService->logout();
        } catch (UserException $e) {
            throw new AuthException($e->getMessage(), $e->getCode(), $e, $e->getData(), $e->getDebugData());
        }

        Yii::$app->response->statusCode = StatusCode::OK;
        return [];
    }
}

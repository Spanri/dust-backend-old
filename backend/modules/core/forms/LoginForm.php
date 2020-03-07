<?php

namespace app\modules\core\forms;

use app\modules\core\Model;
use app\models\User;

/**
 * Класс модели ввода данных для авторизации пользователя
 *
 * @property User|null $user
 */
class LoginForm extends Model
{
    /**
     * @var string Email пользователя
     */
    public $email;
    /**
     * @var string Пароль пользователя
     */
    public $password;

    /**
     * @var User|bool
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Валидация пароля
     * Этот метод служит встроенной проверкой пароля.
     * @param $attribute аттрибут, которые проверяется на данный момент
     * @param $params дополнительные параметры
     * @throws \Exception
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            try {
                $user = $this->getUser();
            } catch (\Exception $e) {
                $user = null;
            }

            if (!$user || !$user->validatePassword($this->password)) {
                throw new \Exception('Email или пароль указаны неверно');
            }
        }
    }

    /**
     * Поиск по [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}

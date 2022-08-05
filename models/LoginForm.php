<?php

namespace app\models;

use yii\base\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    private User|null $_user = null;

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'email' => 'EMAIL',
            'password' => 'ПАРОЛЬ',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @param $attribute
     * @return void
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if (is_null($this->_user)) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}

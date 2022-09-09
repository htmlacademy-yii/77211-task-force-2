<?php

namespace app\models;

use Yii;

class SecurityForm extends \yii\base\Model
{
    public string $oldPassword = '';
    public string $password = '';
    public string $password_repeat = '';
    public int $showOnlyCustomer = 0;

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
            'oldPassword' => 'Старый пароль',
            'password' => 'Новый пароль',
            'password_repeat' => 'Повтор нового пароля',
            'showOnlyCustomer' => 'Отключить показ контактных данных (кроме заказчика)',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['oldPassword'], 'validatePassword'],
            [['oldPassword'], 'required', 'when' => function($model) {
                return !empty($model->password);
            }],
            [['password'], 'required', 'when' => function($model) {
                return !empty($model->oldPassword);
            }],
            [['password_repeat'], 'required', 'when' => function($model) {
                return !empty($model->password);
            }],
            [['showOnlyCustomer'], 'boolean'],
            [['oldPassword','password', 'password_repeat'], 'string', 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function validatePassword($attribute): void
    {
        $user = Yii::$app->user->identity;

        if (!$user->validatePassword($this->oldPassword)) {
            $this->addError($attribute, 'Неправильный старый пароль');
        }
    }
}
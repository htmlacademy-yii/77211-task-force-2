<?php

namespace app\models;

use yii\base\Model;

class RegistrationForm extends Model
{
    public string $name = '';
    public string $email = '';
    public int $city_id = 0;
    public string $password = '';
    public string $password_repeat = '';
    public int $role = 1;

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
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'role' => 'я собираюсь откликаться на заказы',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'city_id', 'password', 'password_repeat'], 'required'],
            [['city_id', 'role'], 'integer'],
            [['name', 'email', 'password', 'password_repeat'], 'string', 'max' => 255],
            [['email'], 'email'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [
                'email',
                'unique',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['email' => 'email']
            ],
            [
                'city_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
        ];
    }
}
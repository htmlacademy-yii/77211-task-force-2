<?php

namespace app\models;

use yii\base\Model;

class ProfileForm extends Model
{
    public ?string $avatar = null;
    public string $name;
    public string $email;
    public ?string $birthdate = null;
    public ?string $phone = null;
    public ?string $telegram = null;
    public ?string $info = null;
    public ?array $categories = null;

//    public string $avatar = '';
//    public string $name;
//    public string $email;
//    public string $birthdate = '';
//    public string $phone = '';
//    public string $telegram = '';
//    public string $info = '';
//    public ?array $categories = null;

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
            'avatar' => 'Аватар',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'birthdate' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'info' => 'Информация о себе',
            'categories' => 'Выбор специализаций',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['avatar'], 'image', 'extensions' => ['gif', 'jpg', 'jpeg', 'png']],
            [['avatar', 'birthdate', 'phone', 'telegram', 'info', 'categories'], 'default', 'value' => null],
            [['name', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['phone'], 'string', 'length' => [11, 11]],
            [['telegram'], 'string', 'max' => 64],
            [['info'], 'string'],
            [['birthdate'], 'date', 'format' => 'php:Y-m-d'],
            [
                'categories',
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'allowArray' => true,
            ],
        ];
    }
}
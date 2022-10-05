<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CreateTaskForm extends Model
{
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public ?string $budget = '';
    public string $location = '';
    public string $city = '';
    public string $address = '';
    public string $lat = '';
    public string $long = '';
    public ?string $deadline_at = null;
    public array $files = [];

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
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'budget' => 'Бюджет',
            'location' => 'Локация',
            'city' => 'City',
            'address' => 'Address',
            'lat' => 'Lat',
            'long' => 'Long',
            'deadline_at' => 'Срок исполнения',
            'files' => 'Файлы'
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['category_id', 'budget'], 'integer'],
            [['title', 'description', 'location', 'city', 'address', 'lat', 'long', 'deadline_at'], 'string'],
            [['title'], 'string', 'length' => [10, 255]],
            [['description'], 'string', 'min' => 30],
            [['budget'], 'default', 'value' => null],
            [['budget'], 'integer', 'min' => 1, 'max' => 100000],
            [['deadline_at'], 'default', 'value' => null],
            [
                ['location'],
                'showLocationErrorMessage',
                'when' => fn($model) => $model->city !== Yii::$app->user->identity->city->name
            ],
            [
                ['deadline_at'],
                'date',
                'format' => 'php:Y-m-d',
                'min' => date('Y-m-d'),
                'tooSmall' => 'Срок исполнение не может быть меньше текущей даты.',
            ],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [['files'], 'file', 'maxFiles' => 5],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function showLocationErrorMessage($attribute, $params): void
    {
        $this->addError($attribute, 'Вы можете указывать только адрес в рамках города, указанного при регистрации!');
    }
}
<?php

namespace app\models;

use yii\base\Model;

class CreateTaskForm extends Model
{
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public ?string $budget = '';
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
            [['title', 'description', 'deadline_at'], 'string'],
            [['title'], 'string', 'length' => [10, 255]],
            [['description'], 'string', 'min' => 30],
            [['budget'], 'default', 'value' => null],
            [['budget'], 'integer', 'min' => 1],
            [['deadline_at'], 'default', 'value' => null],
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
}
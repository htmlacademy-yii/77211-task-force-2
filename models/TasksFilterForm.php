<?php

namespace app\models;

use yii\base\Model;

class TasksFilterForm extends Model
{
    public array $categories = [];
    public ?string $remote = null;
    public ?string $withoutResponse = null;
    public string $period = '0';

    public function formName(): string
    {
        return 'filter';
    }

    public function attributeLabels(): array
    {
        return [
            'withoutResponse' => 'Без откликов',
            'remote' => 'Удалённая работа',
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['categories'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'allowArray' => true
            ],
            [['remote'], 'boolean'],
            [['withoutResponse'], 'boolean'],
            [['period'], 'integer', 'min' => 0, 'max' => 24],
        ];
    }
}
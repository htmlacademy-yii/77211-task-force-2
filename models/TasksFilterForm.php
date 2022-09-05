<?php

namespace app\models;

use yii\base\Model;

class TasksFilterForm extends Model
{
    public ?array $categories = null;
    public ?string $withoutResponse = null;
    public ?string $remote = null;
    public string $period = '0';

    public function formName()
    {
        return '';
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
            [['categories', 'remote', 'withoutResponse', 'period'], 'safe']
        ];
    }
}
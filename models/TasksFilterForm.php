<?php

namespace app\models;

use yii\base\Model;

class TasksFilterForm extends Model
{
    public $categories;
    public $withoutResponse;
    public $remote;
    public $period;

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
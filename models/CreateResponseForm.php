<?php

namespace app\models;

class CreateResponseForm extends \yii\base\Model
{
    public ?int $task_id = null;
    public string $comment = '';
    public string $budget = '';

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => 'Task ID',
            'comment' => 'Ваш комментарий',
            'budget' => 'Стоимость',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['task_id', 'comment', 'budget'], 'required'],
            [['task_id', 'budget'], 'integer'],
            [['comment'], 'string'],
            [['budget'], 'integer', 'min' => 1],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ]
        ];
    }
}
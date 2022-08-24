<?php

namespace app\models;

class CreateReviewForm extends \yii\base\Model
{
    public ?int $task_id = null;
    public ?int $author_id = null;
    public ?int $user_id = null;
    public ?int $rate = null;
    public string $comment = '';

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => 'Task ID',
            'author_id' => 'Author ID',
            'user_id' => 'User ID',
            'rate' => 'Оценка работы',
            'comment' => 'Ваш комментарий',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['task_id', 'author_id', 'user_id', 'rate', 'comment'], 'required'],
            [['task_id', 'author_id', 'user_id', 'rate'], 'integer'],
            [['rate'], 'integer', 'min' => 1, 'max' => 5],
            [['comment'], 'string'],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }
}
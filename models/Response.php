<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int $task_id
 * @property int $executor_id
 * @property string|null $comment
 * @property int|null $budget
 * @property int $is_refused
 * @property string $created_at
 *
 * @property User $executor
 * @property Task $task
 */
class Response extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'executor_id'], 'required'],
            [['task_id', 'executor_id', 'budget', 'is_refused'], 'integer'],
            [['budget'], 'integer', 'min' => 1, 'max' => 100000],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
            ],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'comment' => 'Comment',
            'budget' => 'Budget',
            'is_refused' => 'Is Refused',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id'])->inverseOf('responses');
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id'])->inverseOf('responses');
    }
}

<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $task_id
 * @property int $author_id
 * @property int $user_id
 * @property int $rate
 * @property string|null $comment
 * @property string $created_at
 *
 * @property Task $task
 */
class Review extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'author_id', 'user_id', 'rate'], 'required'],
            [['task_id', 'author_id', 'user_id', 'rate'], 'integer'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'author_id' => 'Author ID',
            'user_id' => 'User ID',
            'rate' => 'Rate',
            'comment' => 'Comment',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id'])->inverseOf('reviews');
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id'])->inverseOf('reviewsWhereUserIsAuthor');
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('reviewsWhereUserIsReceiver');
    }

}

<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $executor_id
 * @property int $status
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property int|null $budget
 * @property int|null $city_id
 * @property string $address
 * @property string|null $coordinates
 * @property string $created_at
 * @property string|null $deadline_at
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property File[] $files
 */

class Task extends ActiveRecord
{
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_PROCESSING = 3;
    public const STATUS_DONE = 4;
    public const STATUS_FAILED = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'title', 'category_id'], 'required'],
            [['customer_id', 'executor_id', 'status', 'category_id', 'budget', 'city_id'], 'integer'],
            [['description', 'address', 'coordinates'], 'string'],
            [['created_at', 'deadline_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
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
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'status' => 'Status',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'budget' => 'Budget',
            'city_id' => 'City ID',
            'address' => 'Location',
            'coordinates' => 'Coordinates',
            'created_at' => 'Created At',
            'deadline_at' => 'Deadline At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id'])->inverseOf('tasks');
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id'])->inverseOf('tasks');
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id'])->inverseOf('tasksWhereUserIsCustomer');
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id'])->inverseOf('tasksWhereUserIsExecutor');
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(File::class, ['id' => 'file_id'])
            ->viaTable('task_file', ['task_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getTaskStatusesList(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_PROCESSING => 'На исполнении',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }
}

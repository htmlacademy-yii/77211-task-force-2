<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string|null $birthdate
 * @property string|null $info
 * @property int|null $avatar_file_id
 * @property float $rating
 * @property int $city_id
 * @property string|null $phone
 * @property string|null $telegram
 * @property int $role
 * @property int $status
 * @property string $created_at
 * @property int $failed_tasks_count
 * @property int $show_only_customer
 *
 * @property File $avatarFile
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviewsWhereUserIsAuthor
 * @property Review[] $reviewsWhereUserIsReceiver
 * @property Task[] $tasksWhereUserIsCustomer
 * @property Task[] $tasksWhereUserIsExecutor
 * @property Category[] $categories
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_READY = 0;
    public const STATUS_BUSY = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password', 'name', 'city_id'], 'required'],
            [['birthdate', 'created_at'], 'safe'],
            [['info'], 'string'],
            [['avatar_file_id', 'city_id', 'role', 'status', 'failed_tasks_count', 'show_only_customer'], 'integer'],
            [['rating'], 'number'],
            [['email', 'password', 'name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [
                ['avatar_file_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => File::class,
                'targetAttribute' => ['avatar_file_id' => 'id']
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
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
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'birthdate' => 'Birthdate',
            'info' => 'Info',
            'avatar_file_id' => 'Avatar File ID',
            'rating' => 'Rating',
            'city_id' => 'City ID',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'failed_tasks_count' => 'Failed Tasks Count',
            'show_only_customer' => 'Show Only Customer',
        ];
    }

    /**
     * Gets query for [[AvatarFile]].
     *
     * @return ActiveQuery
     */
    public function getAvatarFile(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'avatar_file_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id'])->inverseOf('users');
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id'])->inverseOf('executor');
    }

    /**
     * Gets query for [[ReviewsWhereUserIsAuthor]].
     *
     * @return ActiveQuery
     */
    public function getReviewsWhereUserIsAuthor(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['author_id' => 'id'])->inverseOf('author');
    }

    /**
     * Gets query for [[ReviewsWhereUserIsReceiver]].
     *
     * @return ActiveQuery
     */
    public function getReviewsWhereUserIsReceiver(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[TasksWhereUserIsCustomer]].
     *
     * @return ActiveQuery
     */
    public function getTasksWhereUserIsCustomer(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id'])->inverseOf('customer');
    }

    /**
     * Gets query for [[TasksWhereUserIsExecutor]].
     *
     * @return ActiveQuery
     */
    public function getTasksWhereUserIsExecutor(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id'])->inverseOf('executor');
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['user_id' => 'id']);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @inheritDoc
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $password;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return date_diff(date_create($this->birthdate), date_create('now'))->y;
    }

    /**
     * @return array
     */
    public function getUserStatusesList(): array
    {
        return [
            self::STATUS_READY => 'Открыт для новых заказов',
            self::STATUS_BUSY => 'Занят',
        ];
    }

    /**
     * @return int
     */
    public function getCompletedTasksCount(): int
    {
        return Task::find()
            ->where(['executor_id' => $this->id])
            ->andWhere(['status' => Task::STATUS_DONE])
            ->count();
    }

    /**
     * @return int
     */
    public function getPlaceInRating(): int
    {
        return self::find()
            ->where(['>', 'rating', $this->rating])
            ->count() + 1;
    }
}

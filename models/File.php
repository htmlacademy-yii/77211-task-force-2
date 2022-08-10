<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $path
 *
 * @property Task[] $tasks
 */
class File extends ActiveRecord
{
    public const BYTES_IN_KILOBYTES = 1024;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['id' => 'task_id'])
            ->viaTable('task_file', ['file_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        $fullFilePath = Yii::getAlias('@webroot') . $this->path;
        $size = round(filesize($fullFilePath) / self::BYTES_IN_KILOBYTES, 2);

        return "$size Кб";
    }
}

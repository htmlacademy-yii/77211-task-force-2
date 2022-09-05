<?php

namespace app\services;

use app\models\File;
use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;

class FileService
{
    /**
     * @param UploadedFile $uploadedFile
     * @param string $type 'task' or 'avatar'
     * @param int|null $id Task or User id
     * @return File
     * @throws Exception
     */
    public function upload(UploadedFile $uploadedFile, string $type, int $id = null): File
    {
        if ($type === 'task') {
            $dir = "/uploads/tasks/$id/";
        }

        if ($type === 'avatar') {
            $dir = "/uploads/avatars/$id/";
        }

        $dirToCreate = Yii::getAlias('@webroot') . $dir;

        if (!is_dir($dirToCreate)) {
            mkdir($dirToCreate);
        }

        $fileName = "$uploadedFile->baseName.$uploadedFile->extension";
        $path = "{$dir}{$fileName}";

        $uploadedFile->saveAs(Yii::getAlias('@webroot') . $path);

        $file = new File();
        $file->path = $path;

        if (!$file->save()) {
            throw new Exception('Что-то пошло не так');
        }

        return $file;
    }
}
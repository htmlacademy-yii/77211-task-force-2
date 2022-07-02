<?php

namespace app\fixtures;

use yii\test\ActiveFixture;

class TaskFileFixture extends ActiveFixture
{
    public $tableName = 'task_file';
    public $depends = [TaskFixture::class, FileFixture::class];
}
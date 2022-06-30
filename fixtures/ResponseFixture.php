<?php

namespace app\fixtures;

use app\models\Response;
use yii\test\ActiveFixture;

class ResponseFixture extends ActiveFixture
{
    public $modelClass = Response::class;
    public $depends = [UserFixture::class, TaskFixture::class];
}
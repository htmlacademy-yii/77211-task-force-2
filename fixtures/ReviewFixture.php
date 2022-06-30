<?php

namespace app\fixtures;

use app\models\Review;
use yii\test\ActiveFixture;

class ReviewFixture extends ActiveFixture
{
    public $modelClass = Review::class;
    public $depends = [TaskFixture::class];
}
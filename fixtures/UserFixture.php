<?php

namespace app\fixtures;

use app\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
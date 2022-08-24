<?php

namespace app\services;

use app\models\User;
use yii\helpers\ArrayHelper;

class UserService
{
    public function countUserRating(User $user): float
    {
        $reviews = $user->reviewsWhereUserIsReceiver;

        $reviewsRateSum = array_sum(ArrayHelper::getColumn($reviews, 'rate'));
        $reviewsCount = count($reviews);
        $userFailedTasksCount = $user->failed_tasks_count;

        return round($reviewsRateSum / ($reviewsCount + $userFailedTasksCount), 2);
    }
}
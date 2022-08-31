<?php

namespace app\services;

use app\models\User;
use yii\helpers\ArrayHelper;

class UserService
{
    /**
     * @param User $user
     * @return float
     */
    public function countUserRating(User $user): float
    {
        $reviews = $user->reviewsWhereUserIsReceiver;

        $reviewsRateSum = array_sum(ArrayHelper::getColumn($reviews, 'rate'));
        $reviewsCount = count($reviews);
        $userFailedTasksCount = $user->failed_tasks_count;

        return round($reviewsRateSum / ($reviewsCount + $userFailedTasksCount), 2);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::find()->where(['email' => $email])->one();
    }
}
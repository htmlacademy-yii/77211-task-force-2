<?php

namespace app\services;

use app\models\RegistrationForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class UserService
{
    /**
     * @param RegistrationForm $form
     * @return User
     * @throws Exception
     */
    public function createUser(RegistrationForm $form): User
    {
        $user = new User();
        $user->loadDefaultValues();
        $user->name = $form->name;
        $user->email = $form->email;
        $user->city_id = $form->city_id;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($form->password);
        $user->role = $form->role;

        if ($user->save()) {
            $auth = Yii::$app->authManager;
            $customerRole = $auth->getRole('customer');
            $executorRole = $auth->getRole('executor');

            if ($user->role === User::ROLE_CUSTOMER) {
                $auth->assign($customerRole, $user->id);
            }

            if ($user->role === User::ROLE_EXECUTOR) {
                $auth->assign($executorRole, $user->id);
            }
        } else {
            throw new Exception('Что-то пошло не так');
        }

        return $user;
    }

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
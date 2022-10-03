<?php

namespace app\services;

use app\models\Category;
use app\models\File;
use app\models\ProfileForm;
use app\models\RegistrationForm;
use app\models\SecurityForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
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

    /**
     * @param ProfileForm $form
     * @param User $user
     * @param File|null $file
     * @return void
     * @throws \yii\db\Exception
     * @throws StaleObjectException
     */
    public function updateUserProfile(ProfileForm $form, User $user, ?File $file = null): void
    {
        $user->name = $form->name;
        $user->email = $form->email;
        $user->birthdate = $form->birthdate;
        $user->phone = $form->phone;
        $user->telegram = $form->telegram;
        $user->info = $form->info;
        $user->update();

        if (!is_null($file)) {
            $oldAvatar = $user->avatarFile;

            if (!is_null($oldAvatar)) {
                $user->unlink('avatarFile', $oldAvatar);
            }

            $user->link('avatarFile', $file);
        }
    }

    /**
     * @param array|null $newCategoriesIds
     * @param User $user
     * @return void
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    public function updateUserCategories(?array $newCategoriesIds, User $user): void
    {
        $oldCategories = $user->categories;

        if (!empty($oldCategories)) {
            foreach ($oldCategories as $oldCategory) {
                $user->unlink('categories', $oldCategory, true);
            }
        }

        if (!is_null($newCategoriesIds)) {
            foreach ($newCategoriesIds as $categoryId) {
                $newCategory = Category::findOne($categoryId);
                $user->link('categories', $newCategory);
            }
        }
    }

    /**
     * @param SecurityForm $securityForm
     * @param User $user
     * @return void
     * @throws Exception
     * @throws StaleObjectException
     */
    public function updateUserSecurity(SecurityForm $securityForm, User $user): void
    {
        $newPassword = $securityForm->password;

        if ($newPassword !== '') {
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($securityForm->password);
        }
        $user->show_only_customer = $securityForm->showOnlyCustomer;
        $user->update();
    }

    /**
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public static function showExecutorContacts(User $currentUser, User $user): bool
    {
        if ($user->show_only_customer === 0) {
            return true;
        }

        if ($currentUser->id === $user->id && $user->show_only_customer === 1) {
            return true;
        }

        if ($currentUser->role === User::ROLE_CUSTOMER && $user->show_only_customer === 1) {
            $currentUserTasks = $currentUser->getTasksWhereUserIsCustomer();
            return $currentUserTasks->where(['executor_id' => $user->id])->exists();
        }

        return false;
    }
}
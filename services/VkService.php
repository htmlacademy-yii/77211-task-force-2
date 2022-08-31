<?php

namespace app\services;

use app\models\Auth;
use app\models\User;
use Yii;
use yii\base\Exception;

class VkService
{
    public string $source;
    public string $sourceId;

    public function __construct(string $source, int $sourceId)
    {
        $this->source = $source;
        $this->sourceId = (string) $sourceId;
    }

    /**
     * @return Auth|null
     */
    public function getVkAuthRecord(): ?Auth
    {
        return Auth::find()->where(['source' => $this->source, 'source_id' => $this->sourceId,])->one();
    }

    /**
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function createVkAuthRecord(int $userId): void
    {
        $vkAuthRecord = new Auth();
        $vkAuthRecord->user_id = $userId;
        $vkAuthRecord->source = $this->source;
        $vkAuthRecord->source_id = $this->sourceId;
        if (!$vkAuthRecord->save()) {
            throw new Exception('Что-то пошло не так!');
        }
    }

    /**
     * @param array $userData
     * @return User
     * @throws Exception
     */
    public function createUserFromVkData(array $userData): User
    {
        $locationService = new LocationService();

        $user = new User();
        $user->loadDefaultValues();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->password = $userData['password'];
        $user->city_id = $locationService->getCityIdByName($userData['city']);

        if (!$user->save()) {
            throw new Exception('Что-то пошло не так!');
        }

        $authManager = Yii::$app->authManager;
        $executorRole = $authManager->getRole('executor');
        $authManager->assign($executorRole, $user->id);

        $this->createVkAuthRecord($user->id);

        return $user;
    }
}
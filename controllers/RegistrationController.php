<?php

namespace app\controllers;

use app\models\City;
use app\models\RegistrationForm;
use app\services\UserService;
use Yii;
use yii\base\Exception;
use yii\web\Response;

class RegistrationController extends SecuredController
{
    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionIndex(): string|Response
    {
        $this->view->title = 'Регистрация пользователя :: Taskforce';

        $citiesList = City::getCitiesList();

        $regForm = new RegistrationForm();

        if ($regForm->load(Yii::$app->request->post()) && $regForm->validate()) {
            $userService = new UserService();
            $user = $userService->createUser($regForm);
            Yii::$app->user->login($user);

            return $this->redirect(['tasks/index']);
        }

        return $this->render('index', [
            'regForm' => $regForm,
            'citiesList' => $citiesList,
        ]);
    }
}
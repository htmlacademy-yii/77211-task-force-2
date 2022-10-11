<?php

namespace app\controllers;

use app\models\City;
use app\models\RegistrationForm;
use app\services\UserService;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RegistrationController extends SecuredController
{
    public function actionIndex()
    {
        $this->view->title = 'Регистрация пользователя :: Taskforce';

        $citiesList = City::getCitiesList();
        $regForm = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $regForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($regForm);
            }

            if ($regForm->validate()) {
                $userService = new UserService();
                $user = $userService->createUser($regForm);
                Yii::$app->user->login($user);

                return $this->redirect(['tasks/index']);
            }
        }

        return $this->render('index', [
            'regForm' => $regForm,
            'citiesList' => $citiesList,
        ]);
    }
}
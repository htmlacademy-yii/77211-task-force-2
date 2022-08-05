<?php

namespace app\controllers;

use app\models\City;
use app\models\RegistrationForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class RegistrationController extends Controller
{
    public function actionIndex(): string|Response
    {
        $this->view->title = 'Регистрация пользователя :: Taskforce';

        $citiesList = City::getCitiesList();

        $regForm = new RegistrationForm();

        if ($regForm->load(Yii::$app->request->post()) && $regForm->validate()) {
            $user = new User();
            $user->loadDefaultValues();
            $user->name = $regForm->name;
            $user->email = $regForm->email;
            $user->city_id = $regForm->city_id;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($regForm->password);
            $user->role = $regForm->role;
            $user->save();

            return $this->redirect(['tasks/index']);
        }

        return $this->render('index', [
            'regForm' => $regForm,
            'citiesList' => $citiesList,
        ]);
    }
}
<?php

namespace app\controllers;

use app\models\City;
use app\models\RegistrationForm;
use app\models\User;
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
            $user = new User();
            $user->loadDefaultValues();
            $user->name = $regForm->name;
            $user->email = $regForm->email;
            $user->city_id = $regForm->city_id;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($regForm->password);
            $user->role = $regForm->role;

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

                $identity = User::findOne($user->id);
                Yii::$app->user->login($identity);
            } else {
                throw new Exception('Что-то пошло не так');
            }

            return $this->redirect(['tasks/index']);
        }

        return $this->render('index', [
            'regForm' => $regForm,
            'citiesList' => $citiesList,
        ]);
    }
}
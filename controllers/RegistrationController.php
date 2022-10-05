<?php

namespace app\controllers;

use app\models\City;
use app\models\RegistrationForm;
use app\services\UserService;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class RegistrationController extends SecuredController
{

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Регистрация пользователя :: Taskforce';

        $citiesList = City::getCitiesList();
        $regForm = new RegistrationForm();

        return $this->render('index', [
            'regForm' => $regForm,
            'citiesList' => $citiesList,
        ]);
    }

    /**
     * @return Response
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    public function actionStore(): Response
    {
        $userService = new UserService();

        if (!Yii::$app->request->getIsPost()) {
            throw new BadRequestHttpException();
        }

        $regForm = new RegistrationForm();

        if ($regForm->load(Yii::$app->request->post()) && $regForm->validate()) {
            $user = $userService->createUser($regForm);
            Yii::$app->user->login($user);

            return $this->redirect(['tasks/index']);
        }

        throw new ServerErrorHttpException('Невозможно создать пользователя');
    }
}
<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LandingController extends SecuredController
{
    public $layout = 'landing';

    /**
     * @return string|array|Response
     */
    public function actionIndex()
    {
        $this->view->title = 'Главная страница :: Taskforce';

        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->redirect(['tasks/index']);
            }
        }

        return $this->render('index', compact('loginForm'));
    }
}
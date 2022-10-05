<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;

class LandingController extends SecuredController
{
    public $layout = 'landing';

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Главная страница :: Taskforce';

        $loginForm = new LoginForm();

        return $this->render('index', compact('loginForm'));
    }

    /**
     * @return Response
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function actionLogin(): Response
    {
        $loginForm = new LoginForm();

        if (!Yii::$app->request->getIsPost()) {
            throw new BadRequestHttpException();
        }

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->validate()) {
            $user = $loginForm->getUser();
            Yii::$app->user->login($user);

            return $this->redirect(['tasks/index']);
        }

        throw new ServerErrorHttpException('Невозможно произвести аутентификацию пользователя');
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionLoginFormAjaxValidate(): array
    {
        if (!Yii::$app->request->getIsPost() || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $loginForm = new LoginForm();
        $loginForm->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ActiveForm::validate($loginForm);
    }
}
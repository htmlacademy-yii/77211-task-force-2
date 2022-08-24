<?php

namespace app\controllers;

use app\models\Review;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $this->view->title = "$user->name :: Taskforce";

        $reviews = Review::find()
            ->where(['user_id' => $id])
            ->with('author.avatarFile', 'task')
            ->all();

        return $this->render('view', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
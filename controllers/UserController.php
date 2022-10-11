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
    /**
     * @return array[]
     */
    public function behaviors()
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
    public function actionView($id)
    {
        $user = User::findOne($id);
        $currentUser = Yii::$app->user->identity;

        if (!$user || $user->role === User::ROLE_CUSTOMER) {
            throw new NotFoundHttpException();
        }

        $this->view->title = "$user->name :: Taskforce";

        $reviews = Review::find()
            ->where(['user_id' => $id])
            ->with('author.avatarFile', 'task')
            ->all();

        return $this->render('view', [
            'user' => $user,
            'currentUser' => $currentUser,
            'reviews' => $reviews,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
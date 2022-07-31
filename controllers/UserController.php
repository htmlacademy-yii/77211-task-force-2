<?php

namespace app\controllers;

use app\models\Review;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
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
}
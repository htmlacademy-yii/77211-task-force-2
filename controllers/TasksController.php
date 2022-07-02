<?php

namespace app\controllers;

use app\models\Task;
use Taskforce\Logic\Task as TaskLogic;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $tasks = Task::find()
            ->where(['status' => TaskLogic::STATUS_NEW])
            ->with('city', 'category')
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
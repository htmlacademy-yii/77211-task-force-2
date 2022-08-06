<?php

namespace app\controllers;

use app\models\Category;
use app\models\Response;
use app\models\Task;
use app\models\TasksFilterForm;
use app\services\TasksFilterService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Новые задания :: Taskforce';

        $categoriesList = Category::getCategoriesList();

        $filterForm = new TasksFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $tasksDataProvider = new ActiveDataProvider([
            'query' => (new TasksFilterService())->filter($filterForm),
            'pagination' => [
                'pageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'tasksDataProvider' => $tasksDataProvider,
            'filterForm' => $filterForm,
            'categoriesList' => $categoriesList,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $this->view->title = "$task->title :: Taskforce";

        $taskStatusName = Task::getTaskStatusesList()[$task->status];

        $responses = Response::find()
            ->where(['task_id' => $id])
            ->with('executor.avatarFile', 'executor.reviewsWhereUserIsReceiver')
            ->all();

        return $this->render('view', [
            'task' => $task,
            'taskStatusName' => $taskStatusName,
            'responses' => $responses,
        ]);
    }
}
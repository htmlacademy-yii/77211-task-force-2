<?php

namespace app\controllers;

use app\models\Category;
use app\models\Response;
use app\models\Task;
use app\models\TasksFilterForm;
use Taskforce\Logic\Task as TaskLogic;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $this->view->title = 'Новые задания :: Taskforce';

        $categories = Category::find()->asArray()->all();
        $categoriesList = ArrayHelper::map($categories, 'id', 'name');

        $filterForm = new TasksFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $selectedCategories = $filterForm->categories;
        $isWithoutResponses = $filterForm->withoutResponse;
        $isRemote = $filterForm->remote;
        $period = (int) $filterForm->period;

        $query = Task::find()
            ->where(['status' => TaskLogic::STATUS_NEW])
            ->with('city', 'category');

        if (!is_null($selectedCategories)) {
            $query->andWhere(['category_id' => $selectedCategories]);
        }

        if (!is_null($isWithoutResponses)) {
            $taskIdsWithResponses = ArrayHelper::getColumn(Response::find()->asArray()->all(), 'task_id');
            $query->andWhere(['not in', 'id', $taskIdsWithResponses]);
        }

        if (!is_null($isRemote)) {
            $query->andWhere(['city_id' => null]);
        }

        if ($period !== 0) {
            $query->andWhere("created_at > NOW() - INTERVAL :period HOUR", [
                ':period' => $period
            ]);
        }

        $tasksDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'route' => 'tasks'
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
}
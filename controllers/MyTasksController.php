<?php

namespace app\controllers;

use app\models\User;
use app\services\TaskService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MyTasksController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Мои задания :: Taskforce';

        $taskService = new TaskService();
        $user = Yii::$app->user->identity;

        $titlesList = [
            'new' => 'Новые задания',
            'processing' => 'Задания в процессе',
            'overdue' => 'Просроченные задания',
            'closed' => 'Закрытые задания',
        ];

        $statusParam = Yii::$app->request->get('status');

        if ($user->role === User::ROLE_CUSTOMER) {
            $statusParam = $statusParam ?? 'new';
        }

        if ($user->role === User::ROLE_EXECUTOR) {
            $statusParam = $statusParam ?? 'processing';
        }

        if (!array_key_exists($statusParam, $titlesList)) {
            throw new NotFoundHttpException();
        }

        $tasksDataProvider = new ActiveDataProvider([
            'query' => $taskService->getMyTasks($user, $statusParam),
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
            'title' => $titlesList[$statusParam],
        ]);
    }
}
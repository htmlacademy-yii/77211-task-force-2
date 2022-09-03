<?php

namespace app\controllers;

use app\models\Category;
use app\models\CreateResponseForm;
use app\models\CreateReviewForm;
use app\models\CreateTaskForm;
use app\services\LocationService;
use app\services\ResponseService;
use app\services\TaskService;
use app\services\UploadFileService;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response as WebResponse;
use app\models\Task;
use app\models\TasksFilterForm;
use app\services\TasksFilterService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class TasksController extends Controller
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
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['customer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['executorCanRefuseTask'],
                        'roleParams' => fn($rule) => [
                            'task' => Task::findOne(Yii::$app->request->get('id'))
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['cancel'],
                        'roles' => ['customerCanCancelTask'],
                        'roleParams' => fn($rule) => [
                            'task' => Task::findOne(Yii::$app->request->get('id'))
                        ]
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
        $this->view->title = 'Новые задания :: Taskforce';

        $categoriesList = Category::getCategoriesList();
        $tasksFilterService = new TasksFilterService();

        $filterForm = new TasksFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $tasksDataProvider = new ActiveDataProvider([
            'query' => $tasksFilterService->filter($filterForm),
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
     * @throws Exception
     */
    public function actionView(int $id): string
    {

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $this->view->title = "$task->title :: Taskforce";

        $taskService = new TaskService();
        $responseService = new ResponseService();
        $locationService = new LocationService();
        $taskStatusName = Task::getTaskStatusesList()[$task->status];
        $locationData = [];

        if (isset($task->city_id)) {
            $locationData = [
                'cityName' => $task->city->name,
                'address' => $task->address,
                'coordinates' => $locationService->getTaskCoordinates($task->id),
            ];
        }

        $responses = $responseService->getResponses($task, Yii::$app->user->identity);
        $actionsMarkup = $taskService->getAvailableActionsMarkup(Yii::$app->user->identity, $task);
        $files = $task->files;

        $responseForm = new CreateResponseForm();
        $reviewForm = new CreateReviewForm();

        return $this->render('view', [
            'task' => $task,
            'taskStatusName' => $taskStatusName,
            'locationData' => $locationData,
            'responses' => $responses,
            'actionsMarkup' => $actionsMarkup,
            'files' => $files,
            'responseForm' => $responseForm,
            'reviewForm' => $reviewForm,
        ]);
    }

    /**
     * @return array|string|WebResponse
     * @throws Exception
     */
    public function actionCreate(): WebResponse|array|string
    {
        $this->view->title = 'Cоздать задание :: Taskforce';

        $user = Yii::$app->user->identity;
        $locationService = new LocationService();
        $taskService = new TaskService();
        $uploadFileService = new UploadFileService();
        $categoriesList = Category::getCategoriesList();
        $createTaskForm = new CreateTaskForm();
        $userLocalityData = [
            'location' => 'Россия, ' . $user->city->name,
            'address' => $user->city->name,
            'city' => $user->city->name,
            'coordinates' => $locationService->getUsersCityCoordinates(),
        ];

        if (Yii::$app->request->getIsPost()) {
            $createTaskForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = WebResponse::FORMAT_JSON;
                return ActiveForm::validate($createTaskForm);
            }

            if ($createTaskForm->validate()) {
                $task = $taskService->createTask($createTaskForm);

                $uploadedFiles = UploadedFile::getInstances($createTaskForm, 'files');

                if (!empty($uploadedFiles)) {
                    foreach ($uploadedFiles as $uploadedFile) {
                        $file = $uploadFileService->upload($uploadedFile, 'task', $task->id);
                        $task->link('files', $file);
                    }
                }

                return $this->redirect(['tasks/view', 'id' => $task->id]);
            }
        }

        return $this->render('create', [
            'createTaskForm' => $createTaskForm,
            'categoriesList' => $categoriesList,
            'userLocalityData' => $userLocalityData,
        ]);
    }


    /**
     * @param int $id
     * @return WebResponse
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    public function actionRefuse(int $id): WebResponse
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $taskService = new TaskService();
        $taskService->refuseTask($task);

        return $this->redirect(['tasks/view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return WebResponse
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionCancel(int $id): WebResponse
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app->user->identity;
        $taskService = new TaskService();

        if ($user->id === $task->customer_id && $task->status === Task::STATUS_NEW) {
            $taskService->cancelTask($task);
        }

        return $this->redirect(['tasks/view', 'id' => $id]);
    }
}
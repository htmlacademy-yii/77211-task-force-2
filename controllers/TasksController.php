<?php

namespace app\controllers;

use app\models\Category;
use app\models\CreateResponseForm;
use app\models\CreateReviewForm;
use app\models\CreateTaskForm;
use app\models\User;
use app\services\CreateTaskService;
use app\services\LocationService;
use app\services\ResponseService;
use app\services\TaskService;
use app\services\UploadFileService;
use app\services\UserService;
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
     * @throws Exception
     */
    public function actionView(int $id): string
    {

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $this->view->title = "$task->title :: Taskforce";

        $taskStatusName = Task::getTaskStatusesList()[$task->status];
        $locationData = [];

        if (isset($task->city_id)) {
            $locationData = [
                'cityName' => $task->city->name,
                'address' => $task->address,
                'coordinates' => (new LocationService())->getTaskCoordinates($task->id),
            ];
        }

        $responses = (new ResponseService())->getResponses($task, Yii::$app->user->identity);
        $actionsMarkup = (new TaskService())->getAvailableActionsMarkup(Yii::$app->user->identity, $task);
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
        $categoriesList = Category::getCategoriesList();
        $createTaskForm = new CreateTaskForm();
        $userLocalityData = [
            'location' => 'Россия, ' . $user->city->name,
            'address' => $user->city->name,
            'city' => $user->city->name,
            'coordinates' => (new LocationService())->getUsersCityCoordinates(),
        ];

        if (Yii::$app->request->getIsPost()) {
            $createTaskForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = WebResponse::FORMAT_JSON;
                return ActiveForm::validate($createTaskForm);
            }

            if ($createTaskForm->validate()) {
                $task = (new CreateTaskService())->createTask($createTaskForm);

                $uploadedFiles = UploadedFile::getInstances($createTaskForm, 'files');

                if (!empty($uploadedFiles)) {
                    foreach ($uploadedFiles as $uploadedFile) {
                        $file = (new UploadFileService())->upload($uploadedFile, 'task', $task->id);
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
     */
    public function actionRefuse(int $id): WebResponse
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $task->status = Task::STATUS_FAILED;
        $task->update();

        $executor = $task->executor;
        $executor->updateCounters(['failed_tasks_count' => 1]);
        $executor->rating = (new UserService())->countUserRating($executor);
        $executor->status = User::STATUS_READY;
        $executor->update();

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
        $user = Yii::$app->user->identity;

        if (!$task) {
            throw new NotFoundHttpException();
        }

        if ($user->id === $task->customer_id && $task->status === Task::STATUS_NEW) {
            $task->status = Task::STATUS_CANCELED;
            $task->update();
        }

        return $this->redirect(['tasks/view', 'id' => $id]);
    }
}
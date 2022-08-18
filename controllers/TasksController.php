<?php

namespace app\controllers;

use app\models\Category;
use app\models\CreateTaskForm;
use app\models\User;
use app\services\CreateTaskService;
use app\services\ResponseService;
use app\services\TaskService;
use app\services\UploadFileService;
use yii\base\Exception;
use yii\web\Response as WebResponse;
use app\models\Task;
use app\models\TasksFilterForm;
use app\services\TasksFilterService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class TasksController extends SecuredController
{
    /**
     * @return array|array[]
     */
    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role === User::ROLE_EXECUTOR;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
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
        $responses = (new ResponseService())->getResponses($task, Yii::$app->user->identity);
        $actionsMarkup = (new TaskService())->getAvailableActionsMarkup(Yii::$app->user->identity, $task);

        $files = $task->files;

        return $this->render('view', [
            'task' => $task,
            'taskStatusName' => $taskStatusName,
            'responses' => $responses,
            'actionsMarkup' => $actionsMarkup,
            'files' => $files
        ]);
    }

    /**
     * @return array|string|WebResponse
     * @throws Exception
     */
    public function actionCreate(): WebResponse|array|string
    {
        $this->view->title = 'Cоздать задание :: Taskforce';

        $categoriesList = Category::getCategoriesList();
        $createTaskForm = new CreateTaskForm();

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
        ]);
    }
}
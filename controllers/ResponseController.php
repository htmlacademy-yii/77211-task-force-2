<?php

namespace app\controllers;

use app\models\CreateResponseForm;
use app\models\Response;
use app\models\Task;
use app\models\User;
use app\services\ResponseService;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response as WebResponse;

class ResponseController extends SecuredController
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
                        'actions' => ['create'],
                        'roles' => ['executorCanCreateResponse'],
                        'roleParams' => fn($rule) => [
                            'task' => Task::findOne(Yii::$app->request->post('CreateResponseForm')['task_id'])
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['accept'],
                        'roles' => ['customerCanAcceptResponse'],
                        'roleParams' => fn($rule) => [
                            'task' => (Response::findOne(Yii::$app->request->get('id'))->task)
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['customerCanRefuseResponse'],
                        'roleParams' => fn($rule) => [
                            'task' => (Response::findOne(Yii::$app->request->get('id'))->task)
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * @return WebResponse|bool
     */
    public function actionCreate(): WebResponse|bool
    {
        $responseForm = new CreateResponseForm();
        $responseService = new ResponseService();
        $user = Yii::$app->user->identity;

        if ($responseForm->load(Yii::$app->request->post()) && $responseForm->validate()) {

            $isUserMadeResponse = $responseService->checkIsUserMadeResponseForTask($user->id, $responseForm->task_id);

            if ($user->role === User::ROLE_EXECUTOR && !$isUserMadeResponse) {
                $response = $responseService->createResponse($responseForm, $user);

                return $this->redirect(['tasks/view', 'id' => $response->task_id]);
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return WebResponse
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Exception
     */
    public function actionAccept(int $id): WebResponse
    {
        $response = Response::findOne($id);

        if (!$response) {
            throw new NotFoundHttpException();
        }

        $task = $response->task;
        $executor = $response->executor;
        $responseService = new ResponseService();

        if (Yii::$app->user->id === $task->customer_id && $task->status === Task::STATUS_NEW) {
            $responseService->acceptResponse($task, $executor);
        }

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    /**
     * @param int $id
     * @return WebResponse
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionRefuse(int $id): WebResponse
    {
        $response = Response::findOne($id);

        if (!$response) {
            throw new NotFoundHttpException();
        }

        $responseService = new ResponseService();
        $responseService->refuseResponse($response);

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }
}
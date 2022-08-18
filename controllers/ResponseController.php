<?php

namespace app\controllers;

use app\models\CreateResponseForm;
use app\models\Response;
use app\models\Task;
use app\models\User;
use app\services\ResponseService;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response as WebResponse;

class ResponseController extends SecuredController
{
    /**
     * @return WebResponse|bool
     */
    public function actionCreate(): WebResponse|bool
    {
        $responseForm = new CreateResponseForm();
        $user = Yii::$app->user->identity;

        if ($responseForm->load(Yii::$app->request->post()) && $responseForm->validate()) {

            $isUserMadeResponse = (new ResponseService())->checkIsUserMadeResponseForTask($user->id, $responseForm->task_id);

            if ($user->role === User::ROLE_EXECUTOR && !$isUserMadeResponse) {
                $response = new Response();
                $response->loadDefaultValues();
                $response->task_id = $responseForm->task_id;
                $response->executor_id = $user->id;
                $response->comment = $responseForm->comment;
                $response->budget = (int) $responseForm->budget;
                $response->save();

                return $this->redirect(['tasks/view', 'id' => $response->task_id]);
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return WebResponse
     * @throws NotFoundHttpException
     */
    public function actionAccept(int $id): WebResponse
    {
        $response = Response::findOne($id);

        if (!$response) {
            throw new NotFoundHttpException();
        }

        $task = $response->task;

        if (Yii::$app->user->id === $task->customer_id && $task->status === Task::STATUS_NEW) {
            $task->status = Task::STATUS_PROCESSING;
            $task->executor_id = $response->executor_id;
            $task->save();
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

        $response->is_refused = 1;
        $response->update();

        return $this->redirect(['tasks/view', 'id' => $response->task_id]);
    }
}
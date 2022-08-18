<?php

namespace app\controllers;

use app\models\Response;
use app\models\Task;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response as WebResponse;

class ResponseController extends SecuredController
{
    public function actionAccept(int $id)
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
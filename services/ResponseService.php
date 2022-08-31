<?php

namespace app\services;

use app\models\CreateResponseForm;
use app\models\Response;
use app\models\Task;
use app\models\User;
use yii\db\Exception;
use yii\db\StaleObjectException;

class ResponseService
{
    /**
     * @param Task $task
     * @param User $user
     * @return array
     */
    public function getResponses(Task $task, User $user): array
    {
        $query = Response::find()
        ->where(['task_id' => $task->id])
        ->with('executor.avatarFile', 'executor.reviewsWhereUserIsReceiver');

        if ($user->role === User::ROLE_EXECUTOR) {
            $query->andWhere(['executor_id' => $user->id]);
        }

        return $query->all();
    }

    /**
     * @param int $userId
     * @param int $taskId
     * @return bool
     */
    public function checkIsUserMadeResponseForTask(int $userId, int $taskId): bool
    {
        return Response::find()->where(['executor_id' => $userId, 'task_id' => $taskId])->exists();
    }

    /**
     * @param CreateResponseForm $form
     * @param User $user
     * @return Response
     */
    public function createResponse(CreateResponseForm $form, User $user): Response
    {
        $response = new Response();
        $response->loadDefaultValues();
        $response->task_id = $form->task_id;
        $response->executor_id = $user->id;
        $response->comment = $form->comment;
        $response->budget = (int) $form->budget;
        $response->save();

        return $response;
    }

    /**
     * @param Task $task
     * @param User $executor
     * @return void
     * @throws Exception
     * @throws StaleObjectException
     */
    public function acceptResponse(Task $task, User $executor): void
    {
        $transaction = Task::getDb()->beginTransaction();

        try {
            $task->status = Task::STATUS_PROCESSING;
            $task->executor_id = $executor->id;
            $task->save();

            $executor->status = User::STATUS_BUSY;
            $executor->update();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Response $response
     * @return void
     * @throws StaleObjectException
     */
    public function refuseResponse(Response $response): void
    {
        $response->is_refused = 1;
        $response->update();
    }
}
<?php

namespace app\services;

use app\models\Response;
use app\models\Task;
use app\models\User;

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
}
<?php

namespace app\services;

use app\models\Task;
use app\models\User;
use app\services\actions\ActionCancel;
use app\services\actions\ActionFinish;
use app\services\actions\ActionRefuse;
use app\services\actions\ActionRespond;
use yii\base\Exception;
use yii\helpers\Url;

class TaskService
{
    /**
     * @param User $user
     * @param Task $task
     * @return array
     * @throws Exception
     */
    public function getAvailableActionsMarkup(User $user, Task $task): array
    {
        $actionsMarkup = [];

        switch ($task->status) {
            case Task::STATUS_NEW:
                if (ActionCancel::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="' . Url::to(['tasks/cancel', 'id' => $task->id]) . '" class="button button--yellow action-btn">Отменить задание</a>';
                }

                if (ActionRespond::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>';
                }
                break;
            case Task::STATUS_PROCESSING:
                if (ActionFinish::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>';
                }

                if (ActionRefuse::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>';
                }
                break;
            case Task::STATUS_FAILED:
            case Task::STATUS_DONE:
            case Task::STATUS_CANCELED:
                return [];
            default:
                throw new Exception("Что-то пошло не так");
        }

        return $actionsMarkup;
    }
}
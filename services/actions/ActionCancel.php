<?php

namespace app\services\actions;

use app\models\Task;
use app\models\User;

class ActionCancel extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(User $user, Task $task): bool
    {
        return $user->id === $task->customer_id;
    }
}
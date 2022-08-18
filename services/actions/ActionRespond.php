<?php

namespace app\services\actions;

use app\models\Task;
use app\models\User;
use app\services\ResponseService;

class ActionRespond extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(User $user, Task $task): bool
    {
        $isUserMadeResponse = (new ResponseService())->checkIsUserMadeResponseForTask($user->id, $task->id);
        return !$isUserMadeResponse && $user->role === User::ROLE_EXECUTOR;
    }
}
<?php

namespace app\services\actions;

use app\models\Task;
use app\models\User;

class ActionRespond extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public static function isCurrentUserCanAct(User $user, Task $task): bool
    {
        $isUserMadeResponse = $task->getResponses()->where(['executor_id' => $user->id])->exists();
        return !$isUserMadeResponse && $user->role === User::ROLE_EXECUTOR;
    }
}
<?php

namespace app\services\actions;

use app\models\Task;
use app\models\User;

abstract class AbstractAction
{
    /**
     * @param User $user
     * @param Task $task
     * @return bool
     */
    abstract public static function isCurrentUserCanAct(User $user, Task $task): bool;
}
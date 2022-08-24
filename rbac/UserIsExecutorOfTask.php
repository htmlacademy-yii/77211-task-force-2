<?php

namespace app\rbac;

use yii\rbac\Rule;

class UserIsExecutorOfTask extends Rule
{
    public $name = 'userIsExecutorOfTask';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        return isset($params['task']) && $params['task']->executor_id === $user;
    }
}
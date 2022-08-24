<?php

namespace app\rbac;

use app\models\Task;
use yii\rbac\Rule;

class UserIsCreatorOfTaskAndTaskStatusIsNew extends Rule
{
    public $name = 'userIsCreatorOfTaskAndTaskStatusIsNew';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        return isset($params['task']) && $params['task']->customer_id === $user && $params['task']->status === Task::STATUS_NEW;
    }
}
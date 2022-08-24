<?php

namespace app\rbac;

use yii\rbac\Rule;

class UserIsCreatorOfTask extends Rule
{
    public $name = 'userIsCreatorOfTask';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        return isset($params['task']) && $params['task']->customer_id === $user;
    }
}
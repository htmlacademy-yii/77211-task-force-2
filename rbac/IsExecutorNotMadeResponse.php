<?php

namespace app\rbac;

use app\services\ResponseService;
use yii\rbac\Rule;

class IsExecutorNotMadeResponse extends Rule
{
    public $name = 'isExecutorMadeResponse';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params): bool
    {
        return isset($params['task']) && !(new ResponseService())->checkIsUserMadeResponseForTask(
                $user,
                $params['task']->id
            );
    }
}
<?php

namespace Taskforce\Logic\Actions;

class ActionDone extends AbstractAction
{
    protected string $name = 'Выполнено';
    protected string $code = 'done';

    /**
     * @inheritDoc
     */
    public function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}

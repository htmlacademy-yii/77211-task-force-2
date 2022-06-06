<?php

namespace Taskforce\Logic\Actions;

class ActionRefuse extends AbstractAction
{
    protected string $name = 'Отказаться';
    protected string $code = 'refuse';

    /**
     * @inheritDoc
     */
    public function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $executorId;
    }
}

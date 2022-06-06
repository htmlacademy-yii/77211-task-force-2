<?php

namespace Taskforce\Logic\Actions;

class ActionStart extends AbstractAction
{
    protected string $name = 'Начать задание';
    protected string $code = 'start';

    /**
     * @inheritDoc
     */
    public function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}

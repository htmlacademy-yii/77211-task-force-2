<?php

namespace Taskforce\Logic\Actions;

class ActionRespond extends AbstractAction
{
    protected string $name = 'Откликнуться';
    protected string $code = 'respond';

    /**
     * @inheritDoc
     */
    public function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId !== $customerId;
    }
}

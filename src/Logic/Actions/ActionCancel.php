<?php

namespace App\Logic\Actions;

class ActionCancel extends AbstractAction
{
    protected string $name = 'Отменить';
    protected string $code = 'cancel';

    /**
     * @inheritDoc
     */
    public function isCurrentUserCanAct(int $executorId, int $customerId, int $currentUserId): bool
    {
        return $currentUserId === $customerId;
    }
}

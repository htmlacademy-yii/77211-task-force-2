<?php

namespace App\Logic;

use App\Logic\Actions\ActionCancel;
use App\Logic\Actions\ActionStart;
use App\Logic\Actions\ActionDone;
use App\Logic\Actions\ActionRefuse;
use App\Logic\Actions\ActionRespond;
use App\Logic\Exceptions\ActionException;
use App\Logic\Exceptions\StatusException;

class Task
{
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_PROCESSING = 3;
    public const STATUS_DONE = 4;
    public const STATUS_FAILED = 5;

    public const ACTION_STATUS_MAP = [
        'start' => self::STATUS_PROCESSING,
        'cancel' => self::STATUS_CANCELED,
        'respond' => self::STATUS_NEW,
        'done' => self::STATUS_DONE,
        'refuse' => self::STATUS_FAILED
    ];

    private int $customerId;
    private ?int $executorId;
    private int $status;

    /**
     * @param int $customerId
     * @param int|null $executorId
     * @param int $status
     */
    public function __construct(int $customerId, int $executorId = null, int $status = self::STATUS_NEW)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getStatusesList(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_PROCESSING => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * @param string $action
     * @return int
     * @throws ActionException
     */
    public function getNextStatus(string $action): int
    {
        return array_key_exists(
            $action,
            self::ACTION_STATUS_MAP
        ) ? self::ACTION_STATUS_MAP[$action] : throw new ActionException("Неизвестное действие $action");
    }

    /**
     * @param int $currentUserId
     * @return array
     * @throws StatusException
     */
    public function getAvailableActions(int $currentUserId): array
    {
        $actions = [];

        switch ($this->status) {
            case self::STATUS_NEW:
                if ((new ActionStart())->isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionStart();
                }

                if ((new ActionCancel())->isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionCancel();
                }

                if ((new ActionRespond())->isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionRespond();
                }
                break;
            case self::STATUS_PROCESSING:
                if ((new ActionDone())->isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionDone();
                }

                if ((new ActionRefuse())->isCurrentUserCanAct($this->executorId, $this->customerId, $currentUserId)) {
                    $actions[] = new ActionRefuse();
                }
                break;
            case self::STATUS_FAILED:
            case self::STATUS_DONE:
            case self::STATUS_CANCELED:
                return [];
            default:
                throw new StatusException("Неизвестный статус $this->status");
        }

        return $actions;
    }
}

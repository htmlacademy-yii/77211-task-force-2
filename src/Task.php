<?php

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_CANCEL = 'cancel';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_DONE = 'done';
    public const ACTION_REFUSE = 'refuse';

    public const ACTION_STATUS_MAP = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_PROCESSING,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    private int $customerId;
    private ?int $executorId;
    private string $status = self::STATUS_NEW;

    /**
     * @param int $customerId
     * @param int|null $executorId
     */
    public function __construct(int $customerId, int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
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
     * @return array
     */
    public function getActionsList(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_DONE => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    /**
     * @param string $action
     * @return string
     * @throws Exception
     */
    public function getNextStatus(string $action): string
    {
        return array_key_exists(
            $action,
            self::ACTION_STATUS_MAP
        ) ? self::ACTION_STATUS_MAP[$action] : throw new Exception("Unknown action $action");
    }

    /**
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function getAvailableAction(string $status): array
    {
        switch ($status) {
            case self::STATUS_NEW:
                return [self::ACTION_CANCEL, self::ACTION_RESPOND];
            case self::STATUS_PROCESSING:
                return [self::ACTION_DONE, self::ACTION_REFUSE];
            case self::STATUS_FAILED:
            case self::STATUS_DONE:
            case self::STATUS_CANCELED:
                return [];
            default:
                throw new Exception("Unknown status $status");
        }
    }
}

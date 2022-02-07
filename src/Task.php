<?php

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_CONFIRM = 'confirm';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_DONE = 'done';
    public const ACTION_REFUSE = 'refuse';

    public const ACTION_STATUS_MAP = [
        self::ACTION_CONFIRM => self::STATUS_PROCESSING,
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_NEW,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    private int $customerId;
    private ?int $executorId;
    private string $status;

    /**
     * @param int $customerId
     * @param int|null $executorId
     * @param string $status
     */
    public function __construct(int $customerId, int $executorId = null, string $status = self::STATUS_NEW)
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
     * @return array
     */
    public function getActionsList(): array
    {
        return [
            self::ACTION_CONFIRM => 'Принять',
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
     * @param int $currentUserId
     * @return array
     */
    public function getAvailableAction(int $currentUserId): array
    {
        $actions = [];

        if ($currentUserId === $this->customerId) {
            if ($this->status === self::STATUS_NEW) {
                $actions[] = [self::ACTION_CONFIRM, self::ACTION_CANCEL];
            }
            if ($this->status === self::STATUS_PROCESSING) {
                $actions[] = self::ACTION_DONE;
            }
        }
        if ($currentUserId === $this->executorId) {
            if ($this->status === self::STATUS_NEW) {
                $actions[] = self::ACTION_RESPOND;
            }
            if ($this->status === self::STATUS_PROCESSING) {
                $actions[] = self::ACTION_REFUSE;
            }
        }

        return $actions;
    }
}

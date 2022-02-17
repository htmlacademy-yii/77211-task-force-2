<?php

namespace Tests\Logic;

use App\Logic\Task;
use Exception;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private const CUSTOMER_ID = 1;
    private const EXECUTOR_ID = 2;
    private Task $taskWithStatusNew;
    private Task $taskWithStatusProcessing;

    protected function setUp(): void
    {
        $this->taskWithStatusNew = new Task(self::CUSTOMER_ID, self::EXECUTOR_ID);
        $this->taskWithStatusProcessing = new Task(self::CUSTOMER_ID, self::EXECUTOR_ID, Task::STATUS_PROCESSING);
    }

    /**
     * @return array[]
     */
    public function statusesProvider(): array
    {
        return [
            [Task::STATUS_PROCESSING, Task::ACTION_START],
            [Task::STATUS_CANCELED, Task::ACTION_CANCEL],
            [Task::STATUS_NEW, Task::ACTION_RESPOND],
            [Task::STATUS_DONE, Task::ACTION_DONE],
            [Task::STATUS_FAILED, Task::ACTION_REFUSE]
        ];
    }

    /**
     * @return array[]
     */
    public function actionsForTaskWithStatusNewProvider(): array
    {
        return [
            [[[Task::ACTION_START, Task::ACTION_CANCEL]], self::CUSTOMER_ID],
            [[Task::ACTION_RESPOND], self::EXECUTOR_ID],
        ];
    }

    /**
     * @return array[]
     */
    public function actionsForTaskWithStatusProcessingProvider(): array
    {
        return [
            [[Task::ACTION_DONE], self::CUSTOMER_ID],
            [[Task::ACTION_REFUSE], self::EXECUTOR_ID],
        ];
    }

    /**
     * @param string $status
     * @param string $action
     * @throws Exception
     * @dataProvider statusesProvider
     */
    public function testGetNextStatus(string $status, string $action)
    {
        $this->assertEquals($status, $this->taskWithStatusNew->getNextStatus($action));
    }


    /**
     * @param array $actions
     * @param int $currentUserId
     * @dataProvider actionsForTaskWithStatusNewProvider
     */
    public function testGetAvailableActionForTaskWithStatusNew(array $actions, int $currentUserId)
    {
        $this->assertEquals($actions, $this->taskWithStatusNew->getAvailableAction($currentUserId));
    }

    /**
     * @param array $actions
     * @param int $currentUserId
     * @dataProvider actionsForTaskWithStatusProcessingProvider
     */
    public function testGetAvailableActionForTaskWithStatusProcessing(array $actions, int $currentUserId)
    {
        $this->assertEquals($actions, $this->taskWithStatusProcessing->getAvailableAction($currentUserId));
    }
}


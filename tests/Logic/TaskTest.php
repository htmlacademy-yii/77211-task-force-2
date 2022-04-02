<?php

namespace Tests\Logic;

use App\Logic\Actions\ActionCancel;
use App\Logic\Actions\ActionDone;
use App\Logic\Actions\ActionRefuse;
use App\Logic\Actions\ActionRespond;
use App\Logic\Actions\ActionStart;
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
            [Task::STATUS_PROCESSING, 'start'],
            [Task::STATUS_CANCELED, 'cancel'],
            [Task::STATUS_NEW, 'respond'],
            [Task::STATUS_DONE, 'done'],
            [Task::STATUS_FAILED, 'refuse'],
        ];
    }

    /**
     * @param int $status
     * @param string $action
     * @throws Exception
     * @dataProvider statusesProvider
     */
    public function testGetNextStatus(int $status, string $action)
    {
        $this->assertEquals($status, $this->taskWithStatusNew->getNextStatus($action));
    }

    /**
     * @return array[]
     */
    public function actionsForTaskWithStatusNewProvider(): array
    {
        return [
            [[new ActionStart(), new ActionCancel()], self::CUSTOMER_ID],
            [[new ActionRespond()], self::EXECUTOR_ID],
        ];
    }

    /**
     * @return array[]
     */
    public function actionsForTaskWithStatusProcessingProvider(): array
    {
        return [
            [[new ActionDone()], self::CUSTOMER_ID],
            [[new ActionRefuse()], self::EXECUTOR_ID],
        ];
    }

    /**
     * @param array $actions
     * @param int $currentUserId
     * @return void
     * @throws Exception
     * @dataProvider actionsForTaskWithStatusNewProvider
     */
    public function testGetAvailableActionsForTaskWithStatusNew(array $actions, int $currentUserId)
    {
        $this->assertEquals($actions, $this->taskWithStatusNew->getAvailableActions($currentUserId));
    }

    /**
     * @param array $actions
     * @param int $currentUserId
     * @return void
     * @throws Exception
     * @dataProvider actionsForTaskWithStatusProcessingProvider
     */
    public function testGetAvailableActionsForTaskWithStatusProcessing(array $actions, int $currentUserId)
    {
        $this->assertEquals($actions, $this->taskWithStatusProcessing->getAvailableActions($currentUserId));
    }
}


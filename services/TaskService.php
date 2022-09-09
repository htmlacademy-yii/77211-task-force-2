<?php

namespace app\services;

use app\models\CreateTaskForm;
use app\models\Task;
use app\models\User;
use app\services\actions\ActionCancel;
use app\services\actions\ActionFinish;
use app\services\actions\ActionRefuse;
use app\services\actions\ActionRespond;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\helpers\Url;

class TaskService
{
    /**
     * @param User $user
     * @param Task $task
     * @return array
     * @throws Exception
     */
    public function getAvailableActionsMarkup(User $user, Task $task): array
    {
        $actionsMarkup = [];

        switch ($task->status) {
            case Task::STATUS_NEW:
                if (ActionCancel::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="' . Url::to(['tasks/cancel', 'id' => $task->id]) . '" class="button button--yellow action-btn">Отменить задание</a>';
                }

                if (ActionRespond::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>';
                }
                break;
            case Task::STATUS_PROCESSING:
                if (ActionFinish::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>';
                }

                if (ActionRefuse::isCurrentUserCanAct($user, $task)) {
                    $actionsMarkup[] = '<a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>';
                }
                break;
            case Task::STATUS_FAILED:
            case Task::STATUS_DONE:
            case Task::STATUS_CANCELED:
                return [];
            default:
                throw new Exception("Что-то пошло не так");
        }

        return $actionsMarkup;
    }

    /**
     * @param CreateTaskForm $form
     * @return Task
     * @throws Exception
     */
    public function createTask(CreateTaskForm $form): Task
    {
        $locationService = new LocationService();

        $task = new Task();
        $task->loadDefaultValues();

        $task->customer_id = Yii::$app->user->id;
        $task->title = $form->title;
        $task->description = $form->description;
        $task->category_id = $form->category_id;
        $task->budget = is_null($form->budget) ? null : (int) $form->budget;

        if ($form->location !== '' && $locationService->isCityExistsInDB($form->city)) {
            $task->city_id = $locationService->getCityIdByName($form->city);
            $task->address = $form->address;
            $task->coordinates = null;

        }

        $task->deadline_at = $form->deadline_at;
        $task->save();

        if ($form->location !== '') {
            $locationService->setPointCoordinatesToTask($task->id, $form->lat, $form->long);
        }

        return $task;
    }

    /**
     * @param Task $task
     * @return void
     * @throws StaleObjectException
     * @throws \yii\db\Exception
     */
    public function refuseTask(Task $task): void
    {
        $userService = new UserService();
        $executor = $task->executor;

        $transaction = Task::getDb()->beginTransaction();

        try {
            $task->status = Task::STATUS_FAILED;
            $task->update();
            $executor->updateCounters(['failed_tasks_count' => 1]);
            $executor->rating = $userService->countUserRating($executor);
            $executor->status = User::STATUS_READY;
            $executor->update();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Task $task
     * @return void
     * @throws StaleObjectException
     */
    public function cancelTask(Task $task): void
    {
        $task->status = Task::STATUS_CANCELED;
        $task->update();
    }

    /**
     * @param User $user
     * @param string $statusParam
     * @return ActiveQuery
     */
    public function getMyTasks(User $user, string $statusParam): ActiveQuery
    {
        $role = $user->role;
        $query = Task::find();

        switch ($statusParam) {
            case 'new':
                $query->andWhere(['status' => Task::STATUS_NEW]);
                break;
            case 'processing':
                $query->andWhere(['status' => Task::STATUS_PROCESSING]);
                break;
            case 'overdue':
                $query->andWhere(['status' => Task::STATUS_PROCESSING]);
                $query->andWhere(['<', 'deadline_at', new Expression('NOW()')]);
                break;
            case 'closed':
                $query->orWhere(['status' => Task::STATUS_DONE]);
                $query->orWhere(['status' => Task::STATUS_FAILED]);
                if ($role === User::ROLE_CUSTOMER) {
                    $query->orWhere(['status' => Task::STATUS_CANCELED]);
                }
                break;
        }

        if ($role === User::ROLE_CUSTOMER) {
            $query->AndWhere(['customer_id' => $user->id]);
        }

        if ($role === User::ROLE_EXECUTOR) {
            $query->AndWhere(['executor_id' => $user->id]);
        }

        return $query->with('city', 'category');
    }
}
<?php

namespace app\services;

use app\models\CreateTaskForm;
use app\models\Task;
use Yii;
use yii\base\Exception;

class CreateTaskService
{
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
}
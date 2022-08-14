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
        $task = new Task();
        $task->loadDefaultValues();

        $task->customer_id = Yii::$app->user->id;
        $task->title = $form->title;
        $task->description = $form->description;
        $task->category_id = $form->category_id;
        $task->budget = is_null($form->budget) ? null : (int) $form->budget;

        // TODO: Добавить city_id и coordinates
        $task->city_id = 1; // Временно!

        $task->deadline_at = $form->deadline_at;

        if (!$task->save()) {
            throw new Exception('Что-то пошло не так');
        }

        return $task;
    }
}
<?php

namespace app\services;

use app\models\Response;
use app\models\Task;
use app\models\TasksFilterForm;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class TasksFilterService
{
    /**
     * @param TasksFilterForm $form
     * @return ActiveQuery
     */
    public function filter(TasksFilterForm $form): ActiveQuery
    {
        $query = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->with('city', 'category');

        if (!is_null($form->categories)) {
            $query->andWhere(['category_id' => $form->categories]);
        }

        if (!is_null($form->withoutResponse)) {
            $taskIdsWithResponses = ArrayHelper::getColumn(Response::find()->asArray()->all(), 'task_id');
            $query->andWhere(['not in', 'id', $taskIdsWithResponses]);
        }

        if (!is_null($form->remote)) {
            $query->andWhere(['city_id' => null]);
        }

        if ((int) $form->period !== 0) {
            $query->andWhere("created_at > NOW() - INTERVAL :period HOUR", [
                ':period' => (int) $form->period
            ]);
        }

        return $query;
    }
}
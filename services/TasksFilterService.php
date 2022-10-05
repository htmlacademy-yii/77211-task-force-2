<?php

namespace app\services;

use app\models\Response;
use app\models\Task;
use app\models\TasksFilterForm;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class TasksFilterService
{
    /**
     * @return ActiveQuery
     */
    public static function getDefaultQuery(): ActiveQuery
    {
        return Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->andWhere(['or', ['city_id' => Yii::$app->user->identity->city_id], ['city_id' => null]])
            ->with('city', 'category');
    }

    /**
     * @param TasksFilterForm $form
     * @return ActiveQuery
     */
    public function filter(TasksFilterForm $form): ActiveQuery
    {
        $query = self::getDefaultQuery();

        if (!empty($form->categories)) {
            $query->andWhere(['category_id' => $form->categories]);
        }

        if (!is_null($form->remote)) {
            $query->andWhere(['city_id' => null]);
        }

        if (!is_null($form->withoutResponse)) {
            $taskIdsWithResponses = ArrayHelper::getColumn(Response::find()->asArray()->all(), 'task_id');
            $query->andWhere(['not in', 'id', $taskIdsWithResponses]);
        }

        if ((int) $form->period !== 0) {
            $query->andWhere("created_at > NOW() - INTERVAL :period HOUR", [
                ':period' => (int) $form->period
            ]);
        }

        return $query;
    }
}
<?php

namespace app\controllers;

use app\models\CreateReviewForm;
use app\models\Review;
use app\models\Task;
use app\models\User;
use app\services\UserService;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Response as WebResponse;

class ReviewController extends SecuredController
{
    /**
     * @return WebResponse|bool
     * @throws StaleObjectException
     */
    public function actionCreate(): WebResponse|bool
    {

        $reviewForm = new CreateReviewForm();
        $customer = Yii::$app->user->identity;

        if ($reviewForm->load(Yii::$app->request->post()) && $reviewForm->validate()) {

            $task = Task::findOne($reviewForm->task_id);

            if ($customer->id === $task->customer_id) {
                $review = new Review();
                $review->loadDefaultValues();
                $review->task_id = $reviewForm->task_id;
                $review->author_id = $reviewForm->author_id;
                $review->user_id = $reviewForm->user_id;
                $review->rate = $reviewForm->rate;
                $review->comment = $reviewForm->comment;

                if ($review->save()) {
                    $task->status = Task::STATUS_DONE;
                    $task->update();

                    $executor = User::findOne($review->user_id);
                    $executor->rating = (new UserService())->countUserRating($executor);
                    $executor->update();
                }

                return $this->redirect(['tasks/view', 'id' => $review->task_id]);
            }
        }

        return false;
    }
}
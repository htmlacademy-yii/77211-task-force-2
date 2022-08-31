<?php

namespace app\services;

use app\models\CreateReviewForm;
use app\models\Review;
use app\models\Task;
use app\models\User;
use yii\db\Exception;
use yii\db\StaleObjectException;

class ReviewService
{
    /**
     * @param CreateReviewForm $form
     * @param Task $task
     * @return Review
     * @throws StaleObjectException
     * @throws Exception
     */
    public function createReview(CreateReviewForm $form, Task $task): Review
    {
        $userService = new UserService();
        $transaction = Review::getDb()->beginTransaction();

        try {
            $review = new Review();
            $review->loadDefaultValues();
            $review->task_id = $form->task_id;
            $review->author_id = $form->author_id;
            $review->user_id = $form->user_id;
            $review->rate = $form->rate;
            $review->comment = $form->comment;
            $review->save();

            $task->status = Task::STATUS_DONE;
            $task->update();

            $executor = $review->user;
            $executor->rating = $userService->countUserRating($executor);
            $executor->status = User::STATUS_READY;
            $executor->update();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $review;
    }
}
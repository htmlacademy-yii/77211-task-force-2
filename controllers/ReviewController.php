<?php

namespace app\controllers;

use app\models\CreateReviewForm;
use app\models\Task;
use app\services\ReviewService;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ReviewController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['customerCanCreateReview'],
                        'roleParams' => fn($rule) => [
                            'task' => Task::findOne(Yii::$app->request->post('CreateReviewForm')['task_id'] ?? null)
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array|Response
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionCreate()
    {
        $reviewForm = new CreateReviewForm();
        $reviewService = new ReviewService();
        $customer = Yii::$app->user->identity;

        if (Yii::$app->request->getIsPost()) {
            $reviewForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($reviewForm);
            }

            if ($reviewForm->validate()) {
                $task = Task::findOne($reviewForm->task_id);

                if ($customer->id === $task->customer_id) {
                    $review = $reviewService->createReview($reviewForm, $task);
                    return $this->redirect(['tasks/view', 'id' => $review->task_id]);
                }
            }
        }

        return $this->goBack();
    }
}
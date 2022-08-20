<?php

namespace app\commands;

use app\models\User;
use app\rbac\IsExecutorNotMadeResponse;
use app\rbac\UserIsCreatorOfTask;
use app\rbac\UserIsCreatorOfTaskAndTaskStatusIsNew;
use app\rbac\UserIsExecutorOfTask;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @return void
     * @throws Exception
     */
    public function actionInit(): void
    {
        $auth = Yii::$app->authManager;

        $isExecutorNotMadeResponseRule = new IsExecutorNotMadeResponse();
        $userIsCreatorOfTask = new UserIsCreatorOfTask();
        $userIsCreatorOfTaskAndTaskStatusIsNew = new UserIsCreatorOfTaskAndTaskStatusIsNew();
        $userIsExecutorOfTask = new UserIsExecutorOfTask();

        $auth->add($isExecutorNotMadeResponseRule);
        $auth->add($userIsCreatorOfTask);
        $auth->add($userIsCreatorOfTaskAndTaskStatusIsNew);
        $auth->add($userIsExecutorOfTask);

        $customerCanAcceptResponse = $auth->createPermission('customerCanAcceptResponse');
        $customerCanAcceptResponse->description = 'Customer can accept response for task';
        $customerCanAcceptResponse->ruleName = $userIsCreatorOfTaskAndTaskStatusIsNew->name;
        $auth->add($customerCanAcceptResponse);

        $customerCanRefuseResponse = $auth->createPermission('customerCanRefuseResponse');
        $customerCanRefuseResponse->description = 'Customer can refuse response for task';
        $customerCanRefuseResponse->ruleName = $userIsCreatorOfTaskAndTaskStatusIsNew->name;
        $auth->add($customerCanRefuseResponse);

        $customerCanCreateReview = $auth->createPermission('customerCanCreateReview');
        $customerCanCreateReview->description = 'Customer can create review for finished task';
        $customerCanCreateReview->ruleName = $userIsCreatorOfTask->name;
        $auth->add($customerCanCreateReview);

        $customerCanCreateTask = $auth->createPermission('customerCanCreateTask');
        $customerCanCreateTask->description = 'Customer can create new task';
        $auth->add($customerCanCreateTask);

        $customerCanCancelTask = $auth->createPermission('customerCanCancelTask');
        $customerCanCancelTask->description = 'Customer can cancel task';
        $customerCanCancelTask->ruleName = $userIsCreatorOfTaskAndTaskStatusIsNew->name;
        $auth->add($customerCanCancelTask);

        $executorCanCreateResponse = $auth->createPermission('executorCanCreateResponse');
        $executorCanCreateResponse->description = 'Executor can create response for task';
        $executorCanCreateResponse->ruleName = $isExecutorNotMadeResponseRule->name;
        $auth->add($executorCanCreateResponse);

        $executorCanRefuseTask = $auth->createPermission('executorCanRefuseTask');
        $executorCanRefuseTask->description = 'Executor can refuse task';
        $executorCanRefuseTask->ruleName = $userIsExecutorOfTask->name;
        $auth->add($executorCanRefuseTask);

        $customer = $auth->createRole('customer');
        $auth->add($customer);
        $auth->addChild($customer, $customerCanAcceptResponse);
        $auth->addChild($customer, $customerCanRefuseResponse);
        $auth->addChild($customer, $customerCanCreateReview);
        $auth->addChild($customer, $customerCanCreateTask);
        $auth->addChild($customer, $customerCanCancelTask);

        $executor = $auth->createRole('executor');
        $auth->add($executor);
        $auth->addChild($executor, $executorCanCreateResponse);
        $auth->addChild($executor, $executorCanRefuseTask);

        $users = User::find()->all();

        foreach ($users as $user) {
            if ($user->role === User::ROLE_CUSTOMER) {
                $auth->assign($customer, $user->id);
            } else {
                $auth->assign($executor, $user->id);
            }
        }
    }
}
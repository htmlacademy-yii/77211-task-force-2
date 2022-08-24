<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class SecuredController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            $this->redirect(['tasks/index']);
                        }
                    ]
                ]
            ]
        ];
    }
}
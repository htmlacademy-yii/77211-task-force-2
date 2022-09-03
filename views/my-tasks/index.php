<?php

/**
 * @var yii\web\View $this
 * @var ActiveDataProvider $tasksDataProvider
 * @var string $title
 */

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\widgets\Menu;

?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?= Menu::widget([
        'items' => [
            [
                'label' => 'Новые',
                'url' => ['my-tasks/index', 'status' => 'new'],
                'visible' => Yii::$app->user->identity->role === User::ROLE_CUSTOMER,
                'active' => function ($item, $hasActiveChild, $isItemActive, $widget) {
                    $queryString = Yii::$app->request->queryString;
                    return $queryString === '' || $queryString === 'status=new';
                }
            ],
            [
                'label' => 'В процессе',
                'url' => ['my-tasks/index', 'status' => 'processing'],
                'active' => function ($item, $hasActiveChild, $isItemActive, $widget) {
                    $queryString = Yii::$app->request->queryString;
                    if (Yii::$app->user->identity->role === User::ROLE_EXECUTOR) {
                        return $queryString === '' || $queryString === 'status=processing';
                    } else {
                        return $queryString === 'status=processing';
                    }
                }
            ],
            ['label' => 'Закрытые', 'url' => ['my-tasks/index', 'status' => 'closed']],
            [
                'label' => 'Просрочено',
                'url' => ['my-tasks/index', 'status' => 'overdue'],
                'visible' => Yii::$app->user->identity->role === User::ROLE_EXECUTOR
            ],
        ],
        'activeCssClass' => 'side-menu-item--active',
        'options' => [
            'class' => 'side-menu-list',
        ],
        'itemOptions' => [
            'class' => 'side-menu-item',
        ],
        'linkTemplate' => '<a class="link link--nav" href="{url}">{label}</a>',
    ]) ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular"><?= $title ?></h3>
    <?= $this->render('//inc/_list', ['tasksDataProvider' => $tasksDataProvider]) ?>
</div>
<?php

/**
 * @var Task $model
 */

use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="task-card">
    <div class="header-task">
        <a href="<?= Url::to(['tasks/view', 'id' => $model->id]) ?>" class="link link--block link--big">
            <?= Html::encode($model->title) ?>
        </a>
        <p class="price price--task"><?= Html::encode($model->budget) ?> ₽</p>
    </div>
    <p class="info-text">
        <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span>
    </p>
    <p class="task-text"><?= Html::encode($model->description) ?></p>
    <div class="footer-task">
        <?php if (isset($model->city)): ?>
            <p class="info-text town-text"><?= $model->city->name ?></p>
        <?php endif; ?>
        <p class="info-text category-text"><?= $model->category->name ?></p>
        <a href="<?= Url::to(['tasks/view', 'id' => $model->id]) ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>

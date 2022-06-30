<?php

/**
 * @var yii\web\View $this
 * @var Task[] $tasks
 */

use app\models\Task;

$this->title = 'Новые задания :: Taskforce';
?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php foreach ($tasks as $task): ?>
        <div class="task-card">
            <div class="header-task">
                <a href="#" class="link link--block link--big"><?= $task->title ?></a>
                <p class="price price--task"><?= $task->budget ?> ₽</p>
            </div>
            <p class="info-text">
                <span class="current-time">
                    <?= Yii::$app->formatter->asRelativeTime($task->created_at) ?>
                </span>
            </p>
            <p class="task-text"><?= $task->description ?></p>
            <div class="footer-task">
                <?php if (isset($task->city)): ?>
                    <p class="info-text town-text"><?= $task->city->name ?></p>
                <?php endif; ?>
                <p class="info-text category-text"><?= $task->category->name ?></p>
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <form>
                <h4 class="head-card">Категории</h4>
                <div class="form-group">
                    <div>
                        <input type="checkbox" id="сourier-services" checked>
                        <label class="control-label" for="сourier-services">Курьерские услуги</label>
                        <input id="cargo-transportation" type="checkbox">
                        <label class="control-label" for="cargo-transportation">Грузоперевозки</label>
                        <input id="translations" type="checkbox">
                        <label class="control-label" for="translations">Переводы</label>
                    </div>
                </div>
                <h4 class="head-card">Дополнительно</h4>
                <div class="form-group">
                    <input id="without-performer" type="checkbox" checked>
                    <label class="control-label" for="without-performer">Без исполнителя</label>
                </div>
                <h4 class="head-card">Период</h4>
                <div class="form-group">
                    <label for="period-value"></label>
                    <select id="period-value">
                        <option>1 час</option>
                        <option>12 часов</option>
                        <option>24 часа</option>
                    </select>
                </div>
                <input type="button" class="button button--blue" value="Искать">
            </form>
        </div>
    </div>
</div>

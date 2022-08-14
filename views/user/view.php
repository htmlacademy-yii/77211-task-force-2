<?php

/**
 * @var yii\web\View $this
 * @var User $user
 * @var Review[] $reviews
 */

use app\models\Review;
use app\models\User;
use app\widgets\Stars;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <?php if ($user->avatarFile): ?>
                <?= Html::img($user->avatarFile->path, [
                    'class' => 'card-photo',
                    'alt' => 'Фото пользователя',
                    'width' => 191,
                    'height' => 190,
                ]) ?>
            <?php else: ?>
                <?= Html::img('https://via.placeholder.com/191x190.png/?text=no+photo', [
                    'class' => 'card-photo',
                    'alt' => 'Фото пользователя отсутсвует',
                    'width' => 191,
                    'height' => 190,
                ]) ?>
            <?php endif; ?>
            <div class="card-rate">
                <div class="stars-rating big">
                    <?= Stars::widget(['rating' => $user->rating]) ?>
                </div>
                <span class="current-rate"><?= $user->rating ?></span>
            </div>
        </div>
        <p class="user-description"><?= Html::encode($user->info) ?></p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <?php
            $categories = $user->categories;
            if (!empty($categories)): ?>
                <ul class="special-list">
                    <?php foreach ($categories as $category): ?>
                        <li class="special-item">
                            <a href="<?= Url::to(['tasks/index', 'categories[]' => $category->id]) ?>" class="link link--regular">
                                <?= $category->name ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div>Нет выбранных категорий</div>
            <?php endif; ?>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <span class="country-info">Россия</span>, <span class="town-info"><?= $user->city->name ?></span>, <span
                        class="age-info">
                    <?= \Yii::$app->i18n->messageFormatter->format(
                        '{n, plural, one{# год} few{# года} many{# лет} other{# года}}',
                        ['n' => $user->age],
                        \Yii::$app->language
                    ); ?>
                </span>
            </p>
        </div>
    </div>
    <?php if (!empty($reviews)): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($reviews as $review): ?>
            <div class="response-card">
                <?= Html::img($review->author->avatarFile->path, [
                    'class' => 'customer-photo',
                    'alt' => 'Фото заказчика',
                    'width' => 120,
                    'height' => 127,
                ]) ?>
                <div class="feedback-wrapper">
                    <p class="feedback"><?= Html::encode($review->comment) ?></p>
                    <p class="task">Задание «<a href="<?= Url::to(['tasks/view', 'id' => $review->task_id]) ?>"
                                                class="link link--small">
                            <?= Html::encode($review->task->title) ?>
                        </a>» выполнено
                    </p>
                </div>
                <div class="feedback-wrapper">
                    <div class="stars-rating small">
                        <?= Stars::widget(['rating' => $review->rate]) ?>
                    </div>
                    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($review->created_at) ?></span></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd>
                <?= \Yii::$app->i18n->messageFormatter->format(
                    '{n, plural, =0{# выполнено} one{# выполнен} few{# выполнено} many{# выполнено} other{# выполнено}}',
                    ['n' => $user->completedTasksCount],
                    \Yii::$app->language
                ); ?>,
                <?= \Yii::$app->i18n->messageFormatter->format(
                    '{n, plural, =0{# провалено} one{# провален} few{# провалено} many{# провалено} other{# провалено}}',
                    ['n' => $user->failed_tasks_count],
                    \Yii::$app->language
                ); ?>
            </dd>
            <dt>Место в рейтинге</dt>
            <dd><?= $user->placeInRating ?> место</dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($user->created_at) ?></dd>
            <dt>Статус</dt>
            <dd><?= $user->getUserStatusesList()[$user->status] ?></dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="tel:<?= Html::encode($user->phone) ?>" class="link link--block link--phone">
                    <?= Html::encode($user->phone) ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="mailto:<?= Html::encode($user->email) ?>" class="link link--block link--email">
                    <?= Html::encode($user->email) ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="https://t.me/<?= Html::encode($user->telegram) ?>" class="link link--block link--tg">
                    <?= Html::encode($user->telegram) ?>
                </a>
            </li>
        </ul>
    </div>
</div>

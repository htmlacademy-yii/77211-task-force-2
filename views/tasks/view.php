<?php

/**
 * @var yii\web\View $this
 * @var Task $task
 * @var string $taskStatusName
 * @var Response[] $responses
 */

use app\models\Response;
use app\models\Task;
use app\widgets\Stars;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
        <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <a href="#" class="button button--blue">Откликнуться на задание</a>
    <div class="task-map">
        <!-- TODO: Добавить вывод карты, города, адреса -->
        <img class="map" src="/img/map.png" width="724" height="348" alt="Новый арбат, 23, к. 1">
        <p class="map-address town">Москва</p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php if (!empty($responses)): ?>
        <?php foreach ($responses as $response): ?>
            <div class="response-card">
                <img class="customer-photo" src="<?= $response->executor->avatarFile->path ?>" width="146" height="156" alt="Фото заказчиков">
                <div class="feedback-wrapper">
                    <a href="<?= Url::to(['user/view', 'id' => $response->executor_id]) ?>" class="link link--block link--big">
                        <?= Html::encode($response->executor->name) ?>
                    </a>
                    <div class="response-wrapper">
                        <div class="stars-rating small">
                            <?= Stars::widget(['rating' => $response->executor->rating]) ?>
                        </div>
                        <p class="reviews">
                            <?= \Yii::$app->i18n->messageFormatter->format(
                                '{n, plural, =0{Нет отзывов} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}',
                                ['n' => count($response->executor->reviewsWhereUserIsReceiver)],
                                \Yii::$app->language
                            ); ?>
                        </p>
                    </div>
                    <p class="response-message"><?= Html::encode($response->comment) ?></p>
                </div>
                <div class="feedback-wrapper">
                    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($response->created_at) ?></span></p>
                    <p class="price price--small"><?= Html::encode($response->budget) ?> ₽</p>
                </div>
                <div class="button-popup">
                    <a href="#" class="button button--blue button--small">Принять</a>
                    <a href="#" class="button button--orange button--small">Отказать</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>У задания нет откликов.</p>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd>
                <a href="<?= Url::to(['tasks/index', 'categories[]' => $task->category->id]) ?>"><?= $task->category->name ?></a>
            </dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?= Yii::$app->formatter->asDatetime($task->deadline_at, 'php:j F, H:i') ?></dd>
            <dt>Статус</dt>
            <dd><?= $taskStatusName ?></dd>
        </dl>
    </div>
    <!-- TODO: Добавить вывод прикрепленных к заданию файлов -->
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                <p class="file-size">356 Кб</p>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">information.docx</a>
                <p class="file-size">12 Кб</p>
            </li>
        </ul>
    </div>
</div>

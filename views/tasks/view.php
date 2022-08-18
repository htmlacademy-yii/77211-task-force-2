<?php

/**
 * @var yii\web\View $this
 * @var Task $task
 * @var string $taskStatusName
 * @var Response[] $responses
 * @var File[] $files
 * @var CreateResponseForm $responseForm
 */

use app\models\CreateResponseForm;
use app\models\File;
use app\models\Response;
use app\models\Task;
use app\widgets\Stars;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
        <?php if (isset($task->budget)): ?>
            <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
        <?php endif; ?>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <?php if (!empty($actionsMarkup)): ?>
        <?php foreach ($actionsMarkup as $markup): ?>
            <?= $markup ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="task-map">
        <!-- TODO: Добавить вывод карты, города, адреса -->
        <img class="map" src="/img/map.png" width="724" height="348" alt="Новый арбат, 23, к. 1">
        <p class="map-address town">Москва</p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <?php if (!empty($responses)): ?>
        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($responses as $response): ?>
            <div class="response-card">
                <?php if (isset($response->executor->avatarFile)): ?>
                    <?= Html::img($response->executor->avatarFile->path, [
                        'class' => 'customer-photo',
                        'alt' => 'Фото заказчика',
                        'width' => 146,
                        'height' => 156,
                    ]) ?>
                <?php else: ?>
                    <?= Html::img('@web/img/avatars/1.png', [
                        'class' => 'customer-photo',
                        'alt' => 'Нет фото',
                        'width' => 146,
                        'height' => 156,
                    ]) ?>
                <?php endif; ?>
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
                    <?php if (Yii::$app->user->id === $task->customer_id && !$response->is_refused && $task->status === Task::STATUS_NEW): ?>
                        <a href="<?= Url::to(['response/accept', 'id' => $response->id]) ?>" class="button button--blue button--small">Принять</a>
                        <a href="<?= Url::to(['response/refuse', 'id' => $response->id]) ?>" class="button button--orange button--small">Отказать</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
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
            <?php if (isset($task->deadline_at)): ?>
                <dd><?= Yii::$app->formatter->asDate($task->deadline_at, 'php:j F') ?></dd>
            <?php else: ?>
                <dd>Бессрочно</dd>
            <?php endif; ?>
            <dt>Статус</dt>
            <dd><?= $taskStatusName ?></dd>
        </dl>
    </div>
    <?php if (!empty($files)): ?>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <?php foreach ($files as $file): ?>
            <li class="enumeration-item">
                <a href="<?= Url::to($file->path) ?>" class="link link--block link--clip"><?= basename($file->path) ?></a>
                <p class="file-size"><?= $file->getSize() ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => ['response/create'],
            ]) ?>
                <?= $form->field($responseForm, 'task_id', [
                    'template' => '{input}',
                ])->hiddenInput(['value' => $task->id]) ?>
                <?= $form->field($responseForm, 'comment')->textarea() ?>
                <?= $form->field($responseForm, 'budget') ?>

                <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <?php ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>

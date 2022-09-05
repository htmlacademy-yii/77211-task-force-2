<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\User;
use app\services\LayoutService;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);

$user = Yii::$app->user->identity;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php if (!str_contains(Yii::$app->request->url, 'registration')): ?>
<header class="page-header">
    <nav class="main-nav">
        <a href='<?= Url::to(['tasks/index']) ?>' class="header-logo">
            <?= Html::img('@web/img/logotype.png', [
                'alt' => 'taskforce',
                'width' => 227,
                'height' => 60
            ]) ?>
        </a>
        <div class="nav-wrapper">
            <?= Menu::widget([
                'items' => [
                    ['label' => 'Новое', 'url' => ['tasks/index']],
                    ['label' => 'Мои задания', 'url' => ['my-tasks/index'],],
                    [
                        'label' => 'Создать задание',
                        'url' => ['tasks/create'],
                        'visible' => $user->role === User::ROLE_CUSTOMER,
                    ],
                    [
                        'label' => 'Настройки',
                        'url' => ['profile/index'],
                        'active' => function ($item, $hasActiveChild, $isItemActive, $widget) {
                            return str_contains(Yii::$app->controller->route, 'profile');
                        }
                    ],
                ],
                'activeCssClass' => 'list-item--active',
                'options' => [
                    'class' => 'nav-list',
                ],
                'itemOptions' => [
                    'class' => 'list-item',
                ],
                'linkTemplate' => '<a class="link link--nav" href="{url}">{label}</a>',
            ]) ?>
        </div>
    </nav>
    <div class="user-block">
        <a href="<?= Url::to(['user/view', 'id' => $user->id]) ?>">
            <?php if (!is_null($user->avatar_file_id)): ?>
                <?= Html::img($user->avatarFile->path, [
                    'class' => 'user-photo',
                    'alt' => 'Аватар',
                    'width' => 55,
                    'height' => 55,
                ]) ?>
            <?php else: ?>
                <?= Html::img('@web/img/avatars/1.png', [
                    'class' => 'user-photo',
                    'alt' => 'Аватар',
                    'width' => 55,
                    'height' => 55,
                ]) ?>
            <?php endif; ?>
        </a>
        <div class="user-menu">
            <p class="user-name"><?= Html::encode($user->name) ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="<?= Url::to(['profile/index']) ?>" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Url::to(['user/logout']) ?>" class="link">Выход из системы</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>
<main class="main-content container <?= LayoutService::addClassToMainSection(Yii::$app->controller->route) ?>">
    <?= $content ?>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

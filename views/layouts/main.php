<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
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
            <ul class="nav-list">
                <li class="list-item list-item--active">
                    <a class="link link--nav" >Новое</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav" >Мои задания</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav" >Создать задание</a>
                </li>
                <li class="list-item">
                    <a href="#" class="link link--nav" >Настройки</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="user-block">
        <a href="<?= Url::to(['user/view', 'id' => Yii::$app->user->id]) ?>">
            <img class="user-photo" src="/img/man-glasses.png" width="55" height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name"><?= Yii::$app->user->identity->name ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="#" class="link">Настройки</a>
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
<main class="main-content container">
    <?= $content ?>
</main>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

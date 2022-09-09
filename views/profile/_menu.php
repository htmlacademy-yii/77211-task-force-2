<?php

use yii\widgets\Menu;

?>
<?= Menu::widget([
    'items' => [
        ['label' => 'Мой профиль', 'url' => ['profile/index']],
        ['label' => 'Безопасность','url' => ['profile/security']],
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

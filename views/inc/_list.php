<?php

/**
 * @var ActiveDataProvider $tasksDataProvider
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

?>
<?= ListView::widget([
    'dataProvider' => $tasksDataProvider,
    'itemView' => '_task',
    'itemOptions' => [
        'tag' => false
    ],
    'pager' => [
        'hideOnSinglePage' => true,
        'options' => [
            'class' => 'pagination-list'
        ],
        'activePageCssClass' => 'pagination-item--active',
        'linkContainerOptions' => [
            'class' => 'pagination-item'
        ],
        'linkOptions' => [
            'class' => 'link link--page'
        ],
        'nextPageCssClass' => 'mark',
        'prevPageCssClass' => 'mark',
        'nextPageLabel' => '',
        'prevPageLabel' => '',
        'disabledPageCssClass' => ''
    ],
    'summary' => '',
    'separator' => '',
    'id' => false,
    'options' => [
        'tag' => false,
    ]
]); ?>
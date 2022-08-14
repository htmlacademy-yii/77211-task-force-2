<?php

/**
 * @var yii\web\View $this
 * @var ActiveDataProvider $tasksDataProvider
 * @var TasksFilterForm $filterForm
 * @var array $categoriesList
 * @var ActiveForm $form
 */

use app\models\TasksFilterForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

?>

<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
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
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['tasks/index'],
                'id' => false,
            ]) ?>

            <h4 class="head-card">Категории</h4>

            <?= $form->field($filterForm, 'categories', [
                'template' => '{label}{input}',
            ])
                ->checkboxList($categoriesList, [
                    'tag' => 'div',
                    'class' => 'checkbox-wrapper',
                    'id' => false,
                    'itemOptions' => [
                        'labelOptions' => [
                            'class' => 'control-label',
                        ]
                    ],
                    'unselect' => null,
                ])
                ->label(false) ?>

            <h4 class="head-card">Дополнительно</h4>

            <?= $form->field($filterForm, 'remote', ['template' => '{input}{label}'])
                ->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label',
                    ],
                    'id' => false,
                    'uncheck' => null,
                ]) ?>

            <?= $form->field($filterForm, 'withoutResponse', ['template' => '{input}{label}'])
                ->checkbox([
                    'labelOptions' => [
                        'class' => 'control-label',
                    ],
                    'id' => false,
                    'uncheck' => null,
                ]) ?>

            <h4 class="head-card">Период</h4>

            <?= $form->field($filterForm, 'period', ['template' => '{input}'])
                ->dropDownList([
                    '1' => '1 час',
                    '12' => '12 часов',
                    '24' => '24 часа',
                ],
                [
                    'prompt' => [
                        'text' => 'Выбери период',
                        'options' => [
                            'value' => '0',
                        ]
                    ],
                    'id' => false,
                ])
                ->label(false) ?>

            <?= Html::submitInput('Искать', ['class' => 'button button--blue']) ?>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

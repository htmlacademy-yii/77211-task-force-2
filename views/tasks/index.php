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
    <?= $this->render('//inc/_list', ['tasksDataProvider' => $tasksDataProvider]) ?>
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
                    'item' => function ($index, $label, $name, $checked, $value) use ($filterForm) {
                        $checked = in_array($value, $filterForm->categories) ? 'checked' : '';
                        $inputTag = "<input type='checkbox' name='$name' value='$value' $checked>";

                        return "<label class='control-label'>{$inputTag}{$label}</label>";
                    },
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

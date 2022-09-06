<?php

/**
 * @var yii\web\View $this
 * @var CreateTaskForm $createTaskForm
 * @var Category[] $categoriesList
 * @var ActiveForm $form
 * @var array $userLocalityData
 */

use app\assets\AutocompleteAsset;
use app\models\Category;
use app\models\CreateTaskForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

AutocompleteAsset::register($this);
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => [
                'tag' => 'span',
                'class' => 'help-block'
            ],
        ],
    ]); ?>

    <h3 class="head-main head-main">Публикация нового задания</h3>

    <?= $form->field($createTaskForm, 'title') ?>
    <?= $form->field($createTaskForm, 'description')
        ->textarea() ?>
    <?= $form->field($createTaskForm, 'category_id')
        ->dropDownList($categoriesList) ?>

    <?= $form->field($createTaskForm, 'location', [
            'enableAjaxValidation' => true,
        ])->textInput([
            'id' => 'autoComplete',
            'class' => 'location-icon',
            'value' => $userLocalityData['location']
        ])->hint('Укажите адрес из вашего города либо оставьте поле пустым') ?>

    <?= $form->field($createTaskForm, 'city', [
            'template' => '{input}',
            'enableAjaxValidation' => true,
            'options' => [
                'tag' => false
            ],
        ])->hiddenInput(['value' => $userLocalityData['city']]) ?>

    <?= $form->field($createTaskForm, 'address', [
        'template' => '{input}',
        'options' => [
            'tag' => false
        ],
    ])->hiddenInput(['value' => $userLocalityData['address']])?>

    <?= $form->field($createTaskForm, 'lat', [
        'template' => '{input}',
        'options' => [
            'tag' => false
        ]
    ])->hiddenInput(['value' => $userLocalityData['coordinates']['lat']]) ?>

    <?= $form->field($createTaskForm, 'long', [
        'template' => '{input}',
        'options' => [
            'tag' => false
        ],
    ])->hiddenInput(['value' => $userLocalityData['coordinates']['long']]) ?>

    <div class="half-wrapper">
        <?= $form->field($createTaskForm, 'budget')
            ->textInput(['class' => 'budget-icon']) ?>
        <?= $form->field($createTaskForm, 'deadline_at', ['enableAjaxValidation' => true])
            ->input('date') ?>
    </div>

    <p class="form-label">Файлы</p>
    <?= $form->field($createTaskForm, 'files[]', ['template' => '{input}'])
        ->fileInput(['multiple' => true, 'class' => 'new-file'])
        ->label(false) ?>

    <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end() ?>
</div>

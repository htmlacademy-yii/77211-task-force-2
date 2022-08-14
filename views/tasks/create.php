<?php

/**
 * @var yii\web\View $this
 * @var CreateTaskForm $createTaskForm
 * @var Category[] $categoriesList
 * @var ActiveForm $form
 */

use app\models\Category;
use app\models\CreateTaskForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
    <!-- TODO: Добавить поле локация -->
<!--    <div class="form-group">-->
<!--        <label class="control-label" for="location">Локация</label>-->
<!--        <input class="location-icon" id="location" type="text">-->
<!--        <span class="help-block">Error description is here</span>-->
<!--    </div>-->
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

<?php

/**
 * @var yii\web\View $this
 * @var RegistrationForm $regForm
 * @var City[] $citiesList
 * @var ActiveForm $form
 */

use app\models\City;
use app\models\RegistrationForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['registration/store']),
            'fieldConfig' => [
                'errorOptions' => [
                    'tag' => 'span',
                    'class' => 'help-block'
                ],
            ],
        ]); ?>

        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <?= $form->field($regForm, 'name') ?>
        <div class="half-wrapper">
            <?= $form->field($regForm, 'email') ?>
            <?= $form->field($regForm, 'city_id')->dropDownList($citiesList) ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($regForm, 'password')->passwordInput() ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($regForm, 'password_repeat')->passwordInput() ?>
        </div>
        <?= $form->field($regForm, 'role', [
            'template' => '{label}{input}',
        ])->checkbox([
            'labelOptions' => [
                'class' => 'control-label checkbox-label'
            ],
        ]) ?>
        <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>




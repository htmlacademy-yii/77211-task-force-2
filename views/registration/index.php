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
use yii\widgets\ActiveForm;

?>

<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin(); ?>

        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <?= $form->field($regForm, 'name') ?>
        <div class="half-wrapper">
            <?= $form->field($regForm, 'email') ?>
            <?= $form->field($regForm, 'city_id')->dropDownList($citiesList) ?>
        </div>
        <?= $form->field($regForm, 'password')->passwordInput() ?>
        <?= $form->field($regForm, 'password_repeat')->passwordInput() ?>
        <?= $form->field($regForm, 'role', ['template' => '{input}{label}'])
            ->checkbox(enclosedByLabel: false) ?>
        <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>




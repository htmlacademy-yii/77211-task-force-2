<?php

/**
 * @var yii\web\View $this
 * @var SecurityForm $securityForm
 * @var ActiveForm $form
 * @var User $user
 * @var int $isUserShowContacts
 */

use app\models\SecurityForm;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?= $this->render('_menu') ?>
</div>
<div class="my-profile-form">
    <h3 class="head-main head-regular">Безопасность</h3>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => [
                'tag' => 'span',
                'class' => 'help-block'
            ],
        ],
        'enableClientValidation' => false,
    ]) ?>

    <?= $form->field($securityForm, 'oldPassword', ['enableAjaxValidation' => true])->passwordInput() ?>
    <?= $form->field($securityForm, 'password', ['enableAjaxValidation' => true])->passwordInput() ?>
    <?= $form->field($securityForm, 'password_repeat',['enableAjaxValidation' => true])->passwordInput() ?>

    <?php if ($user->role === User::ROLE_EXECUTOR): ?>
    <?= $form->field($securityForm, 'showOnlyCustomer', [
        'template' => '{label}{input}',
    ])->checkbox([
        'labelOptions' => [
            'class' => 'control-label checkbox-label'
        ],
        'checked' => (bool) $isUserShowContacts
    ]) ?>
    <?php endif; ?>

    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end() ?>
</div>


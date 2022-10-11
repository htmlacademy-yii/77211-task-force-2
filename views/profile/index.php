<?php

/**
 * @var yii\web\View $this
 * @var ProfileForm $profileForm
 * @var ActiveForm $form
 * @var User $user
 * @var array $categoriesList
 * @var array $userCategoriesList
 */

use app\models\ProfileForm;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?= $this->render('_menu') ?>
</div>
<div class="my-profile-form">
    <h3 class="head-main head-regular">Мой профиль</h3>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'errorOptions' => [
                'tag' => 'span',
                'class' => 'help-block'
            ],
        ],
    ]) ?>
        <div class="photo-editing">
            <div>
                <p class="form-label">Аватар</p>
                <?php if (!is_null($user->avatar_file_id)): ?>
                    <?= Html::img($user->avatarFile->path, [
                        'class' => 'avatar-preview',
                        'alt' => 'Аватар',
                        'width' => 83,
                        'height' => 83,
                    ]) ?>
                <?php else: ?>
                    <?= Html::img('@web/img/avatars/1.png', [
                        'class' => 'avatar-preview',
                        'alt' => 'Аватар',
                        'width' => 83,
                        'height' => 83,
                    ]) ?>
                <?php endif; ?>
            </div>
            <?= $form->field($profileForm, 'avatar', [
                'template' => '{input}{label}{error}',
            ])->fileInput([
                'hidden' => true,
                'id' => 'button-input',
            ])->label('Сменить аватар', ['class' => 'button button--black']) ?>
        </div>
        <?= $form->field($profileForm, 'name')->textInput(['value' => $user->name])?>
        <div class="half-wrapper">
            <?= $form->field($profileForm, 'email', ['enableAjaxValidation' => true])
                ->textInput(['type' => 'email', 'value' => $user->email]) ?>
            <?= $form->field($profileForm, 'birthdate')
                ->textInput(['type' => 'date', 'value' => $user->birthdate ?? '']) ?>
        </div>
        <div class="half-wrapper">
            <?= $form->field($profileForm, 'phone')
                ->textInput(['type' => 'tel','value' => $user->phone ?? '']) ?>
            <?= $form->field($profileForm, 'telegram')
                ->textInput(['value' => $user->telegram ?? '']) ?>
        </div>
        <?= $form->field($profileForm, 'info')->textarea(['value' => $user->info ?? '']) ?>

        <?php if ($user->role === User::ROLE_EXECUTOR): ?>
            <?= $form->field($profileForm, 'categories[]', [
                'template' => '{label}{input}',
            ])->checkboxList($categoriesList, [
                    'tag' => 'div',
                    'class' => 'checkbox-profile',
                    'item' => function ($index, $label, $name, $checked, $value) use ($userCategoriesList) {
                        $checked = in_array($value, $userCategoriesList) ? 'checked' : '';
                        $inputTag = "<input type='checkbox' name='$name' value='$value' $checked>";

                        return "<label class='control-label'>{$inputTag} {$label}</label>";
                    },
                    'itemOptions' => [
                        'labelOptions' => [
                            'class' => 'control-label',
                        ]
                    ],
                    'unselect' => null,
                ]) ?>
        <?php endif; ?>

        <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>
    <?php ActiveForm::end() ?>
</div>


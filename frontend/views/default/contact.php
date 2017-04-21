<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Обратная связь | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Обратная связь';
$this->params['page-header'] = 'Обратная связь';
?>
<div class="default-contact">
    <p>
        Вы можете обратиться к администрации сайта через форму на этой странице. Укажите свои контактные данные
        и нажмите кнопку &laquo;Отправить&raquo;. Все поля обязательны для заполнения.
    </p>
    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'placeholder' => 'Введите Ваше имя']) ?>

            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Введите свой E-mail']) ?>

            <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Введите тему обращения']) ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => 'default/captcha',
                'template' => '<div class="row"><div class="col-xs-5">{image}</div><div class="col-xs-5">{input}</div></div>',
            ])->hint('Нажмите на картинку, чтобы обновить.') ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'body')->textArea(['rows' => 6, 'placeholder' => 'Введите текст обращения']) ?>

        </div>
    <?php ActiveForm::end(); ?>

    </div>
</div>

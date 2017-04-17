<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model frontend\models\CalculatorForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Калькулятор экологического сбора | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Калькулятор';
$this->params['page-header'] = 'Калькулятор стоимости обслуживания';
?>
<div class="calculator">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'form_company')->textInput(['placeholder' => 'Название компании']) ?>

            <?= $form->field($model, 'form_username')->textInput(['placeholder' => 'Ваше имя']) ?>

            <?= $form->field($model, 'form_phone', ['template' => '{label}<div class="input-group"><span class="input-group-addon">+7</span>{input}</div>{error}'])->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '(999) 999-99-99',
            ])->textInput(['maxlength' => true, 'placeholder' => 'Введите номер телефона']) ?>

            <?= $form->field($model, 'form_email')->textInput(['placeholder' => 'Введите E-mail']) ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => 'default/captcha',
                'template' => '<div class="row"><div class="col-xs-5">{image}</div><div class="col-xs-5">{input}</div></div>',
            ])->hint('Нажмите на картинку, чтобы обновить.') ?>

            <div class="form-group">
                <?= Html::submitButton('Заказать услугу', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>

            </div>
        </div>
        <div class="col-md-9">
            <?php $count = count($model->tp); ?>
            <div id="table-part" class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= Html::a('<i class="fa fa-plus" aria-hidden="true"></i> Добавить строку', '#', ['id' => 'btn-add-row', 'class' => 'btn btn-default', 'data-count' => $count]) ?>

                        </div>
                        <div class="col-md-7">
                            <h4 class="pull-right">Сумма: <span id="total_amount">0 р</span></h4>
                        </div>
                    </div>
                    <?= $form->field($model, 'tp_errors', ['template' => "{error}"])->staticControl() ?>

                    <?php foreach ($model->tp as $index => $tpr): ?>
                    <?= $this->render('_calculator_row', [
                        'form' => $form,
                        'calculator' => $model,
                        'model' => $tpr,
                        'counter' => $index,
                    ]) ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$tp_formname = $model->formName() . 'Tp';
$tp_formname_lowcase = strtolower($tp_formname);

$url_add_row = Url::to(['calculator-render-row']);
$this->registerJs(<<<JS
// Обработчик щелчка по кнопке Добавить строку.
//
function btnAddRowOnClick() {
    counter = parseInt($(this).attr("data-count"));
    next_counter = counter+1;
    $.get("$url_add_row?counter=" + counter, function(data) {
        // вставляем после последнего
        $("div[id ^= 'dtp-row']").last().after(data);
    });

    // наращиваем количество добавленных строк
    $(this).attr("data-count", next_counter);

    return false;
} // btnAddRowOnClick()

// Обработчик щелчка по кнопке Удалить строку.
//
function btnDeleteRowClick() {
    counter = $(this).attr("data-counter");

    if (counter != undefined) {
        $("#dtp-row-" + counter).remove();
        CalculateTotalAmount();
    }

    return false;
} // btnDeleteRowClick()

// Функция-обработчик изменения значения в поле Вес.
//
function WeightOnChange() {
    CalculateAmount($(this).attr("data-counter"));
} // WeightOnChange()

$(document).on("click", "#btn-add-row", btnAddRowOnClick);
$(document).on("click", "a[id ^= 'btn-delete-row']", btnDeleteRowClick);
$(document).on("change", "input[id ^= '$tp_formname_lowcase-weight']", WeightOnChange);
JS
, yii\web\View::POS_READY);

$this->registerJs(<<<JS

// Функция выполняет расчет суммы экологического сбора.
//
function CalculateAmount(row) {
    // номер текущей строки табличной части
    if (row == undefined) row = $(this).attr("data-counter");
    // вес, введенный пользователем, переводим в тонны
    weight = parseFloat($("#$tp_formname_lowcase-weight-" + row).val());
    // норматив
    ratio = parseFloat($("#$tp_formname_lowcase-hs_ratio-" + row).val());
    // ставка за тонну
    rate = parseFloat($("#$tp_formname_lowcase-hs_rate-" + row).val());
    if (!isNaN(weight) && !isNaN(ratio) && !isNaN(rate)) {
        amount = weight * ratio * rate;
        $("#$tp_formname_lowcase-amount-" + row).val(amount.toFixed(2));
    }

    CalculateTotalAmount();
} // TpHsOnChange()

function CalculateTotalAmount() {
    var total_amount = 0;
    $("input[id ^= '$tp_formname_lowcase-amount']").each(function(index, element) {
        amount = parseFloat($(this).val());
        if (!isNaN(amount)) total_amount += amount;
    });
    
    $("#total_amount").text(total_amount.toFixed(2) + " р");
} // CalculateTotalAmount()

JS
, yii\web\View::POS_BEGIN);
?>

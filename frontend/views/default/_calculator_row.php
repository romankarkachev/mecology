<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $form \yii\bootstrap\ActiveForm */
/* @var $calculator frontend\models\CalculatorForm */
/* @var $model common\models\OrdersTp */
/* @var $counter integer */

$delete_options = ['id' => 'btn-delete-row-'.$counter, 'data-counter' => $counter, 'title' => 'Удалить эту строку'];

$formname = $calculator->formName();
$formname_lowcase = strtolower($formname);
$tp_formname_lowcase = $formname_lowcase . 'tp';
?>

<div id="dtp-row-<?= $counter ?>">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group field-<?= $tp_formname_lowcase ?>-hs_id required">
                <label class="control-label" for="<?= $tp_formname_lowcase ?>-hs_id"><?= $model->attributeLabels()['hs_id'] ?></label>
                <?= Select2::widget([
                    'model' => $model,
                    'name' => $formname . '[tp]['.$counter.'][hs_id]',
                    'value' => $model->hs_id,
                    'initValueText' => $model->getHsName(),
                    'theme' => Select2::THEME_KRAJEE,
                    'size' => Select2::SMALL,
                    'language' => 'ru',
                    'options' => [
                        'id' => $tp_formname_lowcase . '-hs_id-'.$counter,
                        'data-counter' => $counter,
                        'placeholder' => 'Введите код или наименование'
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 1,
                        'language' => 'ru',
                        'ajax' => [
                            'url' => Url::to(['fthcdc-list']),
                            'delay' => 250,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {
    q:params.term, counter: $(this).attr("data-counter"), year: parseInt($("#calculatorform-year").val())
    };
}')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(result) { return result.text; }'),
                        'templateSelection' => new JsExpression('function (result) {
if (!result.id) {return result.text;}
if (result.hs_ratio != "") $("#' . $tp_formname_lowcase . '-hs_ratio-" + result.counter).val(result.hs_ratio);
if (result.hs_ratio != "") $("#' . $tp_formname_lowcase . '-hs_ratio-disp-" + result.counter).val(result.hs_ratio * 100);
if (result.hs_rate != "") $("#' . $tp_formname_lowcase . '-hs_rate-" + result.counter).val(result.hs_rate);

return result.text;
}'),
                    ],
                    'pluginEvents' => [
                        'select2:select' => new JsExpression('function() {
    CalculateAmount($(this).attr("data-counter"));
}'),
                    ],
                ]) ?>

                <p class="help-block help-block-error"></p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group field-<?= $tp_formname_lowcase ?>-weight">
                <label class="control-label" for="<?= $tp_formname_lowcase ?>-weight"><?= $model->attributeLabels()['weight'] ?></label>
                <?= MaskMoney::widget([
                    'name' => $formname . '[tp]['.$counter.'][weight]',
                    'value' => $model->weight == null ? 0 : $model->weight,
                    'id' => $tp_formname_lowcase . '-weight-'.$counter,
                    'pluginOptions' => [
                        'precision' => 0,
                    ],
                    'options' => [
                        'class' => 'input-sm',
                        'data-counter' => $counter,
                        'title' => 'Введите вес',
                        'placeholder' => 'Введите вес',
                    ],
                ]); ?>

                <p class="help-block help-block-error"></p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group field-<?= $tp_formname_lowcase ?>-hs_ratio">
                <?= Html::input('hidden', $formname . '[tp]['.$counter.'][hs_ratio]', $model->hs_ratio, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => $tp_formname_lowcase . '-hs_ratio-'.$counter, 'aria-describedby' => 'ig-addon-hs_ratio']) ?>
                <label class="control-label" for="<?= $tp_formname_lowcase ?>-hs_ratio">Норматив</label>
                <div class="input-group">
                    <?= Html::input('text', 'hs_ratio', ($model->hs_ratio * 100), ['class' => 'form-control input-sm', 'readonly' => true, 'id' => $tp_formname_lowcase . '-hs_ratio-disp-'.$counter]) ?>

                    <span class="input-group-addon" id="ig-addon-hs_ratio">%</span>
                </div>
                <p class="help-block help-block-error"></p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group field-<?= $tp_formname_lowcase ?>-hs_rate">
                <label class="control-label" for="<?= $tp_formname_lowcase ?>-hs_rate">Ставка за тонну</label>
                <div class="input-group">
                    <?= Html::input('text', $formname . '[tp]['.$counter.'][hs_rate]', $model->hs_id != null ? $model->hs->hs_rate : null, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => $tp_formname_lowcase . '-hs_rate-'.$counter, 'aria-describedby' => 'ig-addon-hs_rate']) ?>

                    <span class="input-group-addon" id="ig-addon-hs_rate"><i class="fa fa-rub" aria-hidden="true"></i></span>
                </div>
                <p class="help-block help-block-error"></p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group field-<?= $tp_formname_lowcase ?>-amount">
                <label class="control-label" for="<?= $tp_formname_lowcase ?>-amount"><?= $model->attributeLabels()['amount'] ?> экосбора</label>
                <div class="input-group">
                    <?= Html::input('text', $formname . '[tp]['.$counter.'][amount]', $model->amount, ['class' => 'form-control input-sm', 'readonly' => true, 'id' => $tp_formname_lowcase . '-amount-'.$counter, 'aria-describedby' => 'ig-addon-amount']) ?>

                    <span class="input-group-addon" id="ig-addon-amount"><i class="fa fa-rub" aria-hidden="true"></i></span>
                </div>
                <p class="help-block help-block-error"></p>
            </div>
        </div>
        <?php if ($counter > 0): ?>
            <div class="col-md-1">
                <label class="control-label" for="<?= 'btn-delete-row-'.$counter ?>">&nbsp;</label>
                <div class="form-group cate">
                    <li style="line-height: 1;"><?= Html::a('<span style="color:#fff;background-color:#d9534f;border-color:#d43f3a;margin-top: 3px;"><i class="fa fa-minus" aria-hidden="true"></i></span>', '#', $delete_options) ?></li>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

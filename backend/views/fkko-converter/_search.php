<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FkkoConverterSearch */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $searchApplied bool */
?>

<div class="fkko-search">
    <?php $form = ActiveForm::begin([
        'action' => ['/fkko-converter'],
        'method' => 'get',
        'options' => [
            'id' => 'frmSearch',
            'class' => $searchApplied === true ? 'collapse in' : 'collapse',
            'aria-expanded' => $searchApplied === true ? 'true' : 'false'
        ],
    ]); ?>

    <div class="card">
        <div class="card-header card-header-info card-header-inverse"><i class="fa fa-filter"></i> Форма отбора</div>
        <div class="card-block">
            <?= $form->field($model, 'searchEntire')->textInput(['placeholder' => 'Поиск по всем кодам и наименованиям']) ?>

            <div class="form-group">
                <?= Html::submitButton('Выполнить', ['class' => 'btn btn-info']) ?>

                <?= Html::a('Отключить отбор', ['/fkko-converter'], ['class' => 'btn btn-secondary']) ?>

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

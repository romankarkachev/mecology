<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\FkkoConverter */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="fkko-form">
    <div class="card">
        <div class="card-block">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'fkko_code')->textInput(['maxlength' => true, 'placeholder' => 'Введите код']) ?>

                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'fkko2002_code')->textInput(['maxlength' => true, 'placeholder' => 'Введите код']) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'fkko_name')->textarea(['rows' => 2, 'placeholder' => 'Введите наименование']) ?>

                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'fkko2002_name')->textarea(['rows' => 2, 'placeholder' => 'Введите наименование']) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'fkko_date')->widget(DateControl::className(), [
                                'value' => $model->fkko_date,
                                'type' => DateControl::FORMAT_DATE,
                                'language' => 'ru',
                                'displayFormat' => 'php:d.m.Y',
                                'saveFormat' => 'php:Y-m-d',
                                'widgetOptions' => [
                                    'options' => ['placeholder' => 'выберите'],
                                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                    'layout' => '<div class="input-group">{input}{picker}{remove}</div>',
                                    'pickerButton' => '<span class="input-group-addon kv-date-calendar" title="Выбрать дату"><i class="fa fa-calendar" aria-hidden="true"></i></span>',
                                    'removeButton' => '<span class="input-group-addon kv-date-remove" title="Очистить поле"><i class="fa fa-times" aria-hidden="true"></i></span>',
                                    'pluginOptions' => [
                                        'todayHighlight' => true,
                                        'weekStart' => 1,
                                        'autoclose' => true,
                                    ],
                                ],
                            ]) ?>

                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'fkko_dc')->textInput(['maxlength' => true, 'placeholder' => 'Класс опасности']) ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Коды ФККО', ['/fkko-converter'], ['class' => 'btn btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

                <?php if ($model->isNewRecord): ?>
                    <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>

                <?php else: ?>
                    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>

                <?php endif; ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

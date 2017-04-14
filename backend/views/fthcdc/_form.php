<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\Fthcdc */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="fthcdc-form">
    <div class="card">
        <div class="card-block">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'hs_code')->textInput(['maxlength' => true, 'placeholder' => 'Введите код']) ?>

                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'hs_group')->textInput(['placeholder' => 'Группа']) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'hs_ratio')->widget(MaskMoney::className(), ['options' => ['title' => 'Введите норматив', 'placeholder' => 'Введите норматив']]) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'hs_rate', [
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><i class="fa fa-rub" aria-hidden="true"></i></span></div>{error}'
                    ])->widget(MaskMoney::className(), [
                        'pluginOptions' => [
                            'allowNegative' => false,
                        ],
                        'options' => [
                            'title' => 'Введите ставку',
                            'placeholder' => 'Введите ставку'
                        ]
                    ]) ?>

                </div>
            </div>
            <?= $form->field($model, 'hs_name')->textarea(['rows' => 3, 'placeholder' => 'Введите наименование']) ?>

            <div class="form-group">
                <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Коды ТН ВЭД', ['/fthcdc'], ['class' => 'btn btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

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

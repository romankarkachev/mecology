<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $attribute string */
?>

<div class="form-group field-<?= $model->formName() ?>-<?= $attribute ?>">
    <label class="control-label"><?= $model->attributeLabels()[$attribute] ?></label>
    <?= Html::input('text', $model->formName() . '[' . $attribute . ']', $model->$attribute, ['class' => 'form-control', 'maxlength' => true, 'readonly' => true]) ?>

</div>

<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $dttp yii\data\ActiveDataProvider */

$order = '№ ' . $model->id . ' от ' . Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y');

$this->title = $order . HtmlPurifier::process(' &mdash; Заявки | ') . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['/orders']];
$this->params['breadcrumbs'][] = $order;

$fields = [
    'form_company',
    'form_username',
    'form_phone',
    'form_email',
]
?>
<div class="orders-update">
    <?= $this->render('_form', ['fields' => $fields, 'model' => $model, 'dttp' => $dttp]) ?>

</div>

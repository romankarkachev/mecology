<?php

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Новая заявка | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['/orders']];
$this->params['breadcrumbs'][] = 'Новая *';
?>
<div class="orders-create">
    <?= $this->render('_form', ['model' => $model]) ?>

</div>

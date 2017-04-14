<?php

/* @var $this yii\web\View */
/* @var $model common\models\Fthcdc */

$this->title = 'Новый код ТН ВЭД | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Коды ТН ВЭД', 'url' => ['/fthcdc']];
$this->params['breadcrumbs'][] = 'Новый *';
?>
<div class="fthcdc-create">
    <?= $this->render('_form', ['model' => $model]) ?>

</div>

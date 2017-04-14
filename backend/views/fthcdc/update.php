<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model common\models\Fthcdc */

$this->title = $model->hs_code . HtmlPurifier::process(' &mdash; Коды ТН ВЭД | ') . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Коды ТН ВЭД', 'url' => ['/fthcdc']];
$this->params['breadcrumbs'][] = $model->hs_code;
?>
<div class="fthcdc-update">
    <?= $this->render('_form', ['model' => $model]) ?>

</div>

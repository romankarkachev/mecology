<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model common\models\FkkoConverter */

$this->title = $model->fkko_name . HtmlPurifier::process(' &mdash; Коды ФККО | ') . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Коды ФККО для конвертера', 'url' => ['/fkko-converter']];
$this->params['breadcrumbs'][] = $model->fkko_name;
?>
<div class="fkko-update">
    <?= $this->render('_form', ['model' => $model]) ?>

</div>

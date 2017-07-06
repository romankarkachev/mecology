<?php

/* @var $this yii\web\View */
/* @var $model common\models\FkkoConverter */

$this->title = 'Новый код ФККО | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Коды ФККО для конвертера', 'url' => ['/fkko-converter']];
$this->params['breadcrumbs'][] = 'Новый *';
?>
<div class="fkko-create">
    <?= $this->render('_form', ['model' => $model]) ?>

</div>

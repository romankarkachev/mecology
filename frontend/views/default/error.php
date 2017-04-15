<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="default-error">
    <h1>Ошибка <?= $exception->statusCode ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p>
        При выполнении Вашего запроса произошла ошибка, которую Вы можете видеть выше.
    </p>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $order common\models\Orders */

$link = Yii::$app->urlManager->createAbsoluteUrl(['/manage/orders']);
?>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="content-block">
            <strong>Уважаемый менеджер!</strong>
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Создана <strong>1</strong> новая заявка.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            <?= Html::a('Открыть заявки', $link, ['class' => 'btn-primary']) ?>

        </td>
    </tr>
    <tr>
        <td class="content-block">
            <p>Отправитель: <?= $order->form_username ?>.</p>
            <p>Компания: <?= $order->form_company ?></p>
            <p>Телефон: <?= $order->form_phone ?></p>
            <p>E-mail: <?= $order->form_email ?></p>
        </td>
    </tr>
</table>

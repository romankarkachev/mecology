<?php
/* @var $this yii\web\View */
/* @var $name string имя пользователя */
/* @var $email string ящик для обратной связи */
/* @var $subject string тема обращения */
/* @var $body string текст обращения посетителя */
?>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="content-block">
            <strong>Уважаемый администратор!</strong>
        </td>
    </tr>
    <tr>
        <td class="content-block">
            К Вам обращается посетитель сайта <strong><?= Yii::$app->name ?></strong>.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            <p><strong>Имя</strong>: <?= $name ?>.</p>
            <p><strong>E-mail</strong>: <?= $email ?>.</p>
            <p><strong>Тема</strong>: <?= $subject ?>.</p>
            <p><strong>Текст обращения</strong>: <?= nl2br($body) ?></p>
        </td>
    </tr>
</table>

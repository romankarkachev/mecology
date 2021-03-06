<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\FthcdcImport;

/* @var $this yii\web\View */
/* @var $model backend\models\FthcdcImport */

$this->title = 'Импорт кодов ТН ВЭД | '.Yii::$app->name;
$this->blocks['content-header'] = 'Импорт кодов ТН ВЭД';
$this->params['breadcrumbs'][] = ['label' => 'Коды ТН ВЭД', 'url' => ['/fthcdc']];
$this->params['breadcrumbs'][] = 'Импорт';
?>
<div class="fthcdc-import">
    <div class="card card-accent-primary">
        <div class="card-header">Примечание</div>
        <div class="card-block">
            <p>Внимание! В файле импорта первая строка должна содержать заголовок. Наименования полей стандартизированы, они указаны в скобках, только латинские символы. Обратите внимание, что сопоставление объектов осуществляется с учетом регистра: &laquo;Директор&raquo; и &laquo;директор&raquo; - разные данные.</p>
            <p><strong>Обратите также внимание</strong>, что файл импорта, который Вы предоставляете, должен содержать только один лист в книге. В противном случае импорт не может быть выполнен.</p>
            <p>Файл импорта должен содержать следующие поля (порядок может быть любой): <strong>Код ТН ВЭД *</strong> (code), <strong>Норматив *</strong> (ratio), <strong>Ставка за тонну *</strong> (rate).</p>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <?php $form = ActiveForm::begin() ?>

            <div class="row">
                <div class="col-md-1">
                    <?= $form->field($model, 'year')->textInput() ?>

                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'importFile')->fileInput()->label(false) ?>

                </div>
            </div>
            <div class="form-group">
                <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Коды ТН ВЭД', ['/fthcdc'], ['class' => 'btn btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

                <?= Html::submitButton('<i class="fa fa-cloud-upload" aria-hidden="true"></i> Выполнить', ['class' => 'btn btn-success btn-lg']) ?>

            </div>
            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>
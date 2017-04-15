<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\Converter;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\Converter */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $columns array */

$this->title = 'Конвертер ФККО | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Конвертер';
$this->params['page-header'] = 'Конвертер кодов ФККО';
?>
<div class="converter">
    <?php $form = ActiveForm::begin([
        'action' => ['/converter'],
        'method' => 'get',
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Форма отбора</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($searchModel, 'searchMode')->widget(Select2::className(), [
                        'data' => Converter::arrayMapOfModesForSelect2(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                        'hideSearch' => true,
                    ]) ?>

                </div>
                <div class="col-md-10">
                    <?= $form->field($searchModel, 'searchEntire', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span></div>'])->textInput(['placeholder' => 'Введите код ФККО или часть наименования...']) ?>

                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Выполнить', ['class' => 'btn btn-info']) ?>

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php Pjax::begin(['timeout' => 5000]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{summary}{items}{summary}{pager}',
        'summary' => "<p class=\"text-muted text-right\"><small>Показаны записи с <strong>{begin}</strong> по <strong>{end}</strong>, на странице <strong>{count}</strong>, всего <strong>{totalCount}</strong>. Страница <strong>{page}</strong> из <strong>{pageCount}</strong>.</small></p>",
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>

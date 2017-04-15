<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FkkoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск по ФККО | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Поиск по ФККО';
$this->params['page-header'] = 'Поиск по кодам ФККО-2014';
?>
<div class="fkko">
    <?php $form = ActiveForm::begin([
        'action' => ['/fkko'],
        'method' => 'get',
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Форма отбора</div>
        <div class="panel-body">
            <?= $form->field($searchModel, 'searchEntire', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span></div>'])->textInput(['placeholder' => 'Введите код ФККО или часть наименования...']) ?>

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
        'columns' => [
            [
                'attribute' => 'fkko_code',
                'label' => 'Код-2014',
                'options' => ['width' => 130],
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'fkko_date',
                'label' => 'Дата внесения',
                'format' => 'date',
                'options' => ['width' => 130],
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            'fkko_name:ntext',
//            [
//                'attribute' => 'fkko_dc',
//                'options' => ['width' => 80],
//                'headerOptions' => ['class' => 'text-center'],
//                'contentOptions' => ['class' => 'text-center'],
//            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

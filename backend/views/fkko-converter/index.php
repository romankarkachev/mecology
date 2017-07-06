<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FkkoConverterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchApplied bool */

$this->title = 'Коды ФККО для конвертера | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Коды ФККО для конвертера';

$this->params['breadcrumbsRight'][] = ['label' => 'Отбор', 'icon' => 'fa fa-filter', 'url' => '#frmSearch', 'data-target' => '#frmSearch', 'data-toggle' => 'collapse', 'aria-expanded' => $searchApplied === true ? 'true' : 'false', 'aria-controls' => 'frmSearch'];
$this->params['breadcrumbsRight'][] = ['icon' => 'fa fa-sort-amount-asc', 'url' => ['/fkko-converter'], 'title' => 'Сбросить отбор и применить сортировку по-умолчанию'];
?>
<div class="fkko-list">
    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['create'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i> Стереть все', ['clear'], ['class' => 'btn btn-danger pull-right', 'data' => [
            'confirm' => 'Вы действительно хотите удалить все записи?',
            'method' => 'post',
        ],]) ?>

        <?= Html::a('<i class="fa fa-file-excel-o" aria-hidden="true"></i> Импорт из Excel', ['import'], ['class' => 'btn btn-secondary pull-right']) ?>

    </p>
    <?= $this->render('_search', ['model' => $searchModel, 'searchApplied' => $searchApplied]); ?>

    <div class="card">
        <div class="card-block">
            <?php Pjax::begin(['timeout' => 5000]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "<div style=\"position: relative; min-height: 20px;\"><small class=\"pull-right form-text text-muted\" style=\"position: absolute; bottom: 0; right: 0;\">{summary}</small>\n{pager}</div>\n{items}\n<small class=\"pull-right form-text text-muted\">{summary}</small>\n{pager}",
                'summary' => "Показаны записи с <strong>{begin}</strong> по <strong>{end}</strong>, на странице <strong>{count}</strong>, всего <strong>{totalCount}</strong>. Страница <strong>{page}</strong> из <strong>{pageCount}</strong>.",
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'pager' => [
                    'options' => ['class' => 'pagination', 'style' => 'margin-bottom: 5px;'],
                    'linkOptions' => ['class' => 'page-link'],
                    'pageCssClass' => ['class' => 'page-item'],
                    'prevPageCssClass' => ['class' => 'page-item'],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ],
                'columns' => [
                    [
                        'attribute' => 'fkko_code',
                        'label' => 'Код-2014',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    'fkko_name:ntext',
                    [
                        'attribute' => 'fkko_date',
                        'label' => 'Дата внесения',
                        'format' => 'date',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'fkko2002_code',
                        'label' => 'Код-2002',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'class' => 'backend\components\grid\ActionColumn',
                        'header' => 'Действия',
                        'options' => ['width' => '90'],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</div>

<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchApplied bool */

$this->title = 'Заявки | ' . Yii::$app->name;
$this->params['breadcrumbs'][] = 'Заявки';

$this->params['breadcrumbsRight'][] = ['label' => 'Отбор', 'icon' => 'fa fa-filter', 'url' => '#frmSearch', 'data-target' => '#frmSearch', 'data-toggle' => 'collapse', 'aria-expanded' => $searchApplied === true ? 'true' : 'false', 'aria-controls' => 'frmSearch'];
$this->params['breadcrumbsRight'][] = ['icon' => 'fa fa-sort-amount-asc', 'url' => ['/orders'], 'title' => 'Сбросить отбор и применить сортировку по-умолчанию'];
?>
<div class="orders-list">
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
                        'attribute' => 'created_at',
                        'label' => 'Создано',
                        'format' => ['datetime', 'dd.MM.YYYY HH:mm'],
                        'options' => ['width' => '130'],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => function ($model, $key, $index, $column) {
                            /* @var $model \common\models\Orders */
                            if ($model->seen_at == null)
                                return ['class' => 'text-center font-weight-bold'];
                            else
                                return ['class' => 'text-center'];
                        },
                    ],
                    'form_company',
                    'form_username',
                    [
                        'attribute' => 'form_phone',
                        'value' => function($model, $key, $index, $column) {
                            /* @var $model \common\models\Orders */
                            $phone_f = '<нет номера телефона>';
                            if ($model->form_phone != null && $model->form_phone != '')
                                if (preg_match('/^(\d{3})(\d{3})(\d{2})(\d{2})$/', $model->form_phone, $matches)) {
                                    $phone_f = '+7 ('.$matches[1].') '.$matches[2].'-'.$matches[3].'-'.$matches[4];
                                }
                                else
                                    // не удалось преобразовать в человеческий вид - отображаем как есть
                                    $phone_f = $model->form_phone;

                            return $phone_f;
                        }
                    ],
                    'form_email:email',
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

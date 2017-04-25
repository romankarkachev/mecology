<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\components\TotalsColumn;

/* @var $this yii\web\View */
/* @var $fields array массив полей заявки */
/* @var $model common\models\Orders */
/* @var $dttp yii\data\ActiveDataProvider табличная часть заявки */
?>

<div class="orders-form">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-accent-danger">
                <div class="card-header">Заявка № <?= $model->id ?>, создана <?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y в H:i') ?></div>
                <div class="card-block">
                    <?php
                    // выводим поля формы в одной одежде
                    foreach($fields as $field)
                        echo $this->render('_form_field', [
                            'model' => $model,
                            'attribute' => $field,
                        ]);
                    ?>
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Заявки', ['/orders'], ['class' => 'btn btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-accent-primary">
                <div class="card-header">
                    Табличная часть
                    <span class="badge badge-pill badge-primary float-right"><?= $dttp->getTotalCount() ?></span>
                </div>
                <div class="card-block">
                    <?php Pjax::begin(['timeout' => 5000]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dttp,
                        'layout' => "{items}",
                        'tableOptions' => ['class' => 'table table-bordered table-sm table-hover'],
                        'showFooter' => true,
                        'footerRowOptions' => ['class' => 'text-center font-weight-bold'],
                        'columns' => [
                            [
                                'attribute' => 'hsCode',
                                'options' => ['width' => '130'],
                                'headerOptions' => ['class' => 'text-center'],
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'hsName',
                                'headerOptions' => ['class' => 'text-center'],
                                'format' => 'raw',
                                'value' => function($model, $key, $index, $column) {
                                    /* @var $model \common\models\Orders */
                                    /* @var $column \yii\grid\DataColumn */
                                    return '<p class="mb-0">' . $model->hsName . '</p><small class="text-muted">' . $model->formula . '</small>';
                                },
                                'footer' => 'Итого:',
                                'footerOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'class' => TotalsColumn::className(),
                                'attribute' => 'weight',
                                'format' => ['decimal', 'decimals' => 2],
                                'options' => ['width' => '90'],
                                'headerOptions' => ['class' => 'text-center'],
                                'contentOptions' => ['class' => 'text-right'],
                                'footerOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'class' => TotalsColumn::className(),
                                'attribute' => 'amount',
                                'format' => ['decimal', 'decimals' => 2],
                                'options' => ['width' => '100'],
                                'headerOptions' => ['class' => 'text-center'],
                                'contentOptions' => ['class' => 'text-right'],
                                'footerOptions' => ['class' => 'text-right'],
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use romankarkachev\apoa\widgets\Nav;
use romankarkachev\apoa\widgets\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);

romankarkachev\apoa\ApoaAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/ico', 'href' => '/favicon.png']) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrap">
    <header>
        <div class="container">
            <div class="logo">
                <?= Html::a(
                    Html::img('http://mecology.ru/wp-content/uploads/2017/04/logo-1.png', [
                        'class' => 'img-responsive',
                        'title' => 'Перейти на сайт ' . Yii::$app->name,
                    ]),
                    Yii::$app->homeUrl
                ) ?>

            </div>
            <?php
            NavBar::begin();
            $menuItems = [
                ['label' => '<i class="fa fa-home"></i> mecology', 'url' => Yii::$app->homeUrl],
                ['label' => 'Калькулятор', 'url' => ['/default/calculator']],
                ['label' => 'Конвертер', 'url' => ['/default/converter']],
                ['label' => 'ФККО', 'url' => ['/default/fkko']],
                ['label' => '<i class="fa fa-bug" aria-hidden="true"></i> Пожаловаться', 'url' => ['/default/contact']],
            ];
            echo Nav::widget([
                'encodeLabels' => false,
                'options' => ['class' => 'nav ownmenu'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

        </div>
    </header>
    <section class="sub-bnr" style="background:url(images/sub-bnr-bg-4.jpg) center no-repeat;">
        <div class="position-center-center">
            <div class="container">
                <h4><?= isset($this->params['page-header']) ? $this->params['page-header'] : $this->title ?></h4>
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => '<i class="fa fa-home"></i>',
                        'url' => Yii::$app->homeUrl,
                    ],
                    'encodeLabels' => false,
                    'tag' => 'ol',
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

            </div>
        </div>
    </section>
    <div id="content">
        <section class="contact-page padding-top-0">
            <div class="container padding-top-50 padding-bottom-50">
                <?= \romankarkachev\coreui\widgets\Alert::widget() ?>

                <?= $content ?>

            </div>
        </section>
    </div>
    <footer>
        <div class="container">
            <div class="sub-footer">
                <div class="row">
                    <div class="col-md-3">
                        <div class="logo-foot">
                            <?=  Html::img('http://mecology.ru/wp-content/uploads/2017/04/logo-1.png', [
                                'class' => 'img-responsive',
                            ]) ?>

                        </div>
                    </div>
                    <div class="col-md-6 margin-top-30">
                        <p>ООО &laquo;СОВРЕМЕННЫЕ ЭКОЛОГИЧЕСКИЕ КОМПЛЕКСНЫЕ СТРАТЕГИИ&raquo;</p>
                    </div>
                </div>
            </div>
            <div class="footer-info">
                <div class="row">
                    <div class="col-md-4">
                        <h6>всегда на связи</h6>
                        <hr>
                        <ul class="personal-info">
                            <li><i class="fa fa-map-marker"></i>Адрес: г. Московский, ул. Хабарова, 2</li>
                            <li><i class="fa fa-envelope"></i>E-mail: info@mecology.ru</li>
                            <li><i class="fa fa-phone"></i>Телефон: 8 (499) 704 64 00</li>
                            <li><i class="fa fa-fax"></i>Факс: 8 (499) 704 64 00</li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h6>Ссылки</h6>
                        <hr>
                        <ul class="links">
                            <li><?= Html::a('mecology.ru', Yii::$app->homeUrl) ?></li>
                            <li><?= Html::a('Калькулятор', ['/default/calculator']) ?></li>
                            <li><?= Html::a('Конвертер', ['/default/converter']) ?></li>
                            <li><?= Html::a('ФККО', ['/default/fkko']) ?></li>
                            <li><?= Html::a('Пожаловаться', ['/default/contact']) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="rights">
        <div class="container">
            <p>&copy; <?= date('Y') ?> ООО &laquo;Современные экологические комплексные стратегии&raquo;. Все права защищены.</p>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

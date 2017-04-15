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
            <div class="logo"> <a href="#."><img  class="img-responsive" src="images/logo.png" alt="" ></a> </div>
            <?php
            NavBar::begin();
            $menuItems = [
                ['label' => '<i class="fa fa-home"></i> mecology', 'url' => Yii::$app->homeUrl],
                ['label' => 'Калькулятор', 'url' => ['/default/calculator']],
                ['label' => 'Конвертер', 'url' => ['/default/converter']],
                ['label' => 'ФККО', 'url' => ['/default/fkko']],
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
                        <div class="logo-foot"> <img src="images/logo.png" alt="" > </div>
                    </div>
                    <div class="col-md-6 margin-top-30">
                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio semnes
                            elit Morbi leo risus, porta ac consectetur ac, vestibu lum at eros Nulla vitae elit </p>
                    </div>
                </div>
            </div>
            <div class="footer-info">
                <div class="row">
                    <div class="col-md-4">
                        <h6>keep in touch</h6>
                        <hr>
                        <ul class="personal-info">
                            <li><i class="fa fa-map-marker"></i>Address : 44 New Design Street,
                                Melbourne 005 </li>
                            <li><i class="fa fa-envelope"></i>Email : info@anous.com</li>
                            <li><i class="fa fa-phone"></i>Phone : (01) 800 433 633 </li>
                            <li><i class="fa fa-fax"></i>Fax : (01) 800 854 633 </li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h6>Links</h6>
                        <hr>
                        <ul class="links">
                            <li><a href="#.">Home</a></li>
                            <li><a href="#.">About us</a></li>
                            <li><a href="#.">Services</a></li>
                            <li><a href="#.">Portfolio</a></li>
                            <li><a href="#.">Blog</a></li>
                            <li><a href="#.">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h6>Legal</h6>
                        <hr>
                        <ul class="links">
                            <li><a href="#.">Privacy policy</a></li>
                            <li><a href="#.">Terms & Condition</a></li>
                            <li><a href="#.">FAQ</a></li>
                            <li><a href="#.">Careers</a></li>
                            <li><a href="#.">Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="rights">
        <div class="container">
            <p>Copyrights &copy; <?= date('Y') ?>  All Rights Reserved </p>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

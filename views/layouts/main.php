<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $cart = count(Yii::$app->session->get('cart')) ? count(Yii::$app->session->get('cart')) : 'Empty';
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/store/index']],
            Yii::$app->user->isGuest ? ['label' => 'Contact', 'url' => ['/store/contact']] : ['label' => 'Create User', 'url' => ['/store/create-user']],
            Yii::$app->user->isGuest ? '' : ['label' => 'Add Item', 'url' => ['/store/add-product']],
            Yii::$app->user->isGuest ? ['label' => 'About', 'url' => ['/store/about']] : ['label' => 'View Stock', 'url' => ['/store/view-stock']],
            Yii::$app->user->isGuest ? '' : ['label' => 'Cart ( '. $cart .' )', 'url' => ['/store/cart']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/store/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/store/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

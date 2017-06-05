<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Cart';
$this->title = (Yii::$app->session->get('cart')) ? ('Cart ( '. count(Yii::$app->session->get('cart')).' )') : ('Cart');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::$app->session['success']; ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?php echo Yii::$app->session['error']; ?>
        </div>
    <?php endif; ?>

    
</div>

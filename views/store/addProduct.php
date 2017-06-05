<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Add Product';
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

    <p>Please fill out the following fields to add product:</p>

    <?php
    $form = ActiveForm::begin([
                'id' => 'add-product-form',
                'enableAjaxValidation' => FALSE,
                'validateOnSubmit' => TRUE,
                'layout' => 'horizontal',
                'method' => 'post',
                'action' => Url::to(['store/add-product']),
                'options' => [
                    'ajaxUrl' => Url::to(['store/is-pid-unique']),
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-2 control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => false]) ?>
    
    <?= $form->field($model, 'product_id')->textInput(['style' => 'text-transform: uppercase']) ?>

    <?= $form->field($model, 'units')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'add-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

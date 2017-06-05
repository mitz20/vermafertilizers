<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'View Product';
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

    <?php
    $form = ActiveForm::begin([
                'id' => 'view-product-form',
                'layout' => 'horizontal',
                'method' => 'post',
                'action' => Url::to(['store/update-product']),
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-2 control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'product_id')->textInput(["disabled" => "disabled"]) ?>

    <?= $form->field($model, 'name')->textInput(["disabled" => "disabled"]) ?>

    <?= $form->field($model, 'units')->textInput(["disabled" => "disabled"]) ?>

    <?= $form->field($model, 'price')->textInput(["disabled" => "disabled"]) ?>

    <?= $form->field($model, 'product_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-4">
            <?= Html::button('Edit', ['class' => 'btn btn-primary btn-width', 'id' => 'product-edit-button', 'name' => 'edit-button']) ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-width', 'id' => 'product-submit-button', 'name' => 'submit-button', "style" => "display:none;"]) ?>
            <?= Html::button('Add to cart', ['class' => 'btn btn-primary btn-width', 'name' => 'add-to-cart-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

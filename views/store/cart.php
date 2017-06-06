<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile('@web/js/cart-input.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Cart';
$this->title = (Yii::$app->session->get('cart')) ? ('Cart ( ' . count(Yii::$app->session->get('cart')) . ' )') : ('Cart');
$this->params['breadcrumbs'][] = $this->title;

$count = count($products);
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

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-lg-4">Modify Cart</div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <form id="cart-form" action="<?= Url::to(['store/cart']) ?>" method="POST" class="form-inline">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Price Per Unit</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $key => $product) { ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $product->name ?></td>
                                            <td><?= $product->price_per_unit ?></td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning btn-number input-xs" data-type="minus" data-field="<?= $product->product_id ?>">
                                                            <span class="glyphicon glyphicon-minus"></span>
                                                        </button>
                                                    </span>
                                                    <input name="<?= $product->product_id ?>" class="form-control input-number input-xs" value="1" min="1" max="100000" type="text" size="3">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success btn-number input-xs" data-type="plus" data-field="<?= $product->product_id ?>">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="glyphicon glyphicon-remove btn btn-danger btn-sm" href="<?= Url::to(['store/update-cart', '__pid' => base64_encode($product->product_id), 'action' => 'remove']); ?>"></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <?php if ($count) { ?>
                            <button class="btn btn-primary btn-sm pull-right" type="submit">Checkout <i class="glyphicon glyphicon-arrow-right" style="color: white"></i></button>
                        <?php } ?>
                    </form>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
    </div>
    <!-- /.row -->
</div>

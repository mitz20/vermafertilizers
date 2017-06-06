<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Cart Review';
$this->params['breadcrumbs'][] = $this->title;

$count = count($products);
?>
<div class="site-about">
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
                    <div class="col-lg-4">Review Cart</div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Price Per Unit</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $key => $product) { ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $product['product_id'] ?></td>
                                        <td><?= $product['name'] ?></td>
                                        <td><?= $product['price_per_unit'] ?></td>
                                        <td><?= $product['quantity'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                    <br>
                    <?php if ($count) { ?>
                    <label>Total Cost : <i class="fa fa-inr"></i> <?= $totalCost ?> Only </label>
                    <a href="<?= Url::to(['store/generate-bill']) ?>" class="btn btn-primary btn-sm pull-right" type="button">Generate Bill</a>
                    <?php } ?>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-6 -->
    </div>
    <!-- /.row -->
<!--<code><? __FILE__ ?></code>-->
</div>



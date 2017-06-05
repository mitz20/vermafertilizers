<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Stock Details';
$this->params['breadcrumbs'][] = $this->title;
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
                    <span class="col-lg-6 mt-7">
                        Last updated stock details 
                    </span>
                    <span class="pull-right col-lg-6">
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'search-form',
                                    'enableAjaxValidation' => FALSE,
                                    'validateOnSubmit' => TRUE,
                                    'layout' => 'horizontal',
                                    'method' => 'post',
                                    'action' => Url::to(['store/view-stock']),
                                    'fieldConfig' => [
                                        'template' => "<div class=\"input-group main-search pull-right\">{input}<span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"submit\"><i class=\"fa fa-search\"></i></button></span></div>",
                                    ],
                        ]);
                        ?>
                        <?= $form->field($search, 'search')->textInput(['placeholder' => 'Search'])->label(FALSE) ?>
                        <?php ActiveForm::end(); ?>
                    </span>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Product Id</th>
                                <th>Product Name</th>
                                <th>Units</th>
                                <th>Price Per Unit</th>
                                <th class="center cart-action" text-align="center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($models as $key => $model) { ?>
                                <tr class="<?php echo ($key % 2) ? 'odd gradeA' : 'even gradeA' ?>">
                                    <td class="center"><?= $key + 1 ?></td>
                                    <td class="center"><a href="<?= Url::to(['store/view-product-details', '__pid' => base64_encode($model->product_id)]); ?>"><?= $model->product_id ?></a></td>
                                    <td class="center"><?= $model->name ?></td>
                                    <td class="center"><?= $model->units ?></td>
                                    <td class="center"><?= $model->price_per_unit ?></td>
                                    <td class="center" align="center">
                                        <?php if (Yii::$app->session->get('cart') && in_array($model->product_id, Yii::$app->session->get('cart'))) { ?>
                                            <a class="btn btn-danger btn-sm btn-width-md remove-from-cart" href="<?= Url::to(['store/update-cart', '__pid' => base64_encode($model->product_id), 'action' => 'remove']); ?>">Remove from cart</a>
                                        <?php } else { ?>
                                            <a class="btn btn-success btn-sm btn-width-md add-to-cart" href="<?= Url::to(['store/update-cart', '__pid' => base64_encode($model->product_id), 'action' => 'add']); ?>">Add to cart</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                    <?php
                    echo LinkPager::widget([
                        'pagination' => $pages,
                        'nextPageLabel' => 'Next',
                        'prevPageLabel' => 'Previous',
                        'hideOnSinglePage' => FALSE,
                        'maxButtonCount' => 10
                    ]);
                    ?>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!--<code><? __FILE__ ?></code>-->
</div>



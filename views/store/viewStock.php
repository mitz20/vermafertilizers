<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Stock Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Last updated stock details 
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($models as $key => $model) { ?>
                                <tr class="<?php echo ($key % 2) ?  'odd gradeA' : 'even gradeA' ?>">
                                    <td class="center"><?= $key + 1 ?></td>
                                    <td class="center"><?= $model->product_id ?></td>
                                    <td class="center"><?= $model->name ?></td>
                                    <td class="center"><?= $model->units ?></td>
                                    <td class="center"><?= $model->price_per_unit ?></td>
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
                        'maxButtonCount' => 5
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



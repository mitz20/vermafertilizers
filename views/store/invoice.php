<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile("@web/css/invoice.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
//    'media' => 'print',
], 'invoice-css');

$this->title = 'Invoice';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="http://nextstepwebs.com/images/logo.png" style="width:100%; max-width:300px;">
                            </td>
                            <td>
                                Invoice #: 123<br>
                                Created: January 1, 2015<br>
                                Due: February 1, 2015
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Next Step Webs, Inc.<br>
                                12345 Sunny Road<br>
                                Sunnyville, TX 12345
                            </td>

                            <td>
                                Acme Corp.<br>
                                John Doe<br>
                                john@example.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Payment Method
                </td>
                <td>
                    Check #
                </td>
            </tr>
            <tr class="details">
                <td>
                    Check
                </td>
                <td>
                    1000
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Item
                </td>

                <td>
                    Price
                </td>
            </tr>
            <tr class="item">
                <td>
                    Website design
                </td>

                <td>
                    $300.00
                </td>
            </tr>
            <tr class="item">
                <td>
                    Hosting (3 months)
                </td>

                <td>
                    $75.00
                </td>
            </tr>
            <tr class="item last">
                <td>
                    Domain name (1 year)
                </td>

                <td>
                    $10.00
                </td>
            </tr>
            <tr class="total">
                <td></td>

                <td>
                    Total: $385.00
                </td>
            </tr>
        </table>
    </div>
</div>
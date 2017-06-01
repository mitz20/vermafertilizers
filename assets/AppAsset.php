<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/custom.css',
        'vendor/bootstrap/css/bootstrap.min.css',
        'vendor/metisMenu/metisMenu.min.css',
        'vendor/font-awesome/css/font-awesome.min.css',
        'dist/css/sb-admin-2.css',
        'dist/css/sb-admin-2.min.css',
    ];
    public $js = [
        'js/custom.js',
        'js/jquery.bxslider.min.js',
        'vendor/bootstrap/js/bootstrap.min.js',
        'vendor/metisMenu/metisMenu.min.js',
        'dist/js/sb-admin-2.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

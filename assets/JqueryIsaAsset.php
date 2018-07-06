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
class JqueryIsaAsset extends AssetBundle
{
    public $sourcePath = '@vendor/webcreate/jquery-ias';

    public $js = [
        'src/jquery-ias.js',
        'src/callbacks.js',
        'src/extension/spinner.js',
        'src/extension/noneleft.js',
        'src/extension/paging.js',
        'src/extension/trigger.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

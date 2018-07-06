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
class EmojiAsset extends AssetBundle
{
    public $basePath = '@web';

    public $css = [
        'css/index.css',
        'css/jquery.mCustomScrollbar.min.css',
        'css/jquery.emoji.css',
    ];

    public $js = [
        'js/jquery.mCustomScrollbar.min.js',
        'js/jquery.emoji.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

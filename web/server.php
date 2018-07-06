#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

$SYSTEM_CONFIG = parse_ini_file(__DIR__.'/../system/SYSTEM_CONFIG') ;
if(isset($SYSTEM_CONFIG['YII_DEBUG']) &&  $SYSTEM_CONFIG['YII_DEBUG']){
    define('YII_DEBUG', true);
}
if(isset($SYSTEM_CONFIG['YII_ENV']) &&  $SYSTEM_CONFIG['YII_ENV']){
    define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/console.php');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);

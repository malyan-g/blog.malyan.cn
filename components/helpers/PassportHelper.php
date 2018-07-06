<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 18/3/30
 * Time: 上午11:20
 */

namespace app\components\helpers;

use Yii;
use yii\base\Object;

class PassportHelper extends Object
{
    private static $domain = 'http://passport.malyan.cn';

    public static function loginRoute()
    {
        return self::$domain . '/login.html?redirect_url=' . Yii::$app->request->absoluteUrl;
    }

    public static function logoutRoute()
    {
        return self::$domain . '/logout.html?redirect_url=' . Yii::$app->request->absoluteUrl;
    }

    public static function registerRoute()
    {
        return self::$domain . '/register.html';
    }
}
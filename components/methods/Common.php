<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/2
 * Time: 下午2:34
 */
namespace app\components\methods;

use yii\helpers\Url;
use yii\base\Object;

class Common extends Object
{
    /**
     * 获取重定向URL
     * @return string
     */
    public static function redirectUrl()
    {
        $redirectUrl = Url::current([], true);
        $baseUrl = Url::base(true);
        return $baseUrl . '/' == $redirectUrl ? $baseUrl : $redirectUrl;
    }
}
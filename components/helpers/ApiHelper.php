<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/10/27
 * Time: 下午1:07
 */

namespace app\components\helpers;

use Yii;
use yii\base\Object;

/**
 * Class ApiHelper
 * @package app\components\helpers
 */
class ApiHelper extends Object
{
    /**
     * 接口请求
     * @param $url
     * @param array $getData
     * @param array $postData
     * @param string $methods
     * @return array|mixed
     */
    protected static function request($url,  $getData = [], $postData = [], $methods = 'get')
    {
        $param = '';
        foreach ($getData as $key=>$val){
            $param .= $key . '=' . $val .'&';
        }
        $sign = SignHelper::encrypt(array_merge($getData, $postData), API_KEY);
        $param .= 'sign=' . $sign;
        $url = $url . '?' . $param;
        $result = HttpClientHelper::request($url,$methods,$postData);
        return $result;
    }

    /**
     * 验证token
     * @param $token
     * @return array|mixed
     */
    public static function checkToken($token)
    {
        $url = AUTH_URL . '/api/check-token.html';
        return self::request($url, ['token' => $token]);
    }
}

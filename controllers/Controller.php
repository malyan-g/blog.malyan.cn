<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/10/27
 * Time: 下午1:35
 */

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\components\helpers\ScHelper;

/**
 * Class Controller
 * @package app\controllers
 */
class Controller extends \yii\web\Controller
{
    /**
     * 异常提示
     * @param string $message
     * @throws NotFoundHttpException
     */
    public function exception($message = '')
    {
        throw new NotFoundHttpException($message);
    }
}

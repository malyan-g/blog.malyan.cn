<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/1
 * Time: 上午9:09
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\helpers\WebSocketHelper;

class ServerController extends Controller
{
    public function actionIndex()
    {
        $webSocketHelper = new WebSocketHelper([
            'host' => '127.0.0.1',
            'port' => 9098,
            'logFile' => '/Users/M/logs/swoole.log',
        ]);
        $webSocketHelper->start();
    }
}

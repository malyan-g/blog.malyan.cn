<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/1
 * Time: 上午9:11
 */

namespace app\components\helpers;


use yii\base\Object;

/**
 * Class WebSocketHelper
 * @package app\components\helpers
 * @property string $host
 * @property integer $port
 * @property integer $maxConn
 * @property integer $daemonize
 * @property integer $workerNum
 * @property string $logFile
 * @property \swoole_websocket_server $server
 */
class WebSocketHelper extends Object
{
    /**
     * IP地址
     * @var string
     */
    public $host = '0.0.0.0';

    /**
     * 端口号
     * @var int
     */
    public $port = 9051;

    /**
     * 最大连接
     * 此参数用来设置Server最大允许维持多少个tcp连接。超过此数量后，新进入的连接将被拒绝。
     * @var int
     */
    public $maxConn = 256;

    /**
     * 守护进程化
     * 加入此参数后，执行php server.php将转入后台作为守护进程运行
     * @var int
     */
    public $daemonize = 0;

    /**
     * worker进程数
     * worker_num => 4，设置启动的worker进程数量。swoole采用固定worker进程的模式。
     * PHP代码中是全异步非阻塞，worker_num配置为CPU核数的1-4倍即可。
     * 如果是同步阻塞，worker_num配置为100或者更高，具体要看每次请求处理的耗时和操作系统负载状况。
     * 当设定的worker进程数小于reactor线程数时，会自动调低reactor线程的数量
     * @var int
     */
    public $workerNum = 1;

    /**
     * 日志文件路径
     * 指定swoole错误日志文件。
     * 在swoole运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕。
     * @var string
     */
    public $logFile = '/data/log/swoole.log';

    /**
     * webSocket对象
     * @var
     */
    protected $server;

    /**
     * webSocket初始化
     */
    public function start()
    {
        // 构建webSocket对象
        $this->server = new swoole_websocket_server($this->host, $this->port);

        // 设置运行时参数
        $this->set();

        // webSocket握手处理
        $this->handshake();

        // 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
        $this->open();

        // 当服务器收到来自WebSocket客户端的数据帧时会回调此函数
        $this->message();

        // 请求服务器端会回调此函数
        $this->request();

        // WebSocket客户端与服务端断开连接回调此函数
        $this->close();

        $this->server->start();
    }

    /**
     * 设置运行时参数
     */
    public function set()
    {
        $this->server->set([
            'max_conn' => $this->maxConn,
            'worker_num' => $this->workerNum,
            'daemonize' => $this->daemonize,
            'log_file' => $this->logFile
        ]);
    }

    /**
     * webSocket握手处理(只有返回true才握手成功)
     */
    protected function handshake()
    {
        $this->server->on('handshake', function(\swoole_http_request $request, \swoole_http_response $response){
            //自定定握手规则，没有设置则用系统内置的（只支持version:13的）
            if(!isset($request->header['sec-websocket-key'])) {
                $response->end();
                return false;
            }

            // webSocket握手连接算法验证
            $secWebSocketKey = $request->header['sec-websocket-key'];
            $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
            if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
                $response->end();
                return false;
            }
            echo $request->header['sec-websocket-key'];
            $key = base64_encode(sha1(
                $request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
                true
            ));

            $headers = [
                'Upgrade' => 'websocket',
                'Connection' => 'Upgrade',
                'Sec-WebSocket-Accept' => $key,
                'Sec-WebSocket-Version' => '13',
            ];

            // WebSocket connection to 'ws://127.0.0.1:9502/'
            // failed: Error during WebSocket handshake:
            // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
            if (isset($request->header['sec-websocket-protocol'])) {
                $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
            }

            foreach ($headers as $key => $val) {
                $response->header($key, $val);
            }

            $response->status(101);
            $response->end();
            echo "connected!" . PHP_EOL;
            return true;
        });
    }

    /**
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数
     */
    protected function open()
    {
        $this->server->on('open', function (\swoole_websocket_server $server,\swoole_http_request $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });
    }

    /**
     * 当服务器收到来自WebSocket客户端的数据帧时会回调此函数
     */
    protected function message()
    {
        $this->server->on('message', function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
            // $frame   共有4个属性
            // $frame->fd   客户端的socket id，使用$server->push推送数据时需要用到
            // $frame->data   数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断, 如果是文本类型，编码格式必然是UTF-8，这是WebSocket协议规定的
            // $frame->opcode   WebSocket的OpCode类型，可以参考WebSocket协议标准文档
            // $frame->finish   表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送
            // echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

            echo "\n message: " . $frame->data . "\n";


            foreach ($this->server->connections as $fd) {
                if($frame->fd != $fd){
                    $this->server->push($fd, $frame->data);
                }
            }

            // 向websocket客户端连接推送数据，长度最大不得超过2M
            // $fd 客户端连接的ID，如果指定的$fd对应的TCP连接并非websocket客户端，将会发送失败
            // $data 要发送的数据内容
            // $opcode，指定发送数据内容的格式，默认为文本。发送二进制内容$opcode参数需要设置为WEBSOCKET_OPCODE_BINARY
            // 发送成功返回true，发送失败返回false
            // $server->push($frame->fd, "this is server");
        });
    }

    /**
     * 请求服务器端会回调此函数(用于服务器给客户端推送消息)
     */
    public function request()
    {
        $this->server->on('request', function (\swoole_http_request $request,\swoole_http_response $response) {
            // 接收http请求从get获取message参数的值，给用户推送
            // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
            foreach ($this->server->connections as $fd) {
                @$this->server->push($fd, $request->get['message']);
            }
            $response->header();
            $response->end("success");
        });
    }

    /**
     * WebSocket客户端与服务端断开连接回调此函数
     */
    protected function close()
    {
        $this->server->on('close', function (\swoole_websocket_server $server, $fd) {
            echo "client {$fd} closed\n";
        });
    }
}

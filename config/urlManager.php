<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/10/26
 * Time: 上午9:53
 */

return [
    'enablePrettyUrl' => true,
    'showScriptName'  => false, //隐藏index.php
    'suffix' => '.html', //后缀
    'rules' => [
        //'<action:[\w-]+>' => 'article/<action>',
        //'<action:[\w]+>' => 'index/<action>',
        '' => 'index/index',
        'picture' => 'index/picture',
        'music' => 'index/music',
        'video' => 'index/video',
        'chat' => 'index/chat',
        'list' => 'index/list',
        'members' => 'index/members',
        'donate' => 'index/donate',
        'service' => 'article/service',
        'database' => 'article/database',
        'front-end' => 'article/front-end',
        'security' => 'article/security',
        'search' => 'article/search',
        '<id:\d+>' => 'article/detail',
        'comment' => 'article/comment',
        'reply' => 'article/reply',
        //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
];

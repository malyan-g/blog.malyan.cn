<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/10/27
 * Time: 下午1:37
 */

namespace app\controllers;

use app\components\helpers\RedisHelper;
use app\models\Banner;
use Yii;
use app\models\Link;
use app\models\Article;
use yii\db\ActiveQuery;
use app\controllers\Controller;
use yii\data\ActiveDataProvider;
use app\components\helpers\HttpClientHelper;
use yii\web\Response;

/**
 * Class IndexController
 * @package app\controllers
 */
class IndexController extends Controller
{
    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        $query = Article::find()
            ->innerJoinWith([
                'category' => function(ActiveQuery $query){
                    $query->select(['id', 'name']);
                },
                'attach' => function(ActiveQuery $query){
                    $query->select(['article_id', 'title']);
                },
                'user' => function(ActiveQuery $query){
                    $query->select(['id', 'nickname', 'avatar']);
                }
            ])
            ->where([Article::tableName() . '.status' => Article::STATUS_SHOW])
            ->orderBy([Article::tableName() . '.created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 相册
     * @return string
     */
    public function actionPicture()
    {
        return $this->render('index');
    }

    /**
     * 音乐
     * @return string
     */
    public function actionMusic()
    {
        return $this->render('index');
    }

    /**
     * 视频
     * @return string
     */
    public function actionVideo()
    {
        return $this->render('index');
    }

    /**
     * 聊天
     * @return string
     */
    public function actionChat()
    {
        return $this->render('chat');
    }

    public function actionList()
    {
        $data = '{
  "code": 0
  ,"msg": ""
  ,"data": {
    "mine": {
      "username": "纸飞机"
      ,"id": "100000"
      ,"status": "online"
      ,"sign": "在深邃的编码世界，做一枚轻盈的纸飞机"
      ,"avatar": "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg"
    }
    ,"friend": [{
      "groupname": "前端码屌"
      ,"id": 1
      ,"online": 2
      ,"list": [{
        "username": "贤心"
        ,"id": "100001"
        ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
        ,"sign": "这些都是测试数据，实际使用请严格按照该格式返回"
      },{
        "username": "Z_子晴"
        ,"id": "108101"
        ,"avatar": "http://tva3.sinaimg.cn/crop.0.0.512.512.180/8693225ajw8f2rt20ptykj20e80e8weu.jpg"
        ,"sign": "微电商达人"
      },{
        "username": "Lemon_CC"
        ,"id": "102101"
        ,"avatar": "http://tp2.sinaimg.cn/1833062053/180/5643591594/0"
        ,"sign": ""
      },{
        "username": "马小云"
        ,"id": "168168"
        ,"avatar": "http://tp4.sinaimg.cn/2145291155/180/5601307179/1"
        ,"sign": "让天下没有难写的代码"
      },{
        "username": "徐小峥"
        ,"id": "666666"
        ,"avatar": "http://tp2.sinaimg.cn/1783286485/180/5677568891/1"
        ,"sign": "代码在囧途，也要写到底"
      }]
    },{
      "groupname": "网红"
      ,"id": 2
      ,"online": 3
      ,"list": [{
        "username": "罗玉凤"
        ,"id": "121286"
        ,"avatar": "http://tp1.sinaimg.cn/1241679004/180/5743814375/0"
        ,"sign": "在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱"
      },{
        "username": "长泽梓Azusa"
        ,"id": "100001222"
        ,"sign": "我是日本女艺人长泽あずさ"
        ,"avatar": "http://tva1.sinaimg.cn/crop.0.0.180.180.180/86b15b6cjw1e8qgp5bmzyj2050050aa8.jpg"
      },{
        "username": "大鱼_MsYuyu"
        ,"id": "12123454"
        ,"avatar": "http://tp1.sinaimg.cn/5286730964/50/5745125631/0"
        ,"sign": "我瘋了！這也太準了吧  超級笑點低"
      },{
        "username": "谢楠"
        ,"id": "10034001"
        ,"avatar": "http://tp4.sinaimg.cn/1665074831/180/5617130952/0"
        ,"sign": ""
      },{
        "username": "柏雪近在它香"
        ,"id": "3435343"
        ,"avatar": "http://tp2.sinaimg.cn/2518326245/180/5636099025/0"
        ,"sign": ""
      }]
    },{
      "groupname": "我心中的女神"
      ,"id": 3
      ,"online": 1
      ,"list": [{
        "username": "林心如"
        ,"id": "76543"
        ,"avatar": "http://tp3.sinaimg.cn/1223762662/180/5741707953/0"
        ,"sign": "我爱贤心"
      },{
        "username": "佟丽娅"
        ,"id": "4803920"
        ,"avatar": "http://tp4.sinaimg.cn/1345566427/180/5730976522/0"
        ,"sign": "我也爱贤心吖吖啊"
      }]
    }]
    ,"group": [{
      "groupname": "前端群"
      ,"id": "101"
      ,"avatar": "http://tp2.sinaimg.cn/2211874245/180/40050524279/0"
    },{
      "groupname": "Fly社区官方群"
      ,"id": "102"
      ,"avatar": "http://tp2.sinaimg.cn/5488749285/50/5719808192/1"
    }]
  }
}';
        return $data;
    }

    public function actionMembers()
    {
        $data = '{
  "code": 0
  ,"msg": ""
  ,"data": {
    "owner": {
      "username": "贤心"
      ,"id": "100001"
      ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
      ,"sign": "这些都是测试数据，实际使用请严格按照该格式返回"
    }
    ,"list": [{
      "username": "Z_子晴"
      ,"id": "108101"
      ,"avatar": "http://tva3.sinaimg.cn/crop.0.0.512.512.180/8693225ajw8f2rt20ptykj20e80e8weu.jpg"
      ,"sign": "微电商达人"
    },{
      "username": "Lemon_CC"
      ,"id": "102101"
      ,"avatar": "http://tp2.sinaimg.cn/1833062053/180/5643591594/0"
      ,"sign": ""
    },{
      "username": "马小云"
      ,"id": "168168"
      ,"avatar": "http://tp4.sinaimg.cn/2145291155/180/5601307179/1"
      ,"sign": "让天下没有难写的代码"
    },{
      "username": "徐小峥"
      ,"id": "666666"
      ,"avatar": "http://tp2.sinaimg.cn/1783286485/180/5677568891/1"
      ,"sign": "代码在囧途，也要写到底"
    },{
      "username": "罗玉凤"
      ,"id": "121286"
      ,"avatar": "http://tp1.sinaimg.cn/1241679004/180/5743814375/0"
      ,"sign": "在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱"
    },{
      "username": "长泽梓Azusa"
      ,"id": "100001222"
      ,"sign": "我是日本女艺人长泽あずさ"
      ,"avatar": "http://tva1.sinaimg.cn/crop.0.0.180.180.180/86b15b6cjw1e8qgp5bmzyj2050050aa8.jpg"
    },{
      "username": "大鱼_MsYuyu"
      ,"id": "12123454"
      ,"avatar": "http://tp1.sinaimg.cn/5286730964/50/5745125631/0"
      ,"sign": "我瘋了！這也太準了吧  超級笑點低"
    },{
      "username": "谢楠"
      ,"id": "10034001"
      ,"avatar": "http://tp4.sinaimg.cn/1665074831/180/5617130952/0"
      ,"sign": ""
    },{
      "username": "柏雪近在它香"
      ,"id": "3435343"
      ,"avatar": "http://tp2.sinaimg.cn/2518326245/180/5636099025/0"
      ,"sign": ""
    },{
      "username": "林心如"
      ,"id": "76543"
      ,"avatar": "http://tp3.sinaimg.cn/1223762662/180/5741707953/0"
      ,"sign": "我爱贤心"
    },{
      "username": "佟丽娅"
      ,"id": "4803920"
      ,"avatar": "http://tp4.sinaimg.cn/1345566427/180/5730976522/0"
      ,"sign": "我也爱贤心吖吖啊"
    }]
  }
}';
        return $data;
    }

    /**
     * 捐赠
     * @return string
     */
    public function actionDonate()
    {
        //RedisHelper::getInstance()->set('test', 1);
        //Yii::$app->redis->mset('test', 2);
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->set('test', 1);
        $redis->mset(['test' => 2]);
        return $this->render('donate');
    }

    public function actionPush()
    {
        // 创建一个新cURL资源
        $ch = curl_init();
// 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9098?server.php?message=测试阿12121");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        //$post_data = array('me' => str_repeat('a', 80));
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  //POST数据
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 抓取URL并把它传递给浏览器
        $res =  curl_exec($ch);
        var_dump($res);
// 关闭cURL资源，并且释放系统资源
        curl_close($ch);

        //echo HttpClientHelper::request('http://127.0.0.1:9098/server.php', 'get', ['message' => '真的吗']);
    }
}
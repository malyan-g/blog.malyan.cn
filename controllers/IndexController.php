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
        return $this->render('picture');
    }

    /**
     * 音乐
     * @return string
     */
    public function actionMusic()
    {
        return $this->render('music');
    }

    /**
     * 视频
     * @return string
     */
    public function actionVideo()
    {
        return $this->render('video');
    }

    /**
     * 聊天
     * @return string
     */
    public function actionChat()
    {
        $this->layout = false;
        if(Yii::$app->user->isGuest){
            if(Yii::$app->session->has('chat.nickname')){
                $nickname = Yii::$app->session->get('chat.nickname');
            }else{
                $nickname =  '游客' . uniqid();
                Yii::$app->session->set('chat.nickname', $nickname);
            }

        }else{
            $nickname = Yii::$app->user->identity->nickname;
        }
        return $this->render('chat', [
            'nickname' => $nickname
        ]);
    }

    /**
     * 捐赠
     * @return string
     */
    public function actionDonate()
    {
        return $this->render('donate');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/2
 * Time: 下午3:43
 */

namespace app\controllers;

use app\models\form\ReplyForm;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\db\ActiveQuery;
use app\models\Article;
use app\models\ArticleComment;
use app\models\search\ArticleSearch;
use app\components\helpers\RedisHelper;

class ArticleController extends Controller
{
    
    /**
     * 服务端
     * @return string
     */
    public function actionService()
    {
        $this->view->title = '服务端';
        return $this->doList(ArticleSearch::SEARCH_TYPE_SERVICE);
    }

    /**
     * 数据库
     * @return string
     */
    public function actionDatabase()
    {
        $this->view->title = '数据库';
        return $this->doList(ArticleSearch::SEARCH_TYPE_DATABASE);
    }

    /**
     * web前端
     * @return string
     */
    public function actionFrontEnd()
    {
        $this->view->title = 'web前端';
        return $this->doList(ArticleSearch::SEARCH_TYPE_FRONT_END);
    }

    /**
     * Web安全
     * @return string
     */
    public function actionSecurity()
    {
        $this->view->title = 'Web安全';
        return $this->doList(ArticleSearch::SEARCH_TYPE_SECURITY);
    }

    /**
     * 搜索
     * @return string
     */
    public function actionSearch()
    {
        $this->view->title = '搜索';
        return $this->doList(ArticleSearch::SEARCH_TYPE_SEARCH);
    }


    /**
     * 文章详情
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDetail($id)
    {
        $id = intval($id);
        if($id < 1){
            $this->exception('非法请求');
        }

        // 文章
        $article = Article::find()
            ->innerJoinWith([
                'category' => function(ActiveQuery $query){
                    $query->select(['id', 'name']);
                },
                'attach' => function(ActiveQuery $query){
                    $query->select(['article_id', 'title', 'content']);
                },
                'user' => function(ActiveQuery $query){
                    $query->select(['id', 'nickname', 'avatar']);
                }
            ])
            ->where([Article::tableName() . '.id' => $id, Article::tableName() . '.status' => Article::STATUS_SHOW])
            ->one();

        if(!$article){
            $this->exception('非法请求');
        }

        // 更新浏览量
        if($this->browseRenewal($id)){
            $article->browse_num++;
        }

        // 评论内容
        $comments = ArticleComment::comments($id);

        return $this->render('detail',[
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * 点赞
     * @return array
     */
    public function actionPraise()
    {
        $data = ['code' => API_CODE_ERROR, 'msg' => '网络异常'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->user->isGuest){
            $data['msg'] = '请先登录后再进行评论';
            return $data;
        }

        $request = Yii::$app->request;
        if($request->isGet && $request->isAjax){
            $id = intval($request->get('id', 0));
            $type = $request->get('type', 0);
            if($id) {
                $model = $type == 1 ? Article::findOne(['id' => $id]) : ArticleComment::findOne(['id' => $id]);
                if ($model) {
                    $user_id = Yii::$app->user->id;
                    $key = $model::PRAISE_KEY . $model->id;
                    $redisHelper = RedisHelper::getInstance();
                    if($redisHelper->sIsMembers($key, $user_id)){
                        $redisHelper->sRem($key, $user_id);
                    }else{
                        $redisHelper->sAdd($key, $user_id);
                    }
                    $data['code'] = API_CODE_SUCCESS;
                }
            }
        }
        return $data;
    }

    /**
     * 评论
     * @return array
     */
    public function actionComment()
    {
        $data = ['code' => API_CODE_ERROR, 'msg' => '网络异常'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->user->isGuest){
            $data['msg'] = '请先登录后再进行评论';
            return $data;
        }
        $request = Yii::$app->request;
        if($request->isPost && $request->isAjax){
            $model = new ArticleComment();
            if($model->load($request->post()) && $model->validate()){
                $result = Article::findOne($model->article_id);
                if($result) {
                    if($model->save(false)){
                        $result->comment_num++;
                        $result->save(false);
                        $user = Yii::$app->user->identity;
                        $avatar = User::getAvatar($user['id']);
                        $created_at = date('Y-m-d H:i:s', $model->created_at);
                        $content = <<<HTML
                            <li class="media" data-key="{$model->id}">
                                <div class="media-left">
                                    <a>
                                        <img class="media-object" src="{$avatar}" alt="wushshsha">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <a>{$user['nickname']}</a>
                                        评论于{$created_at}
                                    </div>
                                    <div class="media-content">
                                        <p>{$model->content}</p>
                                        <div class="media-action">
                                            <span class="pull-right">
                                                <a href="javascript:void(0);" title="点赞" class="praise" data-id="1" data-type="2">
                                                    <i class="fa fa-thumbs-o-up "></i>
                                                    <i>0</i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>
HTML;

                        $data = [
                            'code' => API_CODE_SUCCESS,
                            'msg' => '评论成功',
                            'content' => $content
                        ];
                    }
                }
            }else{
                $data['msg'] = current($model->firstErrors);
            }
        }
        return $data;
    }

    /**
     * 回复
     * @return array
     */
    public function actionReply()
    {
        $data = ['code' => API_CODE_ERROR, 'msg' => '网络异常'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->user->isGuest){
            $data['msg'] = '请先登录后再进行回复';
            return $data;
        }
        $request = Yii::$app->request;
        if($request->isPost && $request->isAjax){
            $model = new ReplyForm();
            if($model->load($request->post()) && $model->validate()){
                $result = Article::find()->select(['id'])->where(['id' => $model->article_id])->scalar();
                if($result) {
                    if($model->save(false)){
                        $user = Yii::$app->user->identity;
                        $data = [
                            'code' => API_CODE_SUCCESS,
                            'msg' => '回复成功',
                            'data' => [
                                'id' => $user['id'],
                                'nickname' => $user['nickname'],
                                'avatar' => User::getAvatar($user['id']),
                                'content' => $model->content,
                                'comment_id' => $model->comment_id,
                                'answer_user_id' => $model->answer_user_id,
                                'created_at' => date('Y-m-d H:i:s', $model->created_at)
                            ]
                        ];
                    }
                }
            }else{
                $data['msg'] = current($model->firstErrors);
            }
        }
        return $data;
    }

    /**
     * 列表
     * @param $searchType
     * @return string
     */
    protected function doList($searchType)
    {
        $searchModel = new ArticleSearch();
        $searchModel->searchType =  $searchType;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('list',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 更新浏览量
     * @param $id
     * @return bool
     */
    protected function browseRenewal($id)
    {
        $id = intval($id);
        if($id > 0){
            $browseArray = Yii::$app->request->cookies->getValue('browseArray', []);
            if(!in_array($id, $browseArray)){
                $model = Article::findOne($id);
                $model->browse_num++;
                $model->save(false);
                $browseArray[] = $id;
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'browseArray',
                    'value' => $browseArray
                ]));
                return true;
            }
        }
        return false;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/5/12
 * Time: 下午2:58
 */
/* @var $link \app\models\Link */
/* @var $dataProvider \yii\data\ActiveDataProvider */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Carousel;
use app\models\User;

$this->title = '青春也迷茫';
\app\components\widgets\JqueryIsa::widget();
?>
<!-- 轮播start -->
<div class="row flash-view">
    <div class="col-lg-12">
        <?= Carousel::widget([
            'controls' => false,
            'items' => \app\models\Banner::items()
        ]);?>
    </div>
</div>
<!-- 轮播end -->
<div class="row">
    <!--  动态start -->
    <div class="col-lg-9">
        <div class="panel panel-default  main-content">
            <div class="panel-body">
                <div class="page-header" style="padding-bottom:7px ">
                    <h1>最新动态</h1>
                </div>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<ul class="media-list">{items}</ul>{pager}',
                    'itemView' => function($model){
                        $html = '
                            <li class="media">
                                <div class="media-left">
                                    <a>
                                        <img class="media-object" src="' . User::getAvatar($model->user->id) .'" alt="akingsky">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <a>' . Html::encode( $model->user->nickname ) . '</a>
                                        发布了' . Html::encode($model->category->name) . '文章
                                    </div>
                                    <div class="media-content">
                                        <a href="' . Url::toRoute(['article/detail', 'id' => $model->id]) . '" target="_blank">' . Html::encode($model->attach->title) . '</a>
                                    </div>
                                    <div class="media-action">
                                        <span>' . date('Y-m-d H:i:s', $model->created_at) .'</span>
                                        <span class="pull-right">
                                            浏览(' . $model->browse_num . ') |
                                            评论(' . $model->comment_num . ')
                                        </span>
                                    </div>
                                </div>
                            </li>';
                        return $html;
                    }
                ]) ?>
            </div>
        </div>
    </div>
    <!--  动态end-->
    <div class="col-lg-3">
        <div class="panel panel-default" style="background: url(http://img.malyan.cn/user-bg.jpg) #fff; background-size:100% 120px; background-repeat:no-repeat;">
            <div class="panel-body">
                <div class="user">
                    <img class="avatar" src="http://img.malyan.cn/user.jpg" alt="wkf928592">
                    <h1>Malyan</h1>
                </div>
            </div>
        </div>
        <!-- 友情链接start -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">
                    <i class="fa fa-link"></i>
                    友情链接
                </h2>
            </div>
            <div class="panel-body">
                <ul class="post-list">
                    <?php foreach (\app\models\Links::items() as $val): ?>
                        <li>
                            <i class="fa fa-angle-double-right"></i>
                            <a href="<?= Html::encode($val->link); ?>" target="_blank"><?= Html::encode($val->title); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- 友情链接end -->
        <!-- 友情资助start -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">
                    <i class="fa fa-retweet"></i>
                    友情资助
                </h2>
            </div>
            <div class="panel-body">
                <a href="javascript:;" title="友情资助">
                    <img class="fund" src="http://img.malyan.cn/wechat_pay_code.jpg" alt="友情资助" >
                </a>
            </div>
        </div>
        <!-- 友情资助end -->
    </div>
</div>

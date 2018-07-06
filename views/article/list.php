<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/5/12
 * Time: 下午3:12
 */
/* @var  $sort */
/* @var  $searchModel \app\models\search\ArticleSearch */
/* @var  $dataProvider \yii\data\ActiveDataProvider */
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;
use yii\widgets\ListView;

\app\components\widgets\JqueryIsa::widget();
?>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- 排序 -->
                <div class="page-header" style="padding-bottom:7px ">
                    <h1><?= $this->title ?></h1>
                    <ul id="w0" class="nav nav-tabs nav-main">
                        <?php foreach ($searchModel->sortArray as $key => $val): ?>
                            <li <?= $searchModel->sort == $key ? 'class="active"' : ''; ?>>
                                <a href="<?= Url::current(['sort' => $key], true) ?>"><?= $val; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- 列表 -->
                <?= $dataProvider->query->count() ? ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '<ul class="media-list">{items}</ul>{pager}',
                    'itemView' => function($article){
                        $html = '<li class="media" data-key="' . $article->id .'">
                            <div class="media-left">
                                <a rel="author">
                                    <img class="media-object" src="' . User::getAvatar($article->user->id) . '">
                                </a>
                            </div>
                            <div class="media-body">
                                <h2 class="media-heading">
                                    <i class="fa fa-file-text-o fa-fw"></i>
                                    <a href="' . Url::toRoute(['article/detail', 'id' => $article->id]) . '" target="_blank">' . Html::encode($article->attach->title) . '</a>
                                    <small>
                                        <i class="fa fa-thumbs-o-up"></i>
                                        ' . $article->praise_num . '
                                    </small>
                                </h2>
                                <div class="media-action">
                                    <a rel="author">' . Html::encode($article->user->nickname) . '</a>
                                    发布于 ' . date('Y-m-d', $article->created_at) . '
                                    <span class="dot">•</span>
                                    ' . $article->category->name . '
                                </div>
                            </div>
                            <div class="media-right">
                                <div class="btn btn-default">
                                    <h4>浏览</h4>
                                    ' . $article->browse_num . '
                                </div>
                            </div>
                            <div class="media-right">
                                <div class="btn btn-default">
                                    <h4>评论</h4>
                                     ' . $article->comment_num . '
                                </div>
                            </div>
                        </li>';
                        return $html;
                    }
                ]) : '暂无数据' ?>
            </div>
        </div>
    </div>
    <?= $this->render('right-content'); ?>
</div>

<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/5/17
 * Time: 下午2:53
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Article;
use yii\db\ActiveQuery;
// 热门文章
$hotArticle = Article::find()
    ->innerJoinWith([
        'attach' => function(ActiveQuery $query){
            $query->select(['article_id', 'title', 'content']);
        }
    ])
    ->where(['status' => Article::STATUS_SHOW])
    ->orderBy(['browse_num' => SORT_DESC])
    ->limit(8)
    ->all();
// 最新文章
$newArticle = Article::find()
    ->innerJoinWith([
        'attach' => function(ActiveQuery $query){
            $query->select(['article_id', 'title', 'content']);
        }
    ])
    ->where(['status' => Article::STATUS_SHOW])
    ->orderBy(['created_at' => SORT_ASC])
    ->limit(8)
    ->all();
?>
<div class="col-lg-3 visible-lg-inline-block">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">
                <i class="fa fa-file-word-o"></i>
                热门推荐
            </h2>
        </div>
        <div class="panel-body">
            <ul class="post-list">
                <?php foreach ($hotArticle as $val): ?>
                    <li>
                        <i class="fa fa-angle-double-right"></i>
                        <a href="<?= Url::toRoute(['article/detail', 'id' => $val->id]); ?>" target="_blank">
                            <?= Html::encode($val->attach->title); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">
                <i class="fa fa-file-word-o"></i>
                近期文章
            </h2>
        </div>
        <div class="panel-body">
            <ul class="post-list">
                <?php foreach ($newArticle as $val): ?>
                <li>
                    <i class="fa fa-angle-double-right"></i>
                    <a href="<?= Url::toRoute(['article/detail', 'id' => $val->id]); ?>" target="_blank">
                        <?= Html::encode($val->attach->title); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
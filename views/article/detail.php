<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/5/12
 * Time: 下午3:12
 */
/* @var $article \app\models\Article */
/* @var $comment \app\models\ArticleComment */
/* @var $login */
/* @var $comments */
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;
use yii\helpers\Markdown;
use app\components\methods\Common;

$this->title = $article->attach->title;

$redirectUrl = '?redirect_url=' . Common::redirectUrl()
?>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- 标题 -->
                <div class="page-header">
                    <h1>
                        <?= Html::encode($article->attach->title); ?>
                        <small>[ <?= $article->category->name; ?> ]</small>
                    </h1>
                </div>
                <div class="action">
                    <!-- 作者 -->
                    <span>
                        <i class="fa fa-user"></i>
                        <?= Html::encode($article->user->nickname); ?>
                    </span>
                    <!-- 时间 -->
                    <span>
                        <i class="fa fa-clock-o"></i>
                        <?= date('Y-m-d H:i:s', $article->created_at); ?>
                    </span>
                    <!-- 浏览次数 -->
                    <span>
                        <i class="fa fa-eye"></i>
                        <?= $article->browse_num; ?>次浏览
                    </span>
                    <!-- 评论次数 -->
                    <span>
                        <i class="fa fa-comments-o"></i>
                        <i class="comment-num"><?= $article->comment_num; ?></i>条评论
                    </span>
                    <span class="pull-right">
                        <!-- 点赞次数 -->
                        <a href="javascript:void(0);" title="点赞" class="praise" data-id="<?= $article->id; ?>" data-type="1">
                            <i class="fa fa-thumbs-o-up <?= $article->is_praise ? 'praise-i' : ''; ?>"></i>
                            <i>
                                <?= $article->praise_num; ?>
                            </i>
                        </a>
                    </span>
                </div>
                <!-- 文章内容 -->
                <?= Markdown::process($article->attach->content, 'gfm-comment'); ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- 评论区 -->
                <div id="comment">
                    <div class="page-header">
                        <h2>发表评论</h2>
                    </div>
                    <?= $this->render('comment', [
                        'articleId' => $article->id
                    ]) ?>
                    <?= $this->render('reply', [
                        'articleId' => $article->id
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="comments">
                    <div class="page-header">
                        <?= Html::tag('h2','共 <em class="comment-num" id="comment-num">' . count($comments) . '</em> 条评论'); ?>
                    </div>
                    <ul class="media-list">
                    <?php if($comments): ?>
                        <?php foreach ($comments as $val): ?>
                            <li class="media" data-key="<?= $val['id'] ?>">
                                <div class="media-left">
                                    <a>
                                        <img class="media-object" src="<?= User::getAvatar($val['user']['id']); ?>" alt="wushshsha">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <a><?= Html::encode($val['user']['nickname']); ?></a>
                                        评论于 <?= date('Y-m-d H:i:s', $val['created_at']); ?>
                                    </div>
                                    <div class="media-content">
                                        <p><?= Html::encode($val['content']); ?></p>
                                        <div class="hint child-num <?= isset($val['child']) ? 'hidden' : ''; ?>hidden">
                                            共
                                            <em><?= isset($val['child']) ? count($val['child']) : 0; ?></em>
                                            条回复
                                        </div>
                                        <?php if(isset($val['child'])): ?>
                                            <?php foreach ($val['child'] as $v): ?>
                                                <div class="media">
                                                    <div class="media-left">
                                                        <a>
                                                            <img class="media-object" src="<?= User::getAvatar($v['user']['id']); ?>" alt="iceluo">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="media-heading">
                                                            <a rel="author"><?= Html::encode($v['user']['nickname']); ?></a>
                                                            回复于 <?= date('Y-m-d H:i:s', $v['created_at']); ?>

                                                            <?php if(Yii::$app->user->id != $v['user_id']): ?>
                                                            <span class="pull-right">
                                                                <a class="reply" href="javascript:void(0);"  data-user="<?= $v['user_id']?>" data-nickname="<?= $v['user']['nickname'] ?>">
                                                                    <i class="fa fa-reply"></i>
                                                                    回复
                                                                </a>
                                                            </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="media-content">
                                                            <p><?= $v['answer_user_id'] ? Html::a('@'. $v['answerUser']['nickname'], 'javascript:void(0)') . ' ' : ''; ?><?= Html::encode($v['content']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <div class="media-action">
                                        <?php if(Yii::$app->user->id != $val['user_id']): ?>
                                        <a class="reply" href="javascript:void(0);"  data-user="<?= $val['user_id' ]?>"  data-nickname="<?= $val['user']['nickname'] ?>">
                                            <i class="fa fa-reply"></i>
                                            回复
                                        </a>
                                        <?php endif; ?>
                                        <span class="pull-right">
                                            <a href="javascript:void(0);" title="点赞" class="praise" data-id="<?= $val['id']; ?>" data-type="2">
                                                <i class="fa fa-thumbs-o-up <?= $val['is_praise'] ? 'praise-i' : ''; ?>"></i>
                                                <i>
                                                    <?= $val['praise_num']; ?>
                                                </i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-comment">暂无评论</li>
                    <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('right-content') ?>
</div>
<?php
$praiseUrl = Url::toRoute(['article/praise']);
$js = <<<EOD
    // 评论点赞
    $(document).on("click", ".praise", function(){
        let i = $(this).find('i');
        let num = $(this).find('i').last();
        $.ajax({
            type: "GET",
            url: "{$praiseUrl}",
            data: {id: $(this).data('id'), type: $(this).data('type')},
            success: function(data){
                if(data.code){
                    if(i.hasClass('praise-i')){
                        i.removeClass('praise-i');
                        num.html(parseInt(num.html()) - 1);
                    }else{
                        i.addClass('praise-i');
                        num.html(parseInt(num.html()) + 1);
                    }
                }else{
                    layer.msg(data.msg);
                }
            },
            error: function(){
                layer.msg('网络错误');
            }
        });
    });
EOD;
$this->registerJs($js);
?>

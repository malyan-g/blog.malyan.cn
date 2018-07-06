<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/7/19
 * Time: 上午10:38
 */
/* @var $articleId */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\ArticleComment;

$model = new ArticleComment();
$model->article_id = $articleId;
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['article/comment']),
    'fieldConfig' => [
        'template' => '{input}'
    ],
    'enableAjaxValidation' => true
]) ?>
<?= $form->field($model, 'article_id')->hiddenInput() ?>
<?= $form->field($model, 'content', [
    'options' => ['class' => 'input-group'],
    'template' => '{input}<span class="input-group-btn">' . Html::submitButton('评论', ['class' => 'btn btn-success comment-btn', 'style' => 'height:100px']) . '</span>'
])->textarea(['style' => 'height:100px;', 'maxlength' => 250, 'placeholder' => '最多可输入250字符']); ?>
<?php ActiveForm::end() ?>
<?php
$contentId = Html::getInputId($model, 'content');
$js = <<<JS
    // 评论
    $('.comment-btn').click(function () {
        $.ajax({
            url: "$form->action",
            type: "POST",
            dataType: "json",
            data: $('#$form->id').serialize(),
            success: function(data) {
                if(data.code){
                    $('.no-comment').hide();
                    $('.media-list').append(data.content);
                    $('#$contentId').val('');
                    $('.comment-num').html(parseInt($('#comment-num').html()) + 1);
                    layer.msg(data.msg);
                }else{
                    layer.msg(data.msg);
                }
            },
            error: function() {
                layer.msg('网络错误');
            }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>

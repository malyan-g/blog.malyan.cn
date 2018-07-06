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
use app\models\form\ReplyForm;

$model = new ReplyForm();
$model->article_id = $articleId;
?>
<?php $form = ActiveForm::begin([
    'id' => 'reply-form',
    'action' => Url::to(['article/reply']),
    'options' => [
        'class' => 'hidden',
    ],
    'fieldConfig' => [
        'template' => '{input}'
    ],
    'enableAjaxValidation' => true
]) ?>
<?= $form->field($model, 'article_id')->hiddenInput() ?>
<?= $form->field($model, 'comment_id')->hiddenInput() ?>
<?= $form->field($model, 'answer_user_id')->hiddenInput() ?>
<?= $form->field($model, 'content', [
    'options' => ['class' => 'input-group'],
    'template' => '{input}<span class="input-group-btn">' . Html::submitButton('回复', ['class' => 'btn btn-success reply-btn', 'style' => 'height:64px']) . '</span>'
])->textarea(['style' => 'height:64px;', 'maxlength' => 250, 'placeholder' => '最多可输入250字符']); ?>
<?php ActiveForm::end() ?>
<?php
$formId = $form->id;
$commonId = Html::getInputId($model, 'comment_id');
$answerUserId = Html::getInputId($model, 'answer_user_id');
$js = <<<JS
    var reply_nickname = '';
    // 回复
    $(document).on('click', '.reply', function () {
        var commonId = $(this).parents('li').attr('data-key');
        if(commonId == $('#$formId').find('#$commonId').val() && !$('#$formId').hasClass('hidden')){
            return false;
        }
        $('#$formId').removeClass('hidden').appendTo($(this).parent().parent().parent().parent());
        $('#$formId').find('#$commonId').val(commonId);
        $('#$formId').find('#$answerUserId').val($(this).attr('data-user'));
        reply_nickname = $(this).attr('data-nickname');
        var commonPlaceholder =  $(this).parents('div.media').length > 0 ? '@' + reply_nickname : '最多可输入1000字符';
        $('#$formId').find('textarea').attr('placeholder', commonPlaceholder).val('');
        console.log(reply_nickname);
    });
    
    // 评论
    $('.reply-btn').click(function () {
        $.ajax({
            url: "$form->action",
            type: "POST",
            dataType: "json",
            data: $('#$formId').serialize(),
            success: function(e) {
                if(e.code){
                    var data = e.data;
                    var str = '<div class="media">' +
                        '<div class="media-left">' +
                        '<a><img class="media-object" src="' + data.avatar + '" alt="iceluo"></a>' +
                        '</div>' +
                        '<div class="media-body">' +
                        '<div class="media-heading">' +
                        '<a rel="author">' + data.nickname + '</a> 回复于 ' + data.created_at + '' +
                        '</div>' +
                        '<div class="media-content">' +
                        '<p>' + (data.answer_user_id ? '<a href="javascript:void(0)">@' + reply_nickname + '</a> ' : '') + data.content + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    var childNum = $('#$formId').addClass('hidden').parents('.media').children('.media-body').children('.media-content').children('.child-num');
                    childNum.removeClass('hidden').children('em').html(parseInt(childNum.children('em').html())+1);
                     $('#$formId').addClass('hidden').parents('.media').children('.media-body').children('.media-content').children('.media-action').before(str);
                }else{
                    layer.msg(e.msg);
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

<?php

namespace app\models\form;

use Yii;
use app\models\ArticleComment;
use app\components\helpers\RedisHelper;

/**
 * Class ReplyForm
 * @package app\models\form
 */
class ReplyForm extends ArticleComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'comment_id', 'content'], 'required'],
            [['article_id', 'comment_id', 'user_id', 'answer_user_id', 'praise_num', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 250],
            [['answer_user_id', 'praise_num'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => '文章ID',
            'comment_id' => '回复评论ID',
            'user_id' => '用户ID',
            'answer_user_id' => '回复用户ID',
            'praise_num' => '点赞次数',
            'content' => '回复内容',
            'created_at' => '创建时间',
        ];
    }
}

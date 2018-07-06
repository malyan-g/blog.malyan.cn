<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%article_answer}}".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property integer $answer_user_id
 * @property string $content
 * @property integer $created_at
 */
class ArticleAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id', 'answer_user_id', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => '评论ID',
            'user_id' => '用户ID',
            'answer_user_id' => '回复用户ID',
            'content' => '回复内容',
            'created_at' => '创建时间',
        ];
    }
}

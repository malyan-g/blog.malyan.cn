<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%article_attach}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $title
 * @property string $content
 */
class ArticleAttach extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_attach}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['article_id'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 80],
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
            'title' => '标题',
            'content' => '内容',
        ];
    }
}

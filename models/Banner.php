<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $describe
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 */
class Banner extends \yii\db\ActiveRecord
{
    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'status', 'created_at'], 'required'],
            [['sort', 'status', 'created_at'], 'integer'],
            [['url', 'describe'], 'string', 'max' => 80],
            [['title'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => '地址',
            'title' => '标题',
            'describe' => '描述',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 轮播数据
     * @return array
     */
    public static function items()
    {
        $banner = self::find()->select(['url', 'title', 'describe'])->where(['status' => self::STATUS_SHOW])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        $data = [];
        foreach ($banner as $val){
            $data[] = [
                'content' => Html::img($val['url'], ['style' => 'height: 324px;width: 100%']),
                'caption' => Html::tag('h4', $val['title']) . Html::tag('p', $val['describe']),
            ];
        }
        return $data;
    }
}

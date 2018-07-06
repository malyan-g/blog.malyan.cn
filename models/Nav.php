<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%nav}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property string $url
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 */
class Nav extends \yii\db\ActiveRecord
{
    const STATUS_SHOW = 1;
    const STATUS_HIDDEN = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort', 'status', 'created_at'], 'integer'],
            [['created_at'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级ID',
            'name' => '名称',
            'url' => '地址',
            'sort' => '排序',
            'status' => '状态（0-不显示 1-显示）',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 导航数据
     * @return array
     */
    public static function items()
    {
        $nav = self::find()->select(['id', 'pid', 'name', 'url'])->where(['status' => self::STATUS_SHOW])->orderBy(['pid' => SORT_ASC, 'sort' => SORT_ASC])->asArray()->all();
        $data = [];
        foreach ($nav as $val){
            if($val['pid']){
                $data[$val['pid']]['items'][] = [
                    'label' => $val['name'],
                    'url' => [$val['url']]
                ];
            }else{
                $data[$val['id']] = [
                    'label' => $val['name'],
                    'url' =>  in_array($val['url'], ['/', '#']) ? $val['url'] : [$val['url']],
                ];
            }
        }

        return $data;
    }
}

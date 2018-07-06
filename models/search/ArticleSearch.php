<?php

namespace app\models\search;

use yii\db\ActiveQuery;
use app\models\Article;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\components\helpers\MatchHelper;

/**
 * Class ArticleSearch
 * @package app\models\search
 */
class ArticleSearch extends Article
{
    const SEARCH_TYPE_SERVICE = 2;
    const SEARCH_TYPE_DATABASE = 3;
    const SEARCH_TYPE_FRONT_END = 4;
    const SEARCH_TYPE_SECURITY = 5;
    const SEARCH_TYPE_SEARCH = 0;

    public $searchType = null;
    public $sort = 1;
    public $keywords;

    public  $sortArray = [
        self::SORT_NEW => '最新发布',
        self::SORT_BROWSE => '最多浏览',
        self::SORT_COMMENT => '最多评论'
    ];

    public $sortFieldArray = [
        self::SORT_NEW => 'created_at',
        self::SORT_BROWSE => 'browse_num',
        self::SORT_COMMENT => 'comment_num'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keywords'], 'string'],
            [['author', 'category_id', 'browse_num', 'comment_num', 'status', 'created_at', 'sort'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = array_merge(
            $scenarios[self::SCENARIO_DEFAULT],
            ['sort', 'keywords']
        );
        return $scenarios;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(Array $params)
    {
        $query = self::find();

        $this->load(['data' => $params], 'data');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if(!$this->validate()) {
            return null;
        }

        // 分类和用户表关联
        $query->innerJoinWith([
            'category' => function(ActiveQuery $query){
                $query->select(['id', 'name']);
            },
            'user' => function(ActiveQuery $query){
                $query->select(['id', 'nickname', 'avatar']);
            }
        ]);

        // 附加信息关联
        $keywords = $this->keywords;
        $query->innerJoinWith([
            'attach' => function(ActiveQuery $query) use($keywords){
                $query->select(['article_id', 'title']);
                // 标题
                if($keywords){
                    $query->orOnCondition(['like', 'title', $keywords]);
                    $query->orOnCondition(['like', 'content', $keywords]);
                }
            }
        ]);

        // 分类
        if($this->category_id){
            $query->andWhere(['category_id' => $this->category_id]);
        }

        // 搜索类型
        if($this->searchType){
            $query->andWhere(['pid' => $this->searchType]);
        }

        // 状态
        $query->andWhere([self::tableName() . '.status' => self::STATUS_SHOW]);

        // 排序
        $query->orderBy([self::tableName() . '.' . ArrayHelper::getValue($this->sortFieldArray, $this->sort, $this->sortFieldArray[1]) => SORT_DESC]);

        return $dataProvider;
    }
}

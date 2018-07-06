<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/3
 * Time: 下午5:40
 */

namespace app\components\widgets;


use app\assets\EmojiAsset;
use yii\base\Widget;

class Emoji extends Widget
{
    /**
     * 输入框ID
     * @var string
     */
    public $id = 'editor';
    /**
     * 表情按钮ID
     * @var string
     */
    public $button = 'emoji-btn';

    public function run()
    {
        $js = <<<JS
            $("#{$this->id}").emoji({
                button: "#{$this->button}",
                showTab: false,
                animation: 'slide',
                icons: [{
                    name: "QQ表情",
                    path: "/img/qq/",
                    maxNum: 91,
                    file: ".gif"
                }]
            });
JS;
        EmojiAsset::register($this->getView());
        $this->getView()->registerJs($js);
    }
}
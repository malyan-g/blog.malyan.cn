<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 17/11/3
 * Time: 下午5:46
 */

namespace app\components\widgets;


use app\assets\JqueryIsaAsset;
use yii\base\Widget;

class JqueryIsa extends Widget
{
    public function run()
    {
        $js = <<<JS
            var ias = jQuery.ias({container: ".media-list", item: "li.media", pagination: ".pagination", next: ".next a"});
            ias.extension(new IASSpinnerExtension());
            ias.extension(new IASNoneLeftExtension({text: ""}));
            ias.extension(new IASPagingExtension());
JS;
        JqueryIsaAsset::register($this->getView());
        $this->getView()->registerJs($js);
    }
}
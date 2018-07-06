<?php
/**
 * Emoji
 *
 *@package vendor.tangniyuqi.yii2-emoji
 *@author tangming <tangniyuqi@163.com>
 *@copyright DNA <http://www.Noooya.com/>
 *@version 1.0.0
 *@since 2017-05-18 Create
 *@todo N/A
 */
namespace tangniyuqi\emoji;

class Emoji
{
    /**
     * @inheritdoc
     */
	public static function toHtml($str)
    {
        $str =json_encode($str);
        $str = preg_replace_callback("#(\\\ue[0-9a-f]{3})#i", function($matches) {
             return addslashes($matches[0]);
        }, $str);

        $str = json_decode($str);
        $maps = static::maps();

        return str_replace(array_keys($maps), $maps, $str);
	}

    /**
     * @inheritdoc
     */
	public static function maps()
    {
        $maps = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'emoji_code.json';

		return json_decode(file_get_contents($maps), true);
	}
}
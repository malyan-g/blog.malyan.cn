## Install

add `tangniyuqi/yii2-emoji` to composer.json

```sh
$ composer install
```

```
$ php composer.phar require tangniyuqi/yii2-emoji "*"
```

or add

```
"tangniyuqi/yii2-emoji": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

```
use tangniyuqi\emoji;
use tangniyuqi\emoji\EmojiAsset;

$face = emoji::toHtml($str);

EmojiAsset::register($this->getView());

public $depends = [
    'yii\web\YiiAsset',
    .......code.........
    'yangshihe\emoji\EmojiAsset'
];
```
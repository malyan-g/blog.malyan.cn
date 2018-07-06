<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\components\widgets\Nav;
use app\models\User;
use app\components\helpers\PassportHelper;

AppAsset::register($this);
$user = Yii::$app->user->identity;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <!-- 头部 -->
    <?php
    NavBar::begin([
        'brandLabel' => 'My Blog',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => \app\models\Nav::items()
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => '注册',
                'url' => PassportHelper::registerRoute(),
                'visible' => !$user
            ],
            [
                'label' => '登录',
                'url' => PassportHelper::loginRoute(),
                'visible' => !$user
            ],
            [
                'label' => $user ? Html::img(User::getAvatar($user['id']), ['width' => 20, 'height' => 20]) . ' '.Html::encode($user['nickname']) : '',
                'items' => [
                    ['label' => '个人中心', 'url' => ['user/change-password']],
                    '<li class="divider"></li>',
                    ['label' => '退出', 'url' => PassportHelper::logoutRoute()],
                ],
                'encode' => false,
                'visible' => !empty($user)
            ]
        ]
    ]);

    echo Html::beginForm(['article/search'], 'get', ['class' => 'navbar-form visible-lg-inline-block navbar-right'])
        . '<div class="input-group">'
        . Html::textInput('keywords', Yii::$app->request->get('keywords'), ['class' => 'form-control', 'placeholder' => '搜索'])
        . '<span class="input-group-btn">'
        . Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class' => 'btn btn-default'])
        . '</span></div>'
        . Html::endForm();
    NavBar::end();
    ?>

    <!-- 内容 -->
    <div class="container">
        <div>
            <marquee behavior="scroll" direction="" style="background: #ffffff; color: red;">
                <p style="padding: 0 6px;">本站点带宽为1M，所以加载比较慢！</p>
            </marquee>
        </div>
        <?= $content ?>
    </div>
</div>

<!--  尾部 -->
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right">
            技术支持
            <a href="/" rel="external">Malyan</a>
        </p>
    </div>
</footer>
<!-- 返回顶部 -->
<?= \bluezed\scrollTop\ScrollTop::widget() ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
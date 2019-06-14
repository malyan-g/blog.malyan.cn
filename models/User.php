<?php

namespace app\models;

use app\components\helpers\RedisHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $nickname
 * @property string $avatar
 * @property integer $status
 * @property integer $created_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * 正常
     */
    const STATUS_ACTIVE = 1;
    /**
     * 禁用
     */
    const STATUS_DISABLE = 2;

    /**
     * 聊天redis键
     */
    const CHAT_NICKNAME = 'chat.nickname.';

    public static $nicknameData = [
        1 => '【 天魁星 】呼保义-宋江',
        2 => '【 天罡星 】玉麒麟-卢俊义',
        3 => '【 天机星 】智多星-吴用',
        4 => '【 天闲星 】入云龙-公孙胜',
        5 => '【 天勇星 】大刀-关胜',
        6 => '【 天雄星 】豹子头-林冲 ',
        7 => '【 天猛星 】霹雳火-秦明',
        8 => '【 天威星 】双鞭-呼延灼 ',
        9 => '【 天英星 】小李广-花荣',
        10 => '【 天贵星 】小旋风-柴进',
        11 => '【 天富星 】扑天雕-李应',
        12 => '【 天満星 】美髯公-朱仝',
        13 => '【 天孤星 】花和尚-鲁智深',
        14 => '【 天伤星 】行者-武松',
        15 => '【 天立星 】双枪将-董平',
        16 => '【 天捷星 】没羽箭-张清 ',
        17 => '【 天暗星 】青面兽-杨志 ',
        18 => '【 天佑星 】金枪手-徐宁 ',
        19 => '【 天空星 】急先锋-索超 ',
        20 => '【 天速星 】神行太保-戴宗 ',
        21 => '【 天异星 】赤发鬼-刘唐 ',
        22 => '【 天杀星 】黒旋风-李逵 ',
        23 => '【 天微星 】九纹龙-史进 ',
        24 => '【 天究星 】没遮拦-穆弘 ',
        25 => '【 天退星 】插翅虎-雷横 ',
        26 => '【 天寿星 】混江龙-李俊 ',
        27 => '【 天剑星 】立地太岁-阮小二',
        28 => '【 天平星 】船火儿-张横 ',
        29 => '【 天罪星 】短命二郎-阮小五',
        30 => '【 天损星 】浪里白跳-张顺 ',
        31 => '【 天败星 】活阎罗-阮小七 ',
        32 => '【 天牢星 】病关索-杨雄 ',
        33 => '【 天慧星 】拼命三郎-石秀 ',
        34 => '【 天暴星 】两头蛇-解珍 ',
        35 => '【 天哭星 】双尾蝎-解宝 ',
        36 => '【 天巧星 】浪子-燕青 ',
        37 => '【 地魁星 】神机军师-朱武 ',
        38 => '【 地煞星 】镇三山-黄信 ',
        39 => '【 地勇星 】病尉迟-孙立 ',
        40 => '【 地杰星 】丑郡马-宣赞 ',
        41 => '【 地雄星 】井木犴-郝思文 ',
        42 => '【 地威星 】百胜将-韩滔 ',
        43 => '【 地英星 】天目将-彭玘 ',
        44 => '【 地奇星 】圣水将-单廷圭 ',
        45 => '【 地猛星 】神火将-魏定国 ',
        46 => '【 地文星 】圣手书生-萧让 ',
        47 => '【 地正星 】铁面孔目-裴宣 ',
        48 => '【 地阔星 】摩云金翅-欧鹏 ',
        49 => '【 地阖星 】火眼狻猊-邓飞 ',
        50 => '【 地强星 】锦毛虎-燕顺 ',
        51 => '【 地暗星 】锦豹子-杨林 ',
        52 => '【 地轴星 】轰天雷-凌振',
        53 => '【 地会星 】神算子-蒋敬',
        54 => '【 地佐星 】小温侯-吕方',
        55 => '【 地佑星 】赛仁贵-郭盛 ',
        56 => '【 地灵星 】神医-安道全 ',
        57 => '【 地兽星 】紫髯伯-皇甫端 ',
        58 => '【 地微星 】矮脚虎-王英 ',
        59 => '【 地慧星 】一丈青-扈三娘 ',
        60 => '【 地暴星 】丧门神-鲍旭 ',
        61 => '【 地然星 】混世魔王-樊瑞 ',
        62 => '【 地猖星 】毛头星-孔明 ',
        63 => '【 地狂星 】独火星-孔亮 ',
        64 => '【 地飞星 】八臂哪吒-项充 ',
        65 => '【 地走星 】飞天大圣-李衮',
        66 => '【 地巧星 】玉臂匠-金大坚 ',
        67 => '【 地明星 】铁笛仙-马麟 ',
        68 => '【 地进星 】出洞蛟-童威 ',
        69 => '【 地退星 】翻江蜃-童猛 ',
        70 => '【 地满星 】玉幡竿-孟康 ',
        71 => '【 地遂星 】通臂猿-侯健 ',
        72 => '【 地周星 】跳涧虎-陈达 ',
        73 => '【 地隐星 】白花蛇-杨春',
        74 => '【 地异星 】白面郎君-郑天寿',
        75 => '【 地理星 】九尾亀-陶宗旺 ',
        76 => '【 地俊星 】铁扇子-宋清 ',
        77 => '【 地乐星 】铁叫子-乐和 ',
        78 => '【 地捷星 】花项虎-龚旺 ',
        79 => '【 地速星 】中箭虎-丁得孙 ',
        80 => '【 地镇星 】小遮拦-穆春',
        81 => '【 地羁星 】操刀鬼-曹正 ',
        82 => '【 地魔星 】云里金刚-宋万 ',
        83 => '【 地妖星 】摸着天-杜迁 ',
        84 => '【 地幽星 】病大虫-薛永 ',
        85 => '【 地僻星 】打虎将-李忠 ',
        86 => '【 地空星 】小霸王-周通',
        87 => '【 地孤星 】金钱豹子-汤隆 ',
        88 => '【 地全星 】鬼脸儿-杜兴 ',
        89 => '【 地短星 】出林龙-邹渊 ',
        90 => '【 地角星 】独角龙-邹润 ',
        91 => '【 地囚星 】旱地忽律-朱贵 ',
        92 => '【 地蔵星 】笑面虎-朱富 ',
        93 => '【 地伏星 】金眼彪-施恩 ',
        94 => '【 地平星 】鉄臂膊-蔡福 ',
        95 => '【 地损星 】一枝花-蔡庆',
        96 => '【 地奴星 】催命判官-李立 ',
        97 => '【 地察星 】青眼虎-李云 ',
        98 => '【 地悪星 】没面目-焦挺 ',
        99 => '【 地丑星 】石将军-石勇 ',
        100 => '【 地数星 】小尉遅-孙新',
        101 => '【 地阴星 】母大虫-顾大嫂 ',
        102 => '【 地刑星 】菜园子-张青 ',
        103 => '【 地壮星 】母夜叉-孙二娘',
        104 => '【 地劣星 】活闪婆-王定六 ',
        105 => '【 地健星 】険道神-郁保四 ',
        106 => '【 地耗星 】白日鼠-白胜',
        107 => '【 地贼星 】鼓上蚤-时迁 ',
        108 => '【 地狗星 】金毛犬-段景住'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'nickname'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['nickname'], 'string', 'min' => 2, 'max' => 12],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '邮箱',
            'auth_key' => '身份验证码',
            'password_hash' => '密码',
            'nickname' => '昵称',
            'avatar' => '头像',
            'status' => '状态 1正常 2禁用',
            'created_at' => '创建时间'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getAvatar($userId)
    {
        return 'http://image.malyan.cn/avatars/' . (crc32($userId) % 10) . '.jpg';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => [self::STATUS_ACTIVE, self::STATUS_DISABLE]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 获取昵称
     * @return mixed
     */
    public static function getNickname()
    {
        $key = array_rand(self::$nicknameData, 1);
        $redisHelper = RedisHelper::getInstance();
        if($redisHelper->sIsMembers(self::CHAT_NICKNAME , $key)){
            return self::getNickname();
        }else{
            $redisHelper->sAdd(self::CHAT_NICKNAME , $key);
            return self::$nicknameData[$key];
        }
    }
}

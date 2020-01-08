<?php

namespace app\common\lib;
class Redis
{
    /**
     * 验证码 redis key 前缀
     * @var string
     */
    public static $pre = 'sms_';

    /**
     * 用户user key
     * @var string
     */
    public static $usrpre = 'user_';

    /**
     * 存储验证码redis key
     * @param $phone
     * @return string
     */
    public static function SmsKey($phone){
        return self::$pre.$phone;
    }

    /** 用户key
     * @param $phone
     * @return string
     */
    public static function UserKey($phone){
        return self::$usrpre.$phone;
    }
}
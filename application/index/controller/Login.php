<?php
namespace app\index\controller;

use app\common\lib\Predis;
use app\common\lib\Redis;
use app\common\lib\Util;

class Login
{
    public function index(){
        //get message
        $phone_num = input('post.phone_num', 1, 'intval');
        $code = input('post.code',1, 'intval');
        if(empty($phone_num) || empty($code)){
            return Util::getStatus(config('code.error'), 'phone or code is error');
        }
        //redis code
        try{
            $redisCode = Predis::getInstance()->get(Redis::SmsKey($phone_num));
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        //登录
        if($redisCode == $code){
            //写入redis
            $data = [
                'user' => $phone_num,
                'srcKey' => md5(Redis::UserKey($phone_num)),
                'time' => time(),
                'isLogin' => true,
                'expired'  => $_SERVER['REQUEST_TIME'] + 20 * 60, // 20分钟
            ];
            //登录判断用: Base\construct
            session('verifi:'.$phone_num, $data);
            define('__USER__',$phone_num);
            $res = Predis::getInstance()->set(Redis::UserKey($phone_num), $data);
            if($res){
                return Util::getStatus(config('code.success'), '登录成功', $data);
            }else{
                return Util::getStatus(config('code.error'), '登录失败');
            }
        }

    }

}

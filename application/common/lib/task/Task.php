<?php
namespace app\common\lib\task;
use app\common\lib\ali\Sms;
use app\common\lib\Predis;
use app\common\lib\Redis;
use think\Db;

/**
 * swoole所有task任务
 * Class Task
 * @package app\common\task
 */
class Task
{
    /**
     * 异步发送短信验证码
     * @param $data
     * @param $serv swoole server对象
     * @return bool
     */
    public function sendSms($data, $serv){
        //发送验证码
        try{
            $response = Sms::sendSms($data['phone'], $data['code']);
        }catch (\Exception $e){
            return false;
        }
        //存储到redis
        if($response->code === 'ok'){
            Predis::getInstance()->set(Redis::SmsKey($phone_num), $code , config('redis.out_time'));
            return Util::getStatus(config('code.success'), '短信发送成功');
        }
        return true;
    }

    /** 发送赛况信息
     * @param $data
     * $serv swoole server对象
     * @return bool
     */
    public function pushLive($data, $serv) {
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
        foreach($clients as $fd) {
            $serv->push($fd,json_encode($data));
        }
        return true;
    }

    public function memLive($data, $serv){
        $res = Db::name('outs')->insert($data);
        if($res){
            return true;
        }
    }
}
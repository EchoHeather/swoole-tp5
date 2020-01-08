<?php
namespace app\index\controller;
use app\common\lib\Util;
use app\common\lib\ali\Sms;
use app\common\lib\redis;

class Send
{
    public function index(){
        $phone_num = input('get.phone_num', 0, 'intval');
        if(empty($phone_num)){
            return Util::getStatus(config('code.error'),'手机号为空');
        }
        $code =rand(1000,9999);
        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phone_num,
                'code' => $code
            ]
        ];
        //异步task任务
        $_POST['http_server']->task($taskData);
//        try{
//            $response = Sms::sendSms($phone_num, $code);
//        }catch (\Exception $e){
//            return Util::getStatus(config('code.error'), '阿里大于内部异常');
//        }
//        if($response->code === 'ok'){
//            $redis = new Swoole\Coroutine\Redis();
//            $redis->connect('127.0.0.1',6379);
//            $redis->set(Redis::SmsKey($phone_num), $code , config('redis.out_time'));
            return Util::getStatus(config('code.success'), '短信发送成功');
//        }else{
//            return Util::getStatus(config('code.error'),'短信发送失败');
//        }
    }
}

<?php

namespace app\common\lib;
class Predis
{
    public $redis = "";
    private static $_instance = null;

    /**
     * 单例模式如果实例化redis则直接返回obj
     * @return Predis|null
     */
    public static function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 自动调用redis
     * @throws \Exception
     */
    private function __construct(){
        $this->redis = new \Redis();
        $res = $this->redis->connect(config('redis.host'), config('redis.port'),config('redis.time_out'));
        if($res == false){
            throw new \Exception('redis connect error');
        }
    }

    /**
     * redis set
     * @param $key
     * @param $val
     * @param int $time
     * @return bool|string
     */
    public function set($key, $val, $time = 0){
        if(!$key){
            return '';
        }
        if(is_array($val)){
            $val = json_encode($val);
        }
        if(!$time){
            return $this->redis->set($key, $val);
        }else{
            return $this->redis->setex($key ,$time, $val);
        }
    }

    /**
     * redis get
     * @param $key
     * @return bool|string
     */
//    public function get($key){
//        if(!$key){
//            return '';
//        }
//        return $this->redis->get($key);
//    }

    /** redis sadd
     * @param $k
     * @param $v
     * @return mixed
     */
//    public function sAdd($k, $v) {
//        return $this->redis->sAdd($k, $v);
//    }

    /** redis srem
     * @param $k
     * @param $id
     * @return mixed
     */
//    public function sRem($k, $id) {
//        return $this->redis->sRem($k, $id);
//    }

    /** redis sMembers
     * @param $k
     * @return array
     */
//    public function sMembers($k) {
//        return $this->redis->sMembers($k);
//    }

    /** 匿名函数
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        switch(count($arguments)) {
            case 1:
                return $this->redis->$name($arguments[0]);
                break;
            case 2:
                return $this->redis->$name($arguments[0], $arguments[1]);
                break;
        }
    }
}

<?php
namespace app\index\controller;

use app\common\lib\Util;

class Chart extends Base
{
    //调用父类魔术方法
//    public function __construct(){
//        parent::__construct();
//    }

    public function index()
    {
        $game_id = $_POST['game_id'];
        $content = $_POST['content'];
        if(empty($game_id) || empty($content)) {
            return Util::getStatus(config('code.error'), 'error');
        }
        $data = [
            'user' => "用户".rand(0, 2000), //模拟用户id
            'content' => $content
        ];
        foreach($_POST['http_server']->ports[1]->connections as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }
        return Util::getStatus(config('code.success'), 'ok', $data);
    }


}

<?php
namespace app\admin\controller;

use app\common\lib\Predis;
use app\common\lib\Util;

class Live
{
    /**
     * @return string
     */
    public function push() {
        //查询球队信息
        $model = new \app\model\Live();
        $teams = $this->convert_arr_key($model->index(), 'id');
        if( empty($_POST) ) {
            return Util::getStatus(config('code.error'), '数据为空');
        }
        //组装信息
        $data = [
            'type' => intval($_POST['type']),
            'title' => !empty($teams[$_POST['team_id']]['name'])?$teams[$_POST['team_id']]['name']:'直播员',
            'logo' => !empty($teams[$_POST['team_id']]['image'])?$teams[$_POST['team_id']]['image']:'',
            'content' => !empty($_POST['content'])?$_POST['content']:'',
            'image' => !empty($_POST['image'])?$_POST['image']:'',
            'time' => date('H:i:s'),
            'lave' => '05:10' //剩余时间
        ];
        $taskPushData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $taskMemData = [
            'method' => 'memLive',
            'data' => [
                'game_id' => 1,
                'team_id' => !empty($_POST['team_id'])?$_POST['team_id'] : 0,
                'content' => !empty($_POST['content'])?$_POST['content']:'',
                'image' => !empty($_POST['image'])?$_POST['image']:'',
                'type' => intval($_POST['type']),
                'create_time' => time()
            ]
        ];
        //异步task任务 给所有客户端发送信息
        $_POST['http_server']->task($taskPushData);
        //将消息存入mysql
        $_POST['http_server']->task($taskMemData);
        return Util::getStatus(config('code.success'), '发送成功');
    }



    /**
     * 二维数组以$key_name为键名
     * @param $arr
     * @param $key_name
     * @return array
     */
    public function convert_arr_key($arr, $key_name)
    {
        $result = array();
        foreach($arr as $key => $val){
            $result[$val[$key_name]] = $val;
        }
        return $result;
    }
}
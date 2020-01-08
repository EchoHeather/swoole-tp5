<?php
class Http
{
    const HOST = '0.0.0.0';
    const PORT = 8811;
    public $http = null;


    public function __construct(){
        $this->http = new swoole_http_server(self::HOST, self::PORT);
        $this->http->set([
            "worker_num"=>4,
            "task_worker_num"=>2,
            "enable_static_handler"=> true,
            "document_root" => "/home/www/thinkphp/public/static",
        ]);
        $this->http->on('WorkerStart',[$this,'onWorkerStart']);
        $this->http->on('request',[$this,'onRequest']);
        $this->http->on('task',[$this,"onTask"]);
        $this->http->on('finish',[$this,"onFinish"]);
        $this->http->on('close',[$this,'onClose']);
        $this->http->start();
    }

    /** WorkerStart
     * @param $serv
     * @param $worker_id
     */
    public function onWorkerStart($serv, $worker_id){
        //引入thinkPHP入口文件
        define('APP_PATH', __DIR__ . '/../application/');
//        require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';
    }

    /** Request
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response){
        $_POST['http_server'] = $this->http;
        $_SERVER = [];
        if(isset($request->header)){
            foreach($request->header as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->server)){
            foreach($request->server as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)){
            foreach($request->post as $k => $v){
                $_POST[$k] = $v;
            }
        }
        $_GET = [];
        if(isset($request->get)){
            foreach($request->get as $k => $v){
                $_GET[$k] = $v;
            }
        }
//    $_COOKIE = [];
//    if(isset($request->cookie)){
//        foreach($request->cookier as $k => $v){
//            $_COOKIE[$k] = $v;
//        }
//    }
        // 执行应用并响应thinkphp
        ob_start();
        try {
            think\Container::get('app', [ APP_PATH ])
                ->run()
                ->send();
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    /** 异步task任务
     * @param $server
     * @param $task_id
     * @param $worker_id
     * @param $data
     * @return string
     */
    public function onTask($server, $task_id, $worker_id, $data){
        //工厂模式分发task任务
        $obj = new app\common\lib\task\Task();
        if(!empty($data)){
            $method = $data['method'];
            $res = $obj->$method($data['data']);
            return $res;
        }
        return true;
    }

    /** 将处理结果告诉worker进程
     * @param $server
     * @param $task_id
     * @param $data
     */
    public function onFinish($server, $task_id, $data){
        echo "taskId:$task_id\n";
        echo "finish-data-success:$data\n";
    }

    /**关闭客户端触发事件
     * @param $server
     * @param $fd
     */
    public function onClose($server,$fd){
        echo "client is close: {$fd}\n";
    }
}


new Http();
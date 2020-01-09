<?php
class Ws
{
    const HOST = '0.0.0.0';
    const PORT = 8811;
    const CHART_PORT = 8812;
    public $ws = null;


    public function __construct(){
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);
        $this->ws->set([
            "worker_num"=>4,
            "task_worker_num"=>2,
            "enable_static_handler"=> true,
            "document_root" => "/home/www/thinkphp/public/static",
        ]);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('WorkerStart',[$this,'onWorkerStart']);
        $this->ws->on('request',[$this,'onRequest']);
        $this->ws->on('task',[$this,"onTask"]);
        $this->ws->on('finish',[$this,"onFinish"]);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
        //清空redis集合
//        $clients = \app\common\lib\Predis::getInstance()->sMembers('live_game_key');
//        if(count($clients) != 0) {
//            for($i = 0; $i<count($clients); $i++) {
//                \app\common\lib\Predis::getInstance()->sRem(config('redis.live_game_key') , $clients[$i]);
//            }
//        }
    }

    /**
     * 监听websocket连接事件
     * @param $server
     * @param $request
     */
    public function onOpen($server, $request){
        //todo 将连接的客户端fd存入redis
        \app\common\lib\Predis::getInstance()->sAdd(config('redis.live_game_key') , $request->fd);
        var_dump($request->fd);
    }

    /**
     * 接收客户端消息并返回
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame){
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd,"this is server");
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
        if($request->server['request_uri'] == "/favicon.ico") {
            $response->status("404");
            $response->end();
            return;
        }
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
        $_COOKIE = [];
        if(isset($request->cookie)){
            foreach($request->cookie as $k => $v){
                $_COOKIE[$k] = $v;
            }
        }
        $_FILES = [];
        if(isset($request->files)){
            foreach($request->files as $k => $v){
                $_FILES[$k] = $v;
            }
        }
//        $this->writeLog();
        $_POST['http_server'] = $this->ws;
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
            $res = $obj->$method($data['data'], $server);
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
        //todo redis删除有序集合里的fd
        \app\common\lib\Predis::getInstance()->sRem(config('redis.live_game_key') , $fd);
        echo "client is close: {$fd}\n";
    }

    /**
     * 写入日志
     */
    public function writeLog() {
        $datas = array_merge(['date'=> date('Y-m-d H:i:s')], $_GET, $_POST, $_SERVER);
        $logs = '';
        foreach ($datas as $k => $v) {
            $logs .= $k . ":" .$v . " ";
        }
        swoole_async_writefile(APP_PATH.'../runtime/log/'.date('Ym').'/'.date('d').'_access.log', $logs.PHP_EOL, function($filename){

        }, FILE_APPEND);
    }
}


new Ws();
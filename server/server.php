<?php

class Server
{
    const PORT = 8811;
    public function port(){
        $shell = "netstat -anp 2>/dev/null | grep ". self::PORT ." | grep LISTEN | wc -l";
        $res = shell_exec($shell);
        if($res != 1) {
            //todo : send SMS
            //....................

            echo date('Y-m-d H:i:s')." error".PHP_EOL;
        }else{
            echo date('Y-m-d H:i:s')." success".PHP_EOL;
        }
    }
}
(new Server())->port();
<?php

namespace app\common\lib;
class Util
{
    /** 返回信息
     * @param $status
     * @param string $msg
     * @param array $data
     * @return string
     */
    public static function getStatus($status, $msg='', $data=[]){
        $result = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];
        return json_encode($result);
    }
}
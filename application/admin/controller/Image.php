<?php
namespace app\admin\controller;

use app\common\lib\Util;

class Image
{
    public function index() {
        $file = request()->file('file');
        if( $file ){
            $info = $file->move('../public/static/upload');
            if($info) {
                $data = [
                    'image' => config('live.host').'/upload/'.$info->getSaveName()
                ];
                return Util::getStatus(config('code.success'),'ok', $data);
            }else {
                return Util::getStatus(config('code.error'),'ok');
            }
        }

    }
}
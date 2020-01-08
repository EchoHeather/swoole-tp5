<?php
namespace app\model;

use think\Db;
use think\Model;

class Live extends Model
{
    public function index(){
       return Db::name('team')->select();
    }
}
<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
       return '';
    }

    public function send()
    {
       print_r(date("Y-m-d H:i:s"));
    }
}

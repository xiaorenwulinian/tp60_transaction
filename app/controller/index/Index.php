<?php

namespace app\controller\index;


use think\facade\Db;
use think\facade\View;

class Index extends IndexBase
{
    public function index()
    {
      return \view('index/index/index');
    }



}

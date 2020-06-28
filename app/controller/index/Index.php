<?php

namespace app\controller\index;


use think\facade\Db;
use think\facade\View;

class Index extends IndexBase
{
    public function index()
    {
        $uid = session('user_id');
        if (empty($uid)) {
            $uid = 0;
            $userData = [];
        } else {
            $userData = \app\model\User::where("id", '=', $uid)->find();
        }

        $ret = [
            'userIndexData' => $userData,
            'uid' => $uid,
        ];

        return \view('index/index/index', $ret);
    }



}

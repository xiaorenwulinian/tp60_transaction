<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use app\service\CommonService;
use think\captcha\facade\Captcha;
use think\facade\Db;


class Test extends IndexBase
{

    public function index()
    {
        $commonService = new CommonService();
        $a = $commonService->getBaseConfigInfo();
        dd($a);
        return 'tt';
    }



}

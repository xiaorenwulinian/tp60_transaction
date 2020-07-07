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
//        dd($a);
        $goods = Db::table('goods')
            ->where('id','=',1)
            ->find();
        dump($goods);
        Db::table('goods')
            ->where('id','=',1)
            ->inc('has_sale_num',1)
            ->update();
        $goods = Db::table('goods')
            ->where('id','=',1)
            ->find();
        dump($goods);
        return 'tt';
    }



}

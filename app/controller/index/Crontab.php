<?php

namespace app\controller\index;


use app\service\CommonService;
use think\facade\Db;


class Crontab extends IndexBase
{

    /**
     * 自动收货
     * @return \think\response\Redirect
     */
    public function autoConfirmGoods()
    {
        $service = new CommonService();
        $baseConfig = $service->getBaseConfigInfo();
        $confirmGoodsDay = $baseConfig['auto_goods_day'];
        $date = date('Y-m-d 00:00:01',time() - $confirmGoodsDay * 3600 * 24);
        try {
            Db::table('order')
                ->where('order_progress' ,'<', 3)
                ->where('create_time','<',$date)
                ->update([
                    'order_progress' => 3,
                    'confirm_goods_time' => time(),
                ]);
            echo "ok";
        } catch (\Exception $exception) {
            echo "confirm_goods_error:". $exception->getMessage();
        }

    }


}

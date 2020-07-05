<?php

namespace app\controller\index;


use app\service\CommonService;
use app\service\OrderService;
use think\facade\Db;
use think\facade\Log;


class Crontab extends IndexBase
{

    /**
     * 自动收货
     * @return \think\response\Redirect
     */
    public function autoConfirmGoods()
    {
        set_time_limit(0);
        $service = new CommonService();
        $baseConfig = $service->getBaseConfigInfo();
        $confirmGoodsDay = $baseConfig['auto_goods_day'];
        $date = date('Y-m-d 00:00:01',time() - $confirmGoodsDay * 3600 * 24);


        $orderArr = Db::table('order')
            ->where('order_progress' ,'<', 3)
            ->where('create_time','<',$date)
            ->select()
            ->toArray();

        $orderService = new OrderService();
        foreach ($orderArr as $order) {
            $ret = $orderService->confirmReceiptGoods($order);
        }
        echo "ok";
    }


}

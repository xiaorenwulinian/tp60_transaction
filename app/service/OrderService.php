<?php
/**
 * Created by PhpStorm.
 * User: sogubaby
 * Date: 2020/7/2
 * Time: 下午4:01
 */

namespace app\service;


use think\facade\Db;
use think\facade\Log;

class OrderService
{


    /**
     * 确认收货
     * @param $order
     * @return bool
     */
    public function confirmReceiptGoods($order)
    {
        $ret = [
            'code' => 0,
            'mag'  => '',
        ];
        $curDate = date("Y-m-d H:i:s");
        try {

            Db::startTrans();
            Db::table('order')
                ->where('id' ,'=', $order['id'])
                ->update([
                    'order_progress' => 3,
                    'confirm_goods_time' => time(),
                ]);

            Db::table('user')
                ->where('id' ,'=', $order['sell_user_id'])
                ->inc('user_money',$order['order_money']);

            // 操作交易记录
            Db::table("transaction_record")
                ->insertGetId([
                    'user_id'           => $order['id'],
                    'operator_type'     => 1,
                    'money'             => $order['order_money'],
                    'transaction_type'  => 4,
                    'order_id'          => $order['sell_user_id'],
                    'create_time'       => $curDate,
                ]);
            Db::commit();
        } catch (\Exception $exception) {
            Db::rollback();

            $msg = "confirm_goods_error:". $exception->getMessage();
            $ret['code'] = 1;
            $ret['msg'] = $msg;

            Log::error($msg,$order);
        }
        return $ret;
    }
}
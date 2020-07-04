<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class Order extends IndexBase
{

    /**
     * 商品下单
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function buy()
    {
        $userId  = session('user_id');
        if (!$userId) {
            return failed_response('请先登陆！',401);
        }

        $goods_id = input('goods_id',0);

        $user = Db::table("user")->where('id', $userId)->find();
        $goods = Db::table("goods")->where('id', $goods_id)->find();

        if (!$goods) {
            return failed_response('该商品已下架！' . $goods_id);
        }

        if ($goods['publish_type'] == 1) {
            return failed_response("管理员发布商品，禁止购买！");
        }

        // 买家和卖家是同一人
        if ($goods['publish_id'] == $userId) {
            return failed_response("你是该商品的发布者，禁止购买！");
        }

        if ($user["user_money"] < $goods["goods_price"]) {
            return failed_response('账户余额不足，请先充值！', 100);
        }

        $remainMoney =  $user["user_money"] - $goods["goods_price"];

        $curDate = date("Y-m-d H:i:s");

        Db::startTrans();
        try {
            // 更改金额
            Db::table("user")
                ->where('id','=', $userId)
                ->update([
                    'user_money' => $remainMoney,
                    'update_time' => $curDate,
                ]);

            // 订单
            $orderId = Db::table("order")
                ->insertGetId([
                    'order_progress' => 1,
                    'buy_user_id'    => $userId,
                    'sell_user_id'   => $goods['publish_id'],
                    'goods_id'       => $goods['id'],
                    'order_money'    => $goods["goods_price"],
                    'create_time'    => $curDate,
                    'unique_code'    => date("YmdHis") . '-' . StringTool::random(8),
                ]);

            // 操作交易记录
            Db::table("transaction_record")
                ->insertGetId([
                    'user_id'           => $userId,
                    'operator_type'     => 1,
                    'money'             => $goods["goods_price"],
                    'transaction_type'  => 3,
                    'order_id'          => $orderId,
                    'create_time'       => $curDate,
                ]);

            Db::commit();
        } catch (\Exception $e) {

            Db::rollback();
            return failed_response($e->getMessage());
        }


        return success_response();
    }


    public function openVip()
    {
        if (!session('user_id')) {
            return failed_response('请先登陆！',401);
        }

        $userId   = session('user_id');
        $group_id = input('group_id',0);

        $user = Db::table("user")->where('id', $userId)->find();
        $level = Db::table("vip_level")->where('id', $group_id)->find();
        if (!$level) {
            return failed_response('非法攻击！');
        }


        if ($user["user_money"] < $level['vip_fee']) {
            return failed_response('账户余额不足，请先充值！', 100);
        }
        $remainMoney =  $user["user_money"] - $level['vip_fee'] ;

        $maxtime = max(time(), $user['vip_deadline']);
        $newDeadline = $maxtime + 24 * 3600 * $level['vip_delay_day'];

        $curDate = date("Y-m-d H:i:s");

        Db::startTrans();

        try {
            // 更改金额
            Db::table("user")
                ->where('id','=', $userId)
                ->update([
                    'vip_level_id' => 1,
                    'user_money'   => $remainMoney,
                    'vip_deadline' => $newDeadline,
                    'update_time'  => $curDate,
                ]);

            // 订单
//            $orderId = Db::table("order")
//                ->insertGetId([
//                    'order_progress' => 1,
//                    'buy_user_id'    => $userId,
//                    'order_money'    => $goods["goods_price"],
//                    'create_time'    => $curDate,
//                    'unique_code'    => date("ymdHis") . '-' . StringTool::random(6),
//                ]);

            // 操作交易记录
            Db::table("transaction_record")
                ->insertGetId([
                    'user_id'           => $userId,
                    'operator_type'     => 1,
                    'money'             => $level['vip_fee'],
                    'transaction_type'  => 5,
                    'order_id'          => 0,
                    'create_time'       => $curDate,
                ]);

            Db::commit();
        } catch (\Exception $e) {

            Db::rollback();
            return failed_response($e->getMessage());
        }


        return success_response();

    }

    public function index()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', session('user_id'))->find();
        $vipLevel = Db::table("vip_level")
            ->where('is_use','=',1)
            ->order('sort_order')
            ->select();

        $ret = [
            'user' => $user,
            'vipLevel' => $vipLevel,
        ];

        return view("index/user/index", $ret);
    }

    /**
     * 充值
     */
    public function topUp()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }
        return view("index/user/top_up");
    }

    /**
     * 提现
     */
    public function withdraw()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }
        return view("index/user/withdraw");
    }

}

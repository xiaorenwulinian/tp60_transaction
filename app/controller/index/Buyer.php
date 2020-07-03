<?php

namespace app\controller\index;


use think\facade\Db;


class Buyer extends IndexBase
{

    /**
     * 买家订单列表
     * @return \think\response\Redirect
     */
    public function OrderIndex()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', $userId)->find();

        $orderInfo = Db::table('order')
            ->field([
                'order.*',
                'goods.goods_name',
                'goods.goods_img',
            ])
            ->leftJoin("goods",'goods.id=order.goods_id')
            ->where('order.buy_user_id',$userId)
            ->select()
            ->toArray();

//        dd($orderInfo);
        $ret = [
            'user' => $user,
            'orderInfo' => $orderInfo,
        ];

        return view("index/buyer/order_index", $ret);

    }

    public function orderChat()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $orderId = $this->request->param('order_id');
        $goodsId = $this->request->param('goods_id');

        $orderInfo = Db::table('order')
           ->where('id',$orderId)
            ->find();

        if ($goodsId != $orderInfo['goods_id']) {
            return failed_response("非法攻击");
        }

        $goodsInfo = Db::table('goods')
            ->where('id',$orderInfo['goods_id'])
            ->find();

       


        dd($orderInfo,$goodsInfo);

        
    }



}

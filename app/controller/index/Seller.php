<?php

namespace app\controller\index;


use think\facade\Db;


class Seller extends IndexBase
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
            ->where('order.sell_user_id',$userId)
            ->select()
            ->toArray();

        $ret = [
            'user' => $user,
            'orderInfo' => $orderInfo,
        ];

        return view("index/seller/order_index", $ret);

    }

    public function orderChatIndex()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', $userId)->find();

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


        $chatInfo = Db::table("order_chat")
            ->where([
                'order_id' => $orderId,
            ])
            ->order('id','desc')
            ->select()
            ->toArray();

        $ret = [
            'user' => $user,
            'chatInfo' => $chatInfo,
            'orderId' => $orderId,
        ];
//        dump($orderInfo,$goodsInfo,$chatInfo, $orderId);


        return view("index/seller/order_chat_index", $ret);



        
    }

    public function orderChatAdd()
    {
        $userId = session('user_id');
        if (!$userId) {
            return failed_response("请先登陆",401);

        }

        $orderId = $this->request->param('order_id');
        $chatContent = $this->request->param('chat_content');

        $orderInfo = Db::table('order')
            ->where('id',$orderId)
            ->find();

        if (!$orderInfo) {
            return failed_response("非法攻击");
        }



        $chatInfo = Db::table("order_chat")
            ->insert([
                'order_id' => $orderId,
                'user_id' => $userId,
                'chat_content' => $chatContent,
                'user_type' => 2,
                'create_time' => date("Y-m-d H:i:s"),
            ]);

        return success_response();




    }



}

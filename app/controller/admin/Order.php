<?php

namespace app\controller\admin;


use app\model\GoodsIntroduceImg;
use think\exception\ValidateException;
use think\facade\Db;

class Order extends AdminBase
{
    /**
     * 列表
     * @return \think\response\View
     */
    public function lst()
    {
        $pageSize = input('page_size', 10); // 每一页默认显示的条数
//        $curPage = input('page',1);
        $pageSizeSelect = page_size_select($pageSize); //生成下拉选择页数框


        $data = Db::table('order')
            ->order('id','desc')
            ->paginate([
                'query'     => request()->param(),
                'list_rows' => $pageSize, //每页数量
            ],false);

        $newData = [];
        $userIdArr = [];
        $goodsIdArr = [];
        foreach ($data as $v) {
            $userIdArr[] = $v['buy_user_id'];
            $userIdArr[] = $v['sell_user_id'];

            $goodsIdArr[] = $v['goods_id'];
            $newData[] = $v;
        }

        $userIdArr = array_unique($userIdArr);
        $userInfo = Db::table('user')
            ->whereIn('id',$userIdArr)
            ->column('account','id');

        $goodsInfo = Db::table('goods')
            ->whereIn('id',$goodsIdArr)
            ->column('*','id');

        foreach ($newData as &$v) {
            $v['buy_username'] = $userInfo[$v['buy_user_id']];
            $v['sell_username'] = $userInfo[$v['sell_user_id']];
            $v['goods_info'] = $goodsInfo[$v['goods_id']];
        }
//        dd($newData,$userInfo,$goodsIdArr,$goodsInfo);


        $pageShow = $data->render();
//        dd($data,$pageShow);


        $ret = [
            'data'           => $newData,
            'pageSizeSelect' => $pageSizeSelect,
            'pageSize'       => $pageSize,
            'pageShow'       => $pageShow,
        ];
        return view('admin/order/lst', $ret);
    }

    public function chatLst()
    {
        $orderId = input('order_id');

        $ret = [
            'orderId' => $orderId
        ];
        return view('admin/order/chat_lst', $ret);
    }

    public function chatLstMore()
    {
        $orderId = $this->request->param('order_id');

        $orderInfo = Db::table('order')
            ->where('id',$orderId)
            ->find();

        $pageSize = input('page_size',10);
        $curPage  = input('cur_page',1);
        $offset = ($curPage - 1) * $pageSize;


        $userBuySell = Db::table('user')
            ->whereIn('id',[$orderInfo['buy_user_id'],$orderInfo['sell_user_id']])
            ->column('account','id');

        $chatInfo = Db::table("order_chat")
            ->where([
                'order_id' => $orderId,
            ])
            ->order('id','desc')
            ->limit($offset,$pageSize)
            ->select()
            ->toArray();

        foreach ($chatInfo as &$v) {
            if ($v['user_id'] == $orderInfo['buy_user_id']) {
                $v['add_username'] = $userBuySell[$v['user_id']] . "(买家)";
                $v['position'] = 1; // ，买家
            } else {
                $v['add_username'] = $userBuySell[$v['user_id']] . "(卖家)";
                $v['position'] = 2; // 卖家
            }

        }


        $ret = [
            'chatInfo' => $chatInfo,
            'orderId' => $orderId,
        ];

        return success_response($ret);
    }



}

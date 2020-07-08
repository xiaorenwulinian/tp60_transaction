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

    public function orderChat()
    {

    }



}

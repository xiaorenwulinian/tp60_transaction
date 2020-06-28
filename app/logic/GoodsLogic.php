<?php


namespace app\logic;


use app\model\Goods;

class GoodsLogic
{
    public function goodsSearch()
    {
        $curPage = input('curPage', 1);
        $pageSize = input('pageSize',2);
        $order = input('orderBy',1);
        switch ($order) {
            case 1:
                $orderBy = 'sort_id';
                $direction = 'asc';
                break;
            case 2:
                $orderBy = 'discount_price';
                $direction = 'asc';
                break;
            case 3:
                $orderBy = 'discount_price';
                $direction = 'desc';
                break;
            default:
                $orderBy = 'id';
                $direction = 'desc';
        }
        $offset = ($curPage - 1) * $pageSize;
        $where = [];
        $where[] = ['is_delete', '=', 1];
        $goodsName = input('goods_name', '');
        if (!empty($goodsName)) {
            $where[] = ['goods_name', 'like', "%{$goodsName}%"];
        }
        $data = Goods::where($where)
            ->withAttr('goods_img', function ($value, $data) {
            return 'http://192.168.0.200:8899/uploads/' . $value;
        })
            ->order($orderBy, $direction)
            ->limit($offset, $pageSize)
            ->select();
        return $data;
    }
}

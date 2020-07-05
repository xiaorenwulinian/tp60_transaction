<?php

namespace app\controller\admin;


use app\model\GoodsIntroduceImg;
use think\exception\ValidateException;
use think\facade\Db;

class FriendChat extends AdminBase
{
    /**
     * 聊天室列表
     * @return \think\response\View
     */
    public function houseLst()
    {
        $pageSize = input('page_size', 10); // 每一页默认显示的条数
        $pageSizeSelect = page_size_select($pageSize); //生成下拉选择页数框
//        $data = \app\model\Goods::search($pageSize);

        $where = [];
//        $where[] = ['is_delete', '=', 1];
//        $goodsName = input('goods_name', '');
//
//        if (!empty($goodsName)) {
//            $where[] = ['goods_name', 'like', "%{$goodsName}%"];
//        }
//
//        $categoryId = input('goods_category_id', '');
//        if (!empty($categoryId)) {
//            $where[] = ['goods_category_id', '=', "$categoryId"];
//        }


        $data = Db::table('chat_house')
            ->where($where)
            ->order('last_chat_time','desc')
            ->paginate($pageSize)
            ->each(function ($item, $key) {
                $userInfo = Db::table('user')
                    ->whereIn('id',[$item['min_user_id'],$item['max_user_id']])
                    ->column('account','id');
                $item['min_user_name'] = $userInfo[$item['min_user_id']];
                $item['max_user_name'] = $userInfo[$item['max_user_id']];

                return $item;
//                dd($userInfo,$item);
            });

//        dd($data);
        $pageShow = $data->render();


        $ret = [
            'data'           => $data,
            'pageSizeSelect' => $pageSizeSelect,
            'pageSize'       => $pageSize,
            'pageShow'       => $pageShow,

        ];

        return view('admin/friend_chat/house_lst', $ret);
    }

    /**
     * 聊天列表
     */
    public function chatLst()
    {

        return view('admin/friend_chat/chat_lst');
    }


    public function chatLstAjax()
    {
        $houseId = input('chat_house_id', '');


        $pageSize = input('page_size',10);
        $curPage  = input('cur_page',1);
        $offset = ($curPage - 1) * $pageSize;

        $chatInfo = Db::table('friend_chat')
            ->where('chat_house_id',$houseId)
            ->order('id','desc')
            ->limit($offset,$pageSize)
            ->select()
            ->toArray();

        $house = Db::table("chat_house")
            ->where('id',$houseId)
            ->order('last_chat_time','desc')
            ->find();

        $linkUserInfo = Db::table('user')
            ->whereIn('id',[$house['min_user_id'], $house['max_user_id']])
            ->column('account','id');


        foreach ($chatInfo as &$v) {
            if ($v['user_type'] == 2) {
                $v['username'] = "管理员";
                $v['position'] = 2;
            } else {
                $v['username'] = $linkUserInfo[$v['user_id']];
                $v['position'] = 1;
            }
        }

        $ret = [
            'house' => $house,
            'linkUserInfo' => $linkUserInfo,
            'chatInfo' => $chatInfo,
        ];

        return success_response($ret);
    }


    /**
     * 添加
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function chatAdd()
    {
        $adminId = session('admin_user_id');
        if (empty($adminId)) {
            return failed_response("刷新登录");
        }

        $houseId = input('chat_house_id',0);
        $chatContent = input('chat_content','');

        $houseInfo = Db::table('chat_house')
            ->where('id',$houseId)
            ->find();

        if (!$houseInfo) {
            return failed_response("非法攻击");
        }
        Db::table("friend_chat")
            ->insertGetId([
                'user_id'       => $adminId,
                'chat_content'  => $chatContent,
                'chat_house_id' => $houseId,
                'user_type'     => 2,
                'is_read'       => 1,
                'create_time'   => date("Y-m-d H:i:s"),
            ]);

        $houseInfo = Db::table('chat_house')
            ->where('id',$houseId)
            ->update([
                'last_chat_time' => time()
            ]);

        return success_response();
    }


}

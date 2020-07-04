<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class FriendChat extends IndexBase
{

    public function index()
    {
        $userId = session('user_id');
//        $userId = 1;
        if (!$userId) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', $userId)->find();


        $whereRaw = " FIND_IN_SET('{$userId}', chat_relation) ";
        $house = Db::table("chat_house")
            ->whereRaw($whereRaw)
            ->order('last_chat_time','desc')
            ->column('min_user_id,max_user_id,chat_relation,id','id');
//            ->select();
        $linkUserIdArr = [];
        $firstHouse = [];
        $i = 0;
        foreach ($house as $k => $v) {
            if ($v['min_user_id'] != $userId) {
                $linkUserIdArr[] = $v['min_user_id'];
            }
            if ($v['max_user_id'] != $userId) {
                $linkUserIdArr[] = $v['max_user_id'];
            }

            if ($i == 0) {
                $firstHouse = $v;
            }
            $i++;
        }

        $linkUserIdArr = array_unique($linkUserIdArr);


        $linkUserInfo = Db::table('user')
            ->whereIn('id',$linkUserIdArr)
            ->column('account','id');

        foreach ($house as $k => &$v) {

            if (in_array($v['min_user_id'], $linkUserIdArr)) {
                $v['user_name'] = $linkUserInfo[$v['min_user_id']];
            }
            if (in_array($v['max_user_id'], $linkUserIdArr)) {
                $v['user_name'] = $linkUserInfo[$v['max_user_id']];
            }

        }

        $chatInfo = Db::table('friend_chat')
            ->where('chat_house_id',$firstHouse['id'])
            ->order('id','desc')
            ->select()
            ->toArray();
//        dump($house,$linkUserIdArr, $linkUserInfo,$firstHouse,$chatInfo);

        $ret = [
            'user' => $user,
            'house' => $house,
            'linkUserIdArr' => $linkUserIdArr,
            'linkUserInfo' => $linkUserInfo,
            'firstHouse' => $firstHouse,
            'chatInfo' => $chatInfo,

        ];

        return view("index/friend_chat/chat_index", $ret);
    }

    /**
     * 开启一间聊天室
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addHouse()
    {
        $userId = session('user_id');
        if (!$userId) {
            return failed_response("请先登录",401);
        }

        $goodsId = input('goods_id',0);
        $goods = Db::table("goods")->where('id', $goodsId)->find();
        if (!$goods) {
            return failed_response('该商品已下架！' );
        }

        // 买家和卖家是同一人
        if ($goods['publish_id'] == $userId) {
            return failed_response("你是该商品的发布者，不能咨询！");
        }

        $minUserId = min($goods['publish_id'], $userId);
        $maxUserId = max($goods['publish_id'], $userId);


        $chatRelation = $minUserId ."," . $maxUserId;

        $house = Db::table("chat_house")
            ->where('chat_relation', $chatRelation)
            ->find();

        if (!$house) {
            Db::table("chat_house")
                ->insertGetId([
                    'min_user_id'     => $minUserId,
                    'max_user_id'     => $maxUserId,
                    'chat_relation'   => $chatRelation,
                    'create_time'     => date("Y-m-d"),
                    'last_chat_time'  => time(),
                ]);
        }

        return success_response();

    }

    public function addChatContent()
    {
        $userId = session('user_id');
        if (!$userId) {
            return failed_response("请先登录",401);
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
                'user_id'       => $userId,
                'chat_content'  => $chatContent,
                'chat_house_id' => $houseId,
                'user_type'     => 1,
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

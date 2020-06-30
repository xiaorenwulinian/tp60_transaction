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
        if (!session('user_id')) {
            return failed_response('请先登陆！',401);
        }
        $userId   = session('user_id');
        $goods_id = input('goods_id',0);

        $user = Db::table("user")->where('id', $userId)->find();
        $goods = Db::table("goods")->where('id', $goods_id)->find();

        if (!$goods) {
            return failed_response('该商品已下架！' . $goods_id);
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
                    'order_money'    => $goods["goods_price"],
                    'create_time'    => $curDate,
                    'unique_code'    => date("ymdHis") . '-' . StringTool::random(6),
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

    /**
     * 登录
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {

        if($this->request->isPost()) {
            $reqParam = $this->request->param();
            validate(\app\validate\UserValidate::class)->scene('login')->check($reqParam);

            $flag = captcha_check($reqParam['verify']);
            if(!$flag) {
                return failed_response('验证码错误');
            }

            $user = \app\model\User::where('account', '=', $reqParam['account'])->find();
            if (empty($user)) {
                return failed_response('未发现该账号！');
            }

            if ($user['password'] == md5($reqParam['password'])) {
                session('user_id', $user['id']);
                return success_response();
            }
            return failed_response('用户名或密码错误');
        }
        return view('index/user/login');
    }

    public function register()
    {

        if($this->request->isPost()) {

            $reqParam = $this->request->param();
            validate(\app\validate\UserValidate::class)->scene('register')->check($reqParam);

            $flag = captcha_check($reqParam['verify']);
            if(!$flag) {
                return failed_response('验证码错误');
            }

            if ($reqParam['password'] != $reqParam['repassword']) {
                return  failed_response("两次密码不一致！");
            }

            $isExist = \app\model\User::where('account', '=', $reqParam['account'])->find();
            if ($isExist) {
                return failed_response('该账号已存在，请更换！');
            }

            $maxId = \app\model\User::max('id');
            if (!$maxId) {
                $maxId = 0;
            }
            $maxId++;
            $uniqueCode = $maxId . '-' . date("Ymd-Hi") . '-' . StringTool::random(6);
            if (!empty($reqParam['invite_code'])) {
                $hasCount = \app\model\User::where('unique_code', '=', $reqParam['invite_code'])->count();
                if ($hasCount < 1) {
                    return failed_response("该邀请码不存在！");
                }
            }
            try {
                $data = [
                    'account'     => $reqParam['account'],
                    'password'    => md5($reqParam['password']),
                    'unique_code' => $uniqueCode,
                    'invite_code' => $reqParam['invite_code'],
                ];
                $user = \app\model\User::create($data);
            } catch (\Exception $e) {
                return  failed_response($e->getMessage());
            }

            session('user_id', $user['id']);
            return success_response();
        }
        return view('index/user/register');
    }

    public function logout()
    {
        session('user_id', null);
        return redirect("/index/user/index");
    }

    public function verify()
    {

        return Captcha::create('indexlogin');
    }
}

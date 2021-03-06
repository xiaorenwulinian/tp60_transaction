<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class User extends IndexBase
{

    public function index()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', session('user_id'))->find();
        $deadline = $user['vip_deadline'];
        if ($deadline < time()) {
            $deadline = 0;
        } else {
            $gapDay = ceil(($deadline - time()) / (3600 * 24));
            if ($gapDay < 31) {
                $deadline = $gapDay . '天';
            } else {
                $gapMonth = ceil($gapDay / 30);
                $year = floor($gapMonth / 12);
                $month = $gapMonth % 12;

                $deadlineYear = $deadlineMonth = '';
                if (!empty($year)) {
                    $deadlineYear = $year . "年";
                }

                if ($year < 10) {
                    if (!empty($month)) {
                        $deadlineMonth = $month . "个月";
                    }
                }

                $deadline = $deadlineYear . $deadlineMonth;
            }
        }
        $user['deadline_day'] = $deadline;
//        dump($user);
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
     * 用户意见反馈列表
     * @return \think\response\Redirect|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function feedbackConsultIndex()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect("/index/user/login");
        }


        $user = Db::table("user")->where('id', session('user_id'))->find();


        $ret = [
            'feedback' => [],
            'user' => $user,
        ];

        return view("index/user/feedback_consult_index", $ret);


    }

    public function feedbackConsultIndexMore()
    {
        $userId = input('user_id');

        $user = Db::table("user")->where('id', session('user_id'))->find();

        $pageSize = input('page_size',10);
        $curPage  = input('cur_page',1);
        $offset = ($curPage - 1) * $pageSize;

        $feedback = Db::table("user_feedback_consult")
            ->where([
                ['user_id', '=', $userId]
            ])
            ->order('id', 'desc')
            ->limit($offset,$pageSize)
            ->select()
            ->toArray();

        foreach ($feedback as &$v) {
            if ($v['add_type'] == 1) {
                $v['add_username'] = $user['account'];
                $v['position'] = 1; // 本人
            } else {
                $v['add_username'] = '管理员';
                $v['position'] = 2; // 对方
            }
        }


        $ret = [
            'feedback' => $feedback,
            'user' => $user,
        ];

        return success_response($ret);
    }


    /**
     * 用户意见反馈添加
     * @return \think\response\Redirect|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function feedbackConsultAdd()
    {
        if($this->request->isPost()) {
            $userId = session('user_id');
            if (!$userId) {
                return failed_response("请先登陆!",401);
            }
            $reqParam = $this->request->param();
            if (empty($reqParam["feedback_content"])) {
                return failed_response("反馈内容不能为空！");
            }
            Db::table("user_feedback_consult")
                ->insert([
                   "user_id" => $userId,
                   "admin_id" => 0,
                   "is_read" => 1,
                   "add_type" => 1,
                   "content" => $reqParam["feedback_content"],
                   "create_time" => date("Y-m-d H:i:s"),
                ]);

            return success_response();

        }

        $userId = session('user_id');

        if (!$userId) {
            return failed_response("请先登陆");

        }


        $feedback = Db::table("user_feedback_consult")
            ->where([
                ['user_id', '=', $userId]
            ])
            ->order('id', 'desc')
            ->select();

        $ret = [
            'feedback' => $feedback,
        ];

        return view("index/user_feedback_consult/index", $ret);


    }

    /**
     * 充值
     */
    public function topUp()
    {
        $userId = session('user_id');

        if (!$userId) {
            return failed_response("请先登陆");

        }

        $userInfo = Db::table('user')->find($userId);
        $ret = [
            'userInfo' => $userInfo
        ];
        return view("index/user/top_up", $ret);
    }

    /**
     * 提现
     */
    public function withdraw()
    {
        $userId = session('user_id');

        if (!$userId) {
            return failed_response("请先登陆");

        }

        $userInfo = Db::table('user')->find($userId);
        $ret = [
            'userInfo' => $userInfo
        ];
        return view("index/user/withdraw", $ret);

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

                $user->before_login_time = $user->last_login_time;
                $user->last_login_time = time();
                $user->save();
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
            $time = time();
            try {
                $data = [
                    'account'     => $reqParam['account'],
                    'password'    => md5($reqParam['password']),
                    'unique_code' => $uniqueCode,
                    'invite_code' => $reqParam['invite_code'],
                    'last_login_time' => $time,
                    'before_login_time' => $time,
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

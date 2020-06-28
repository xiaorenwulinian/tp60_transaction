<?php

namespace app\controller\index;


use think\captcha\facade\Captcha;
use think\facade\Db;


class User extends IndexBase
{

    public function index()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        return "index/user/index";
    }

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

            $adminData = \app\model\User::where('account', '=', $reqParam['account'])->find();
            if ($adminData) {
                return failed_response('该账号已存在，请更换！');
            }

            try {
                $data = [
                    'account' => $reqParam['account'],
                    'password' => md5($reqParam['password']),
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

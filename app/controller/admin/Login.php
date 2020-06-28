<?php

namespace app\controller\admin;


use think\captcha\facade\Captcha;
use think\facade\Db;


class Login extends AdminBase
{
    public function login()
    {

        if($this->request->isPost()) {
            $reqParam = $this->request->param();
            validate(\app\validate\AdminUserValidate::class)->scene('login')->check($reqParam);

            $flag = captcha_check($reqParam['verify']);
            if(!$flag) {
                return failed_response('验证码错误');
            }

            $adminData = \app\model\AdminUser::where('account', '=', $reqParam['account'])->find();
            if (empty($adminData)) {
                return failed_response('未发现该账号！');
            }

            if($adminData['id'] == 1 || $adminData['is_use'] == 1) {
                if($adminData['password'] == md5($reqParam['password'])) {
                    session('admin_user_id', $adminData['id']);
                    session('admin_user_name', $adminData['username']);
                    return success_response();
                }
                return failed_response('用户名或密码错误');
            }
            return failed_response('账号被禁用');
        }
        return view('admin/login/login');
    }

    public function verify()
    {
        return Captcha::create();
    }
}

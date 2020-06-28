<?php

namespace app\common\tools;


class CommonTool
{

    /**
     * 是否是 ajax 操作
     * @return bool
     */
    public function isAjaxOperator()
    {
        if ( (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            || !empty($_POST['ajax'])
            || !empty($_GET['ajax'])
        ) {
            return true;
        }
        return false;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @return mixed
     */
    function get_client_ip($type = 0) {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) {
            return $ip[$type];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown',$arr);
            if (false !== $pos){
                unset($arr[$pos]);
            }
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip =  $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


    /**
     * 放入父级构造器中
     * @return mixed
     */
    public function login_limit_time()
    {
        $admin_id = $_SESSION['admin_id'];
        if (empty($admin_id)) {
            return redirct('admin/login');  // 还未登录，跳转到登录页面
        }
        $login_time = $_SESSION['login_time'];
        if (!empty($login_time)) {
            $limit_time = 30 * 60 ; // 设置登录有效时常，30分钟未操作，重新登录
            if ($login_time + $limit_time < time()) {
                // 已经 session 过期
                $_SESSION['login_time'] = null;
                $_SESSION['admin_id']   = null; // 登录到用户ID置为空
                return redirct('admin/login');  // 跳转到登录页面
            }
        }
        $_SESSION['login_time'] = time();

    }

}

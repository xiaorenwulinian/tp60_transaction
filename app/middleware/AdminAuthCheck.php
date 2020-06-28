<?php

namespace app\middleware;


class AdminAuthCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

        $adminId = session('admin_user_id');
        if (empty($adminId)) {
            return redirect('/admin/login/login');
        }
        return $next($request);
    }
}

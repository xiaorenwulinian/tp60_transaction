<?php

namespace app\middleware;


use app\common\tools\LclJwtTool;

class MiniProgramAuthCheck
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
        $wechat = LclJwtTool::getInstance()->getWeChatInfoMiniProgram();
        if ( empty($wechat)) {
            return failed_response('非法攻击～！');
        }
        return $next($request);
    }
}

<?php

namespace app\common\tools;


use app\exception\LclJwtException;
use app\libs\lclJwt\XcxPay;

class LclJwtTool
{

    protected static $instance ;
    
    public $miniProgramWxId;
    public $miniProgramUserId;

    private function __construct(){}

    private function __clone(){}

    /**
     * 获取实例对象，单例模式
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 获取weChatId
     * @return mixed
     * @throws \Exception
     */
    public function getWeChatIdMiniProgram()
    {
        $secretKey = config('lclJwtToken.secret_key_miniprogram','huamanyuan');
        $authorization = request()->header('authorization');
        $authorizationArr = explode(' ', $authorization);
        $jwtToken = $authorizationArr[1] ?? '';
        $decryptRet = XcxPay::decrypt($jwtToken, $secretKey);
        if ($decryptRet['code'] !== 0) {
            if ($decryptRet['code'] == 1) {
                throw new LclJwtException($decryptRet['message'], 401);
//                return failed_response($decryptRet['message'], 401); // 401 没有权限 token 不匹配
            } else {
                throw new LclJwtException($decryptRet['message'], 401);

//                return failed_response($decryptRet['message'], 401);
//                return failed_response($decryptRet['message'], 402); // 402 token 过期
            }
        } else {
            $decryptData = (array)$decryptRet['data'];
            $weChatId = $decryptData['wxId'];
            return $weChatId;
        }


    }


    /**
     * 获取微信信息，可得到wechatId, userId
     * @return mixed
     * @throws \Exception
     */
    public function getWeChatInfoMiniProgram()
    {
        $secretKey = config('lclJwtToken.secret_key_miniprogram','huamanyuan');
        $authorization = request()->header('authorization');
        $authorizationArr = explode(' ', $authorization);
        $jwtToken = $authorizationArr[1] ?? '';
        $decryptRet = XcxPay::decrypt($jwtToken, $secretKey);
        if ($decryptRet['code'] !== 0) {
            if ($decryptRet['code'] == 1) {
                throw new LclJwtException($decryptRet['message'], 401);
//                return failed_response($decryptRet['message'], 401); // 401 没有权限 token 不匹配
            } else {
                throw new LclJwtException($decryptRet['message'], 401);

//                return failed_response($decryptRet['message'], 401);
//                return failed_response($decryptRet['message'], 402); // 402 token 过期
            }
        } else {
            $decryptData =  (array)$decryptRet['data'];
            $weChatInfo = json_decode(json_encode($decryptData['weChatInfo']), true);
            return $weChatInfo;
        }

    }

    /**
     * 生成token
     * @param $user
     *
     */
    public function generateTokenMiniProgram(Array $wechat)
    {

        $secretKey = config('lclJwtToken.secret_key_miniprogram','huamanyuan');
        $time = time();
        // 有效期 单位秒
        $expireTime = $time + config('lclJwtToken.expire_time_miniprogram',60 * 60 * 24 * 7);
        $secretData = array(
            "exp"        => $expireTime,
            "wxId"       => $wechat['id'], // 微信ID
            "weChatInfo" => $wechat, // 微信信息
        );
        $jwtToken = XcxPay::encrypt($secretData, $secretKey);
        return $jwtToken;
    }
}

<?php


namespace app\logic;



use GuzzleHttp\Client;

class WeChatLogic
{
    private static $instance;
    const CURL_jscode2session = "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";
    private $appid;
    private $sessionKey;

    public function __construct()
    {

        $this->appid = config('miniprogram.app_id');
        $this->sessionKey = config('miniprogram.secret');

    }

    /**
     * 单例模式获取实例
     * @return WeChatLogic
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * 根据code,获取openid,unionid, sesson_key
     * @param $code
     * @return mixed 包含 opened ，session_key, 如果多端绑定，包括unionid
     */
    public function jscode2Session($code)
    {
        $url = sprintf(static::CURL_jscode2session, $this->appid, $this->sessionKey, $code);
        $ret = $this->sendCurl($url);
        return $ret;
    }
    private function sendCurl($url, $method = 'get')
    {
        $client = new Client();
        $response = $client->request($method, $url);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $sessionKey string 登录后的会话秘钥
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($encryptedData, $iv, $sessionKey)
    {
//        public static $OK = 0;
//        public static $IllegalAesKey = -41001;
//        public static $IllegalIv = -41002;
//        public static $IllegalBuffer = -41003;
//        public static $DecodeBase64Error = -41004;

        $ret = [
            'code' => 0,
            'data' => [],
            'message'=> '' ,
        ];
        if (strlen($sessionKey) != 24) {
            $ret['code'] = -41001;
            return $ret;
        }
        $aesKey=base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            $ret['code'] = -41002;
            return $ret;
        }
        $aesIV=base64_decode($iv);
        $aesCipher=base64_decode($encryptedData);
        $result=openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj=json_decode($result);
        if ($dataObj  == NULL ) {
            $ret['code'] = -41003;
            return $ret;
        }
        if ($dataObj->watermark->appid != $this->appid ) {
            $ret['code'] = -41003;
            return $ret;
        }
        $ret['data'] = $result;
        return $ret;
    }

}

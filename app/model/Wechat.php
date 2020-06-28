<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Wechat extends Model
{
    protected $name = 'wechat';
    protected $table = 'wechat';

    protected $field = [
        'user_id', 'unionid', 'g_openid', 'x_openid', 'wx_nickname', 'wx_country','wx_province'
        ,'wx_city', 'wx_sex', 'wx_headimgurl', 'xcx_session_key', 'phone'
    ];
    protected $disuse = [];


    
}

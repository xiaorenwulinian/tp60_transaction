<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AdminUserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'verify'   => 'require|max:6',
	    'account'  => 'require|max:255',
	    'password' => 'require|max:255',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'verify.require' => '验证码必传',
        'verify.max'     => '验证码最多不能超过6个字符',
    ];
    protected $scene = [
        'login'  =>  ['verify','account','password'],
    ];
}

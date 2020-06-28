<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class VipLevelValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'            => 'require|integer',
	    'vip_name'      => 'require|max:255',
	    'vip_fee'       => 'require|integer',
	    'vip_desc'      => 'require|max:255',
        'is_use'        => 'require|integer',
        'vip_welfare'   => 'require|max:255',
    ];

    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'vip_name.require'        => 'vip级别名称必填',
        'vip_name.max'            => 'vip级别名称最多不能超过255个字符',
        'vip_fee.require'         => 'vip价格必填',
        'vip_desc.require'        => '描述必填',
        'vip_welfare.require'     => '福利必填',
        'is_use.integer'          => '是否使用为正整数',
    ];
    protected $scene = [
        'add'  =>  ['vip_name', 'vip_desc', 'goods_img', 'vip_welfare','vip_desc', 'is_use'],
        'edit'  =>  ['id','vip_name', 'vip_desc', 'goods_img', 'vip_welfare','vip_desc', 'is_use'],
    ];
}

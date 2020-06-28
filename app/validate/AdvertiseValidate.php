<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AdvertiseValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'          => 'require|integer',
	    'title'       => 'require|max:255',
        'adv_type_id' => 'require|integer',
	    'adv_desc'    => 'require|max:255',
	    'adv_url'     => 'require|max:255',
	    'adv_img'     => 'require|max:255',
	    'sort_id'     => 'require|integer',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'       => '广告名称必填',
        'title.max'           => '广告名称最多不能超过255个字符',
        'adv_type_id.require' => '请选择广告栏位',
        'adv_type_id.integer' => '广告栏位为正整数',
        'adv_desc.require'    => '描述必填',
        'adv_desc.max'        => '描述最多不能超过255个字符',
        'adv_url.require'     => '跳转路径必填',
        'adv_url.max'         => '跳转路径最多不能超过255个字符',
        'sort_id.integer'     => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['title', 'adv_type_id', 'adv_img', 'adv_desc', 'sort_id'],
        'edit' =>  ['id', 'title', 'adv_type_id', 'adv_img', 'adv_desc', 'sort_id'],
    ];
}

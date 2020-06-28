<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AdvertiseTypeValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'          => 'require|integer',
	    'name'        => 'require|max:255',
	    'desc'        => 'require|max:255',
	    'identify_en' => 'require|max:255',
	    'sort_id'     => 'require|integer',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '栏目名称必填',
        'name.max'     => '栏目名称最多不能超过255个字符',
        'desc.require' => '描述必填',
        'desc.max'     => '描述最多不能超过255个字符',
        'identify_en.require' => '标识符必填',
        'identify_en.max'     => '标识符最多不能超过255个字符',
        'sort_id.integer'     => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['name','desc', 'identify_en', 'sort_id'],
        'edit' =>  ['id','name','desc', 'identify_en', 'sort_id'],
    ];
}

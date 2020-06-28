<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class GoodsCategoryValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'   => 'require|integer',
	    'cate_name'   => 'require|max:60',
	    'mobile_name'  => 'require|max:60',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'cate_name.require' => '分类名称必填',
        'cate_name.max'     => '分类名称最多不能超过60个字符',
    ];
    protected $scene = [
        'add'  =>  ['cate_name'],
        'edit' =>  ['id','cate_name'],
    ];
}

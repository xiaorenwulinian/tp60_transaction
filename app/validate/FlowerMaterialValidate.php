<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class FlowerMaterialValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'          => 'require|integer',
	    'title'       => 'require|max:60',
	    'sort_id'     => 'require|integer',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'       => '鲜花花材名称必填',
        'title.max'           => '鲜花花材名称最多不能超过60个字符',
        'sort_id.integer'     => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['title','sort_id'],
        'edit' =>  ['id', 'title', 'sort_id'],
    ];
}

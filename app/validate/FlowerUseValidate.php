<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class FlowerUseValidate extends Validate
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
	    'sub_title'   => 'require|max:60',
	    'use_img'     => 'require|max:255',
	    'use_img_thumb' => 'require|max:255',
	    'sort_id'     => 'require|integer',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'       => '鲜花用途名称必填',
        'title.max'           => '鲜花用途名称最多不能超过60个字符',
        'sub_title.require'   => '鲜花用途别名必填',
        'sub_title.max'       => '鲜花用途别名最多不能超过60个字符',
        'use_img.require'     => '图片路径必填',
        'use_img.max'         => '图片路径最多不能超过255个字符',
        'sort_id.integer'     => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['title', 'sub_title', 'use_img', 'use_img_thumb', 'sort_id'],
        'edit' =>  ['id', 'title', 'sub_title', 'use_img', 'use_img_thumb', 'sort_id'],
    ];
}

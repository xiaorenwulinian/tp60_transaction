<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class BaseConfigValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'                   => 'require|integer',
	    'publish_limit_num'    => 'require|integer|max:255',
	    'upload_limit_size'    => 'require|integer|max:100',
        'user_publish_audit'   => 'require|integer',
        'auto_goods_day'   => 'require|integer',
    ];

    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
//        'goods_name.require'          => '商品名称必填',
//        'goods_name.max'              => '商品名称最多不能超过255个字符',
//        'goods_price.require'         => '商品价格必填',
//        'goods_img.require'           => '请上传商品图片',
//        'goods_category_id.require'   => '商品分类必选',
//        'send_info.require'           => '发货信息必填',
//        'goods_desc.require'          => '商品描述必填',
//        'goods_introduce_img.require' => '请上传商品介绍图片',
//        'sort_id.integer'             => '排序为正整数',
    ];
    protected $scene = [
        'edit' =>  ['id', 'publish_limit_num', 'upload_limit_size', 'user_publish_audit','auto_goods_day'],

    ];
}

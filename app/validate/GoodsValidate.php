<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class GoodsValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id'                   => 'require|integer',
	    'goods_name'           => 'require|max:255',
	    'goods_price'          => 'require|float',
	    'goods_img'            => 'require|max:255',
        'goods_category_id'    => 'require|integer',
        'send_info'            => 'require|max:255',
        'has_sale_num'         => 'require|integer',
        'stock_num'            => 'require|integer',
//	    'sort_id'              => 'require|integer',
	    'goods_introduce_img'  => 'require',
	    'goods_desc'           => 'require',
    ];

    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'goods_name.require'          => '商品名称必填',
        'goods_name.max'              => '商品名称最多不能超过255个字符',
        'goods_price.require'         => '商品价格必填',
        'goods_img.require'           => '请上传商品图片',
        'goods_category_id.require'   => '商品分类必选',
        'send_info.require'           => '发货信息必填',
        'goods_desc.require'          => '商品描述必填',
        'goods_introduce_img.require' => '请上传商品介绍图片',
        'sort_id.integer'             => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['goods_name', 'goods_price', 'goods_img', 'goods_category_id','goods_desc'],
        'edit' =>  ['id', 'goods_name', 'goods_price', 'goods_img', 'goods_category_id','goods_desc'],
        'userAdd' =>  ['id', 'goods_name', 'goods_price', 'goods_img', 'goods_category_id','goods_desc'],
        'userEdit' =>  ['id', 'goods_name', 'goods_price', 'goods_img', 'goods_category_id','goods_desc'],
    ];
}

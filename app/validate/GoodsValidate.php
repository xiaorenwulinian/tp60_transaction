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
	    'discount_price'       => 'require|float',
	    'is_promote'           => 'require|in:1,2',
	    'goods_img'            => 'require|max:255',
        'flower_material'      => 'require|max:255',
        'flower_use'           => 'require|max:255',
        'flower_combine'       => 'require|max:255',
        'has_sale_num'         => 'require|integer',
        'flower_stock'         => 'require|integer',
	    'sort_id'              => 'require|integer',
	    'goods_introduce_img'  => 'require',
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
        'goods_price.require'         => '商品原价必填',
        'discount_price.require'      => '商品现价必填',
        'goods_img.require'           => '请上传商品图片',
        'flower_material.require'     => '鲜花材料必选',
        'flower_use.require'          => '鲜花用途必选',
        'flower_combine.require'      => '组合材料必填',
        'goods_introduce_img.require' => '请上传商品介绍图片',
        'sort_id.integer'             => '排序为正整数',
    ];
    protected $scene = [
        'add'  =>  ['goods_name', 'goods_price', 'discount_price', 'goods_img', 'flower_material', 'flower_use', 'flower_combine', 'sort_id'],
        'edit' =>  ['id', 'goods_name', 'goods_price', 'discount_price', 'goods_img', 'flower_material', 'flower_use', 'flower_combine', 'sort_id'],
    ];
}

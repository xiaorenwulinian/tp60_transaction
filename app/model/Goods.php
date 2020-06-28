<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class Goods extends Model
{
    protected $name = 'goods';
    protected $table = 'goods';

//CREATE TABLE `goods` (
//`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
//`goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
//`goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
//`discount_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠价',
//`is_promote` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1不促销，2促销',
//`promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
//`promote_begin_time` int(10) NOT NULL DEFAULT '0' COMMENT '促销开始时间',
//`promote_end_time` int(10) NOT NULL DEFAULT '0' COMMENT '促销结束时间',
//`goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
//`goods_img_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
//`is_best` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否商户推荐1 否，2.是',
//`is_hot` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否热卖，1否，2是',
//`is_new` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否新品，1，否，2是',
//`is_on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架：1：下架，2：上架',
//`flower_material` varchar(255) NOT NULL DEFAULT '' COMMENT '花材，如百合',
//`flower_use` varchar(255) NOT NULL DEFAULT '' COMMENT '用途，如送长辈',
//`flower_combine` varchar(255) NOT NULL DEFAULT '' COMMENT '组合材料',
//`flower_moral` varchar(255) NOT NULL DEFAULT '' COMMENT '花语',
//`remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
//`flower_package` varchar(255) NOT NULL DEFAULT '' COMMENT '包装',
//`flower_stock` smallint(255) NOT NULL DEFAULT '999' COMMENT '库存量',
//`has_sale_num` mediumint(9) NOT NULL DEFAULT '0' COMMENT '已售出数量',
//`sort_id` mediumint(9) NOT NULL DEFAULT '100' COMMENT '排序',
//`add_time` int(10) NOT NULL COMMENT '添加时间',
//`is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在回收站， 1否，2是',
//PRIMARY KEY (`id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    protected $field = [
        'goods_name', 'goods_price',
        'goods_img','goods_img_thumb',
        'is_on_sale', 'goods_category_id',
        'send_info','remark',
        'has_sale_num','stock_num',
        'flower_package', 'sort_id',
        'goods_desc', 'seo_keyword','seo_description',
        'add_time', 'is_delete','edit_time',
    ];
    protected $disuse = [];

    public static function search($pageSize)
    {
        $where = [];
        $where[] = ['is_delete', '=', 1];
        $goodsName = input('goods_name', '');

        if (!empty($goodsName)) {
            $where[] = ['goods_name', 'like', "%{$goodsName}%"];
        }

        $categoryId = input('goods_category_id', '');
        if (!empty($categoryId)) {
            $where[] = ['goods_category_id', '=', "$categoryId"];
        }


        $data = self::where($where)
            ->order('id','desc')
            ->paginate($pageSize);
        return $data;
    }




    /**
     *  前置钩子函数，添加前
     * @param Model $goods
     * @return mixed|void
     */
    public static function onBeforeInsert($goods)
    {
//        if ($goods->is_promote == 2) {
//            $goods->promote_begin_time =  strtotime($goods->promote_begin_time);
//            $goods->promote_end_time =  strtotime($goods->promote_end_time);
//        }
        $goods->add_time = time();
    }

    /**
     * 后置钩子函数，添加后
     * @param Model $goods
     */
    public static function onAfterInsert($goods)
    {
        $introImg  = request()->param('goods_introduce_img');
        $introImgThumb  = request()->param('goods_introduce_img');
        if ($introImg) {
            $introImgArr = explode(',',$introImg);
            $introImgThumbArr = explode(',',$introImgThumb);
            $insertImg = [];
            foreach ($introImgArr as $k=>$v) {
                $temp = [
                    'introduce_img'       => $v,
                    'introduce_img_thumb' => $introImgThumbArr[$k],
                    'goods_id'            => $goods->id,
                ];
                array_push($insertImg, $temp);
            }
            if ($insertImg) {
                Db::table('goods_introduce_img')->insertAll($insertImg);
            }
        }
    }


    public static function onAfterUpdate($goods)
    {
        $introImg  = request()->param('goods_introduce_img');
        $introImgThumb  = request()->param('goods_introduce_img');
        if (!empty($introImg)) {
            $introImgArr = explode(',',$introImg);
            $introImgThumbArr = explode(',',$introImgThumb);
            $insertImg = [];
            foreach ($introImgArr as $k=>$v) {
                $temp = [
                    'introduce_img'       => $v,
                    'introduce_img_thumb' => $introImgThumbArr[$k],
                    'goods_id'            => $goods->id,
                ];
                array_push($insertImg, $temp);
            }
            if ($insertImg) {
                Db::table('goods_introduce_img')->insertAll($insertImg);
            }
        }
    }


}

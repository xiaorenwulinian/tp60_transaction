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
/*

    CREATE TABLE `goods` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
    `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
    `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
    `goods_img_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
    `is_on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架：1：下架，2：上架',
    `goods_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类ID',
    `send_info` varchar(255) NOT NULL DEFAULT '' COMMENT '发货信息',
    `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    `stock_num` smallint(5) NOT NULL DEFAULT '99' COMMENT '库存量',
    `has_sale_num` mediumint(5) NOT NULL DEFAULT '0' COMMENT '已售出数量',
    `sort_id` mediumint(5) NOT NULL DEFAULT '9999' COMMENT '排序',


    `goods_desc` longtext COMMENT '商品描述',
    `seo_keyword` varchar(255) NOT NULL DEFAULT '',
    `seo_description` varchar(255) NOT NULL DEFAULT '',
    `add_time` int(10) NOT NULL COMMENT '添加时间',
    `edit_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
    `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在回收站， 1否，2是',

    `publish_id` int(11) NOT NULL DEFAULT '0' COMMENT '发布人',
    `publish_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1管理员发布，2用户发布',
    `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态，1审核中，2审核通过，3审核失败',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;*/
    protected $field = [
        'goods_name',
        'goods_price',
        'goods_img',
        'goods_img_thumb',
        'is_on_sale',
        'goods_category_id',
        'send_info',
        'remark',
        'has_sale_num',
        'stock_num',
        'flower_package',
        'sort_id',
        'goods_desc',
        'seo_keyword',
        'seo_description',
        'add_time',
        'is_delete',
        'edit_time',
        'publish_id',
        'publish_type',
        'audit_status',
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

<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class GoodsCategory extends Model
{
    protected $name = 'goods_category';
    protected $table = 'goods_category';

    protected $field = [
        'cate_name', 'mobile_name', 'parent_id', 'parent_id_path', 'cate_level','sort_order'
        ,'is_show','image','is_hot','cate_group','commission_rate'
    ];
    protected $disuse = [];
}

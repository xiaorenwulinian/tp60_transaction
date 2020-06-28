<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class GoodsIntroduceImg extends Model
{
    protected $name = 'goods_introduce_img';
    protected $table = 'goods_introduce_img';

    protected $field = [
        'goods_id', 'introduce_img', 'introduce_img_thumb', 'is_delete'
    ];
    protected $disuse = [];
}

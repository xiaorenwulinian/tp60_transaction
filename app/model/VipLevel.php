<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class VipLevel extends Model
{
    protected $name = 'vip_level';
    protected $table = 'vip_level';

    protected $field = [
        'vip_name',
        'vip_desc',
        'vip_fee',
        'is_use',
        'vip_welfare',
        'sort_order',
        'vip_delay_day',
        ];
    protected $disuse = [];
}

<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class AdvertiseType extends Model
{
    protected $name = 'advertise_type';
    protected $table = 'advertise_type';

    protected $field = [
        'name', 'desc', 'identify_en','sort_id'
    ];
    protected $disuse = [];
}

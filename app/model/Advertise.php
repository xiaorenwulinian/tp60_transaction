<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Advertise extends Model
{
    protected $name = 'advertise';
    protected $table = 'advertise';

    protected $field = [
        'title', 'adv_desc', 'adv_url', 'adv_img', 'adv_img_thumb', 'adv_type_id','is_show','sort_id','is_delete'
    ];
    protected $disuse = [];


    public static function search($pageSize)
    {
        $where = [];
        $where[] = ['a.is_delete', '=', 1];
        $title = input('title', '');
        if (!empty($title)) {
            $where[] = ['a.title', 'like', "%{$title}%"];
        }
        $advTypeId = input('adv_type_id', '');
        if (!empty($advTypeId)) {
            $where[] = ['a.adv_type_id', '=', $title];
        }
        $data = self::alias('a')
            ->field('a.*, at.name as adv_type_name')
            ->leftJoin('advertise_type at','a.adv_type_id = at.id')
            ->where($where)
            ->order('a.sort_id','asc')
            ->paginate($pageSize);
        return $data;
    }
}

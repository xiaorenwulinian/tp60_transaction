<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * desc 鲜花用途
 * @mixin think\Model
 */
class FlowerUse extends Model
{
    protected $name = 'flower_use';
    protected $table = 'flower_use';

    protected $field = [
        'title', 'sub_title', 'use_img', 'use_img_thumb','is_delete','sort_id'
    ];
    protected $disuse = [];


    public static function search($pageSize)
    {
        $where = [];
        $where[] = ['is_delete', '=', 1];
        $title = input('title', '');
        if (!empty($title)) {
            $where[] = ['title', 'like', "%{$title}%"];
        }
        $data = self::where($where)
            ->order('id','desc')
            ->paginate($pageSize);
        return $data;
    }
}

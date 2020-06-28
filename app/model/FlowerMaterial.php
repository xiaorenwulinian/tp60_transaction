<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * desc 鲜花花材
 * @mixin think\Model
 */
class FlowerMaterial extends Model
{
    protected $name = 'flower_material';
    protected $table = 'flower_material';

    protected $field = [
        'title','is_delete','sort_id'
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

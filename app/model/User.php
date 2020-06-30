<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class User extends Model
{
    protected $name = 'user';
    protected $table = 'user';

    protected $field = [
        'account','phone', 'password',
        'nickname', 'head_photo',
        'user_money',
        'create_time', 'update_time',
        'unique_code', 'invite_code',
        'last_login_time', 'before_login_time',

    ];
    protected $disuse = [];


    public static function search($pageSize)
    {
        $where = [];
//        $where[] = ['is_delete', '=', 1];

        $account = input('account', '');

        if (!empty($account)) {
            $where[] = ['account', 'like', "%{$account}%"];
        }

        $add_begin_time = input('add_begin_time', '');
        $add_end_time = input('add_end_time', '');
        if (!empty($add_begin_time)) {
            $where[] = ['create_time', '>=', "$add_begin_time"];
        }

        if (!empty($add_begin_time)) {
            $where[] = ['create_time', '<=', "$add_end_time"];
        }


        $data = self::where($where)
            ->order('id','desc')
            ->paginate($pageSize);
        return $data;
    }
    
}

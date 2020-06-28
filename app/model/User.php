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
        'phone', 'password', 'nickname', 'head_photo', 'create_time', 'update_time'
    ];
    protected $disuse = [];


    
}

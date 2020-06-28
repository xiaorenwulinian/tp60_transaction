<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class AdminUser extends Model
{
    protected $name = 'admin';
    protected $table = 'admin';

    protected $field = [
        'account', 'username', 'password', 'is_use', 'is_delete'
    ];
    protected $disuse = [];
}

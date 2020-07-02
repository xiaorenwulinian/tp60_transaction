<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class BaseConfig extends Model
{
    protected $name = 'base_config';
    protected $table = 'base_config';

    protected $field = [
        'publish_limit_num',
        'upload_limit_size',
        'user_publish_audit',
        ];
    protected $disuse = [];
}

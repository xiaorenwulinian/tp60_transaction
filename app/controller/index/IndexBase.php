<?php

namespace app\controller\index;


use app\BaseController;
use think\App;

class IndexBase extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

}

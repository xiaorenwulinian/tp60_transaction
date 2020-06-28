<?php

namespace app\controller\admin;


use app\BaseController;
use think\App;

class AdminBase extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

}

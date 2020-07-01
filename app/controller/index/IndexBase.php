<?php

namespace app\controller\index;


use app\BaseController;
use think\App;
use think\facade\Db;

class IndexBase extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }


    /**
     * 获取基本配置信息
     * @return array|mixed|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBaseConfigInfo()
    {
        $config = cache("base_config_info");

        if (empty($config)) {
            $data = Db::table('base_config')
                ->where('id',1)
                ->find();
            $configInfo = serialize($data);
            cache("base_config_info", $configInfo, 3600);
        } else {
            $data = unserialize($config);
        }
        return $data;
    }
}

<?php

namespace app\controller\admin;


use app\common\tools\ArrayTool;
use think\facade\Db;

class BaseConfig extends AdminBase
{
    public function lst()
    {
        $data = Db::table('base_config')
            ->select()
            ->toArray();

        return view('admin/base_config/lst',['data' => $data]);
    }

    public function edit()
    {
        $id = $this->request->param('id');
        if ($this->request->isPost()) {

            $reqParam = $this->request->param();
            validate(\app\validate\BaseConfigValidate::class)->scene('edit')->check($reqParam);


            try {
                $config  = \app\model\BaseConfig::where('id', $id)->find();

                $config->user_publish_audit =  $reqParam['user_publish_audit'];
                $config->upload_limit_size  =  $reqParam['upload_limit_size'];
                $config->publish_limit_num  =  $reqParam['publish_limit_num'];
                $config ->save();
                cache("base_config_info", null);
            } catch (\Exception $e) {
                return failed_response($e->getMessage());
            }
            return success_response();
        }

        $data = Db::table('base_config')
            ->where('id','=', 1)
            ->find();

        $ret = [
            'data'       => $data,
        ];
        return view('admin/base_config/edit', $ret);
    }



}

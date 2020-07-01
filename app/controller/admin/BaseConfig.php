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
            validate(\app\validate\VipLevelValidate::class)->scene('edit')->check($reqParam);


            try {
                $vip  = \app\model\VipLevel::where('id', $id)->find();

                $vip->vip_name   =  $reqParam['vip_name'];
                $vip->vip_desc =  $reqParam['vip_desc'];
                $vip->vip_welfare  =  $reqParam['vip_welfare'];
                $vip->vip_fee  =  $reqParam['vip_fee'];
                $vip->is_use  =  $reqParam['is_use'];
                $vip->vip_delay_day  =  $reqParam['vip_delay_day'];
                $vip ->save();
            } catch (\Exception $e) {
                return failed_response($e->getMessage());
            }
            return success_response();
        }

        $data = Db::table('vip_level')->where('id', $id)->find();

        $ret = [
            'data'       => $data,
        ];
        return view('admin/vip_level/edit', $ret);
    }



}

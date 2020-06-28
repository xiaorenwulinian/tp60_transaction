<?php

namespace app\controller\admin;


use app\common\tools\ArrayTool;
use think\facade\Db;

class VipLevel extends AdminBase
{
    public function lst()
    {
        $vip_level = Db::table('vip_level')->order('id','asc')->select()->toArray();

        return view('admin/vip_level/lst',['data' => $vip_level]);
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $reqParam = $this->request->param();
            validate(\app\validate\VipLevelValidate::class)->scene('add')->check($reqParam);


            try {
                 \app\model\VipLevel::create($reqParam);

            } catch (\Exception $e) {

                return failed_response($e->getMessage());
            }
            return success_response();
        }

        $ret = [

        ];
        return view('admin/vip_level/add', $ret);

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

    /**
     * 修改显示状态
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function changeShow()
    {
        $id = $this->request->param('id');
        $goodsCat = \app\model\GoodsCategory::where('id', $id)->find();
        $isShow   = $goodsCat->is_show == 1 ? 2 : 1;
        $goodsCat->is_show = $isShow;
        $goodsCat->save();
        return success_response();
    }

    /**
     * 删除或批量删除
     * @return \think\response\Json
     */
    public function delete()
    {
        $ids = $this->request->param('id');
        $idArr = explode(',', $ids);
        if (count($idArr) > 1) {
            return failed_response('不支持批量删除！');
        }
        return success_response();

    }

    /**
     * 修改排序
     * @return \think\response\Json
     */
    public function editSort()
    {
        $id = $this->request->param('id');
        $sortId = $this->request->param('sort_id');
        \app\model\GoodsCategory::where('id','=', $id)->update(['sort_order' => $sortId ]);
        return success_response();
    }
}

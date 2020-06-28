<?php

namespace app\controller\admin;


use app\common\tools\ArrayTool;
use think\facade\Db;

class GoodsCategory extends AdminBase
{
    public function lst()
    {
        $goodsCate = Db::table('goods_category')->order('sort_order','asc')->select()->toArray();
        $data = ArrayTool::getTree($goodsCate);
        return view('admin/goods_category/lst',['data' => $data]);
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $reqParam = $this->request->param();
            validate(\app\validate\GoodsCategoryValidate::class)->scene('add')->check($reqParam);

            Db::startTrans();
            try {
                $goodsCate  = \app\model\GoodsCategory::create($reqParam);
                if ($goodsCate->parent_id == 0) {
                    $parent_id_path = '0' ;
                    $level = 1;
                } else {
                    $parent = Db::table('goods_category')->where('id', $goodsCate->parent_id)->find();
                    $parent_id_path = $parent['parent_id_path'];
                    $level = $parent['cate_level'] + 1;
                }
                $goodsCate->parent_id_path = $parent_id_path. '_' . $goodsCate->id;
                $goodsCate->cate_level = $level;
                $goodsCate ->save();
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return failed_response($e->getMessage());
            }
            return success_response();
        }
        $goodsCate = Db::table('goods_category')->order('sort_order','asc')->select()->toArray();
        $parentData = ArrayTool::getTree($goodsCate);
        $ret = [
            'parentData' => $parentData
        ];
        return view('admin/goods_category/add', $ret);

    }

    public function edit()
    {
        $id = $this->request->param('id');
        if ($this->request->isPost()) {

            $reqParam = $this->request->param();
            validate(\app\validate\GoodsCategoryValidate::class)->scene('edit')->check($reqParam);

            Db::startTrans();
            try {
                $goodsCate  = \app\model\GoodsCategory::where('id', $id)->find();
                $pid = $reqParam['parent_id'];
                if ( $pid != $goodsCate->parent_id) {
                    if ($pid == 0) {
                        $goodsCate->parent_id_path = '0_' . $goodsCate->id;
                        $goodsCate->cate_level =  1;
                    } else {
                        $parent = Db::table('goods_category')->where('id', $pid)->find();
                        $goodsCate->parent_id_path = $parent['parent_id_path']. '_' . $goodsCate->id;
                        $goodsCate->cate_level =  $parent['cate_level'] + 1;
                    }
                    $goodsCate->parent_id = $reqParam['parent_id'];
                }
                $goodsCate->cate_name   =  $reqParam['cate_name'];
                $goodsCate->mobile_name =  $reqParam['mobile_name'];
                $goodsCate->is_show     =  $reqParam['is_show'];
                $goodsCate->sort_order  =  $reqParam['sort_order'];
                $goodsCate ->save();
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return failed_response($e->getMessage());
            }
            return success_response();
        }

        $goodsCate = Db::table('goods_category')->order('sort_order','asc')->select()->toArray();
        $data = Db::table('goods_category')->where('id', $id)->find();
        $parentData = ArrayTool::getTree($goodsCate);
        //修改后的文章分类不能是本身和子级
        $childrenId = ArrayTool::getChildren($goodsCate, $id);
        $childrenId[] = $id;
        $ret = [
            'parentData' => $parentData,
            'data'       => $data,
            'childrenId' => $childrenId,
        ];
        return view('admin/goods_category/edit', $ret);
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

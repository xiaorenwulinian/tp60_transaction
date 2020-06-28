<?php

namespace app\controller\admin;


use app\model\GoodsIntroduceImg;
use think\exception\ValidateException;
use think\facade\Db;

class Goods extends AdminBase
{
    /**
     * 列表
     * @return \think\response\View
     */
    public function lst()
    {
        $pageSize = input('page_size', 10); // 每一页默认显示的条数
        $pageSizeSelect = page_size_select($pageSize); //生成下拉选择页数框
        $data = \app\model\Goods::search($pageSize);
        $pageShow = $data->render();
        $category = \app\model\GoodsCategory::where('is_show','=',1)
                ->column('cate_name','id');

        $ret = [
            'data'           => $data,
            'pageSizeSelect' => $pageSizeSelect,
            'pageSize'       => $pageSize,
            'pageShow'       => $pageShow,
            'category'       => $category,
        ];
        return view('admin/goods/lst', $ret);
    }

    public function getGoodsImgAttr($value)
    {
        return  request()->rootDomain(). 'uploads/'.$value;
    }

    /**
     * 添加
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $reqParam = $this->request->param();

            validate(\app\validate\GoodsValidate::class)->scene('add')->check($reqParam);

            Db::startTrans();
            try {
                \app\model\Goods::create($reqParam);
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return failed_response($e->getMessage());
            }
            return success_response();
        }
        $category = \app\model\GoodsCategory::where('is_show','=',1)
            ->column('cate_name','id');
        $ret = [
            'category' => $category,
        ];
        return view('admin/goods/add', $ret);
    }

    /**
     * 修改
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit()
    {
        $id = $this->request->param('id');
        if ($this->request->isPost()) {

            $reqParam = $this->request->param();
            validate(\app\validate\GoodsValidate::class)->scene('edit')->check($reqParam);
            Db::startTrans();
            try {

                $goods = \app\model\Goods::find($id);

                $goods->goods_name      = $reqParam['goods_name'];
                $goods->goods_price     = $reqParam['goods_price'];
                $goods->discount_price  = $reqParam['discount_price'];
                $goods->goods_img       = $reqParam['goods_img'];
                $goods->goods_img_thumb = $reqParam['goods_img_thumb'];
                $goods->is_best         = $reqParam['is_best'];
                $goods->is_hot          = $reqParam['is_hot'];
                $goods->is_new          = $reqParam['is_new'];
                $goods->is_on_sale      = $reqParam['is_on_sale'];
                $goods->flower_combine  = $reqParam['flower_combine'];
                $goods->flower_moral    = $reqParam['flower_moral'];
                $goods->remark          = $reqParam['remark'];
                $goods->flower_package  = $reqParam['flower_package'];
                $goods->flower_stock    = $reqParam['flower_stock'];
                $goods->sort_id         = $reqParam['sort_id'];
                $goods->flower_use      = $reqParam['flower_use'];
                $goods->flower_material = $reqParam['flower_material'];
                if ($reqParam['is_promote'] == 2) {
                    $goods->is_promote = 2;
                    $goods->promote_price =  $reqParam['promote_price'];
                    $goods->promote_begin_time =  strtotime($reqParam['promote_begin_time']);
                    $goods->promote_end_time =  strtotime($reqParam['promote_end_time']);
                } else {
                    $goods->is_promote = 1;
                    $goods->promote_begin_time =  0;
                    $goods->promote_end_time =  0;
                    $goods->promote_price =  0;
                }
                $goods->save();
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return failed_response($e->getMessage());
            }
            return success_response();
        }
        $data = Db::table('goods')->find($id);

        // 分类
        $category = \app\model\GoodsCategory::where('is_show','=',1)
            ->column('cate_name','id');

        $introduceImg = \app\model\GoodsIntroduceImg::where(['goods_id' => $id, 'is_delete' => 1])->select();
        $ret = [
            'data'               => $data,
            'introduceImg'       => $introduceImg,
            'category'           => $category,
        ];

        return view('admin/goods/edit', $ret);
    }



    /**
     * 修改时删除多文件
     */
    public function editMultiFileDelete()
    {
        $id = $this->request->param('multi_img_id');
        GoodsIntroduceImg::where('id', $id)->update(['is_delete' => 2]);
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
        \app\model\Advertise::whereIn('id', $idArr)->update(['is_delete' => 2]);
        return success_response();

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
        $data = \app\model\Advertise::where('id', $id)->find();
        $isShow   = $data->is_show == 1 ? 2 : 1;
        $data->is_show = $isShow;
        $data->save();
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
        \app\model\Advertise::where('id','=', $id)->update(['sort_id' => $sortId ]);
        return success_response();
    }

}

<?php

namespace app\controller\admin;


use app\model\GoodsIntroduceImg;
use think\exception\ValidateException;
use think\facade\Db;

class User extends AdminBase
{
    /**
     * 列表
     * @return \think\response\View
     */
    public function lst()
    {
        $pageSize = input('page_size', 10); // 每一页默认显示的条数
        $curPage = input('page',1);
        $pageSizeSelect = page_size_select($pageSize); //生成下拉选择页数框
        $data = \app\model\User::search($pageSize);
        $pageShow = $data->render();
        $category = \app\model\GoodsCategory::where('is_show','=',1)
                ->column('cate_name','id');

        $ret = [
            'data'           => $data,
            'pageSizeSelect' => $pageSizeSelect,
            'pageSize'       => $pageSize,
            'pageShow'       => $pageShow,
            'category'       => $category,
            'page'           => $curPage
        ];
        return view('admin/user/lst', $ret);
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
                $goods->goods_img       = $reqParam['goods_img'];
                $goods->goods_img_thumb = $reqParam['goods_img_thumb'];

                $goods->is_on_sale      = $reqParam['is_on_sale'];
//                $goods->remark          = $reqParam['remark'];
                $goods->stock_num    = $reqParam['stock_num'];
//                $goods->sort_id         = $reqParam['sort_id'];
                $goods->goods_category_id      = $reqParam['goods_category_id'];
                $goods->goods_desc      = $reqParam['goods_desc'];
                $goods->seo_description      = $reqParam['seo_description'];

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


    public function topUp()
    {
        $userId = $this->request->param('user_id');
        $money  = $this->request->param('money');

        if (empty($userId) || empty($money)) {
            return failed_response("缺少参数");
        }


        if (!is_numeric($money)) {
            return failed_response("充值金额必须是数值类型");
        }
        $adminId = session('admin_user_id');

        $user = Db::table('user')
            ->where('id', $userId)
            ->find();
        if (empty($user)) {
            return failed_response("非法注入");
        }
        $curDate = date("Y-m-d H:i:s");

        Db::startTrans();
        try {
            // 更改金额
            Db::table("user")
                ->where('id','=', $userId)
                ->update([
                    'user_money' => ($user['user_money'] + $money),
                    'update_time' => date("Y-m-d H:i:s"),
                ]);

            // 操作交易记录 提现
            Db::table("transaction_record")
                ->insertGetId([
                    'user_id'           => $userId,
                    'admin_id'          => $adminId,
                    'operator_type'     => 2,
                    'money'             => $money,
                    'transaction_type'  => 1,
//                    'order_id'          => $orderId,
                    'create_time'       => $curDate,
                ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return failed_response($e->getMessage());
        }

        return success_response();


    }


    /**
     * 列表
     * @return \think\response\View
     */
    public function feedbackConsultLst()
    {
        $pageSize = input('page_size', 10); // 每一页默认显示的条数
        $pageSizeSelect = page_size_select($pageSize); //生成下拉选择页数框



        $where = [];
//        $where[] = ['is_delete', '=', 1];

        $account = input('content', '');

        if (!empty($account)) {
            $where[] = ['content', 'like', "%{$account}%"];
        }

        /*$add_begin_time = input('add_begin_time', '');
        $add_end_time = input('add_end_time', '');
        if (!empty($add_begin_time)) {
            $where[] = ['create_time', '>=', "$add_begin_time"];
        }

        if (!empty($add_begin_time)) {
            $where[] = ['create_time', '<=', "$add_end_time"];
        }*/


        $data = Db::table("user_feedback_consult")
            ->field([
                'user_feedback_consult.id',
                'user_feedback_consult.admin_id',
                'user_feedback_consult.is_read',
                'user_feedback_consult.create_time',
                'user_feedback_consult.add_type',
                'user_feedback_consult.content',
                'user.account as user_name',
            ])
            ->leftJoin("user",'user.id = user_feedback_consult.user_id')
            ->where($where)
            ->order('id','desc')
            ->paginate($pageSize);
//        dd($data);

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
        return view('admin/user/feedback_consult_lst', $ret);
    }


    /**
     * 添加
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function feedbackConsultAdd()
    {

        if($this->request->isPost()) {

            $adminId = session('admin_user_id');

            if (!$adminId) {
                return failed_response("请先登陆!",401);
            }
            $reqParam = $this->request->param();
            if (empty($reqParam["feedback_content"])) {
                return failed_response("反馈内容不能为空！");
            }

            $feedback = Db::table("user_feedback_consult")
                ->where('id', '=', $reqParam['comment_id'])
                ->find();
            $userId = $feedback['user_id'];

            Db::table("user_feedback_consult")
                ->insert([
                    "user_id" => $userId,
                    "admin_id" => $adminId,
                    "is_read" => 1,
                    "add_type" => 2,
                    "content" => $reqParam["feedback_content"],
                    "create_time" => date("Y-m-d H:i:s"),
                ]);

            return success_response();

        }

        return failed_response("非法攻击");


    }

}

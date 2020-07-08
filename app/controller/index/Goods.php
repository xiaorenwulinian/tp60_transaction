<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use app\service\CommonService;
use think\facade\Db;


class Goods extends IndexBase
{

    /**
     * 筛选商品
     * @return \think\response\View
     * @throws \think\db\exception\DbException
     */
    public function search()
    {
        $category_id = input('category_id');

        $where = [];
        $where[] = ['goods.audit_status', '=', 2];

        if (!empty($category_id)) {
            $where[] = ['goods.goods_category_id', '=', $category_id];
        }
        $count =  Db::table("goods")
            ->where($where)
            ->count();

        $goodsData = Db::table("goods")
            ->leftJoin('user','goods.publish_id=user.id')
            ->field('goods.*,user.account as user_account')
            ->where($where)
            ->order('goods.id','desc')
            ->paginate([
                'query'     => request()->param(),
                'list_rows' => 20, //每页数量
            ],false)
            ->each(function($item, $key){

                if (empty($item['goods_img'])) {
                    $item['goods_img'] = default_image();
                } else {
                    $item['goods_img'] = '/uploads/' . $item['goods_img'];
                }

                $item['nickname'] = 'think';
                return $item;
            });


        $pageShow = $goodsData->render();
        $ret = [
            'goodsData' => $goodsData,
            'pageShow' => $pageShow,
        ];
        return view("index/goods/search", $ret);
    }


    /**
     * 详情
     */
    public function detail()
    {
        $goodsId = input('goods_id');

        $goodsData = Db::table("goods")
            ->field('goods.*,goods_category.cate_name')
            ->leftJoin('goods_category','goods.goods_category_id=goods_category.id')
            ->where('goods.id', '=', $goodsId)
            ->find();
        $sellerInfo = Db::table('user')->find($goodsData['publish_id']);

//        dump($goodsData);
        $ret = [
            'goodsData' => $goodsData,
            'sellerInfo' => $sellerInfo
        ];
        return view("index/goods/detail", $ret);
    }


    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishLst()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $userId = session('user_id');

        $publishGoods = Db::table('goods')
            ->where('publish_type','=', 2)
            ->where('publish_id','=',$userId)
            ->select()
            ->toArray();
//        dump($publishGoods);

        foreach ($publishGoods as &$v) {
            if (empty($v['goods_img'])) {
                $v['goods_img'] = default_image();
            } else {
                $v['goods_img'] = '/uploads/' . $v['goods_img'];
            }
            $statusArr = [
                1 => '审核中',
                2 => '审核通过',
                3 => '审核拒绝',
            ];
            $v['audit_status_msg'] = $statusArr[$v['audit_status']];
        }

        $user = Db::table("user")->where('id', $userId)->find();

        $ret = [
            'user' => $user,

            'publishGoods' => $publishGoods,
        ];

        return view('index/goods/publish_lst', $ret);

    }


    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishAdd()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }
        $userId = session('user_id');

        $user = Db::table("user")->where('id', $userId)->find();

        $category = \app\model\GoodsCategory::where('is_show','=',1)
            ->column('cate_name','id');

        $ret = [
            'user' => $user,
            'category' => $category,
        ];

        return view('index/goods/publish_add', $ret);
    }

    /**
     * 发布商品保存
     */
    public function publishAddStore()
    {
        if (!session('user_id')) {
            return failed_response("/index/user/login",401);
        }

        $userId = session('user_id');

        $reqParam = $this->request->param();
        validate(\app\validate\GoodsValidate::class)->scene('userAdd')->check($reqParam);

        $user = Db::table("user")->where('id',$userId)->find();
        if ($user['vip_level_id'] == 0 || $user['vip_deadline'] < time() ) {
            return  failed_response("只有vip会员才能发布商品！");
        }

        $curDay =  date("Y-m-d");
        $publish = Db::table("publish_goods_record")
            ->where('publish_id','=',$userId)
            ->where('publish_time','=',$curDay)
            ->find();

        $commonService = new CommonService();
        $baseConfig = $commonService->getBaseConfigInfo();

        $limitNum = $baseConfig['publish_limit_num']; // 每天发布限制的数量

        if (empty($publish)) {
            $publish_num = 0;
        } else {
            $publish_num = $publish['publish_num'];
        }

        if ($publish_num >= $limitNum ) {
            return failed_response("发布数量超出限制，每天可发布{$limitNum}条！");
        }
        Db::startTrans();
        try {
            $reqParam['publish_id']   = $userId;
            $reqParam['publish_type'] = 2;


            // 自动审核
            if ($baseConfig['user_publish_audit'] == 2) {
                $reqParam['audit_status'] = 2;
                $reqParam['audit_time']   = time();
                $reqParam['audit_admin_id'] = 0;
            } else {
                $reqParam['audit_status'] = 1;
                $reqParam['audit_time']   = 0;
                $reqParam['audit_admin_id'] = 0;
            }

            $goods = \app\model\Goods::create($reqParam);

            $uniqueCode = $goods['id'] . '-' . date("Ymd") . '-' . StringTool::random(4);
            Db::table('goods')
                ->where('id',$goods['id'])
                ->update([
                    'goods_unique' => $uniqueCode
                ]);

            if (empty($publish)) {
                Db::table("publish_goods_record")
                    ->insert([
                        "publish_id"   => $userId,
                        "publish_time" => $curDay,
                        "publish_num"  => 1,
                        "create_time"  => date("Y-m-d H:i:s"),
                    ]);

            } else {
                Db::table("publish_goods_record")
                    ->where('publish_id','=',$userId)
                    ->where('publish_time','=',$curDay)
                    ->inc("publish_num",1)
                    ->update();;
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return failed_response($e->getMessage());
        }
        return success_response();
    }

    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishEdit()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect("/index/user/login");
        }

        $goodsId = input('goods_id');

        $user = Db::table("user")->where('id', $userId)->find();

        $category = \app\model\GoodsCategory::where('is_show','=',1)
            ->column('cate_name','id');

        $data = Db::table('goods')->find($goodsId);

        $introduceImg = \app\model\GoodsIntroduceImg::where(['goods_id' => $goodsId, 'is_delete' => 1])->select();

        $ret = [
            'user' => $user,
            'data'               => $data,
            'introduceImg'       => $introduceImg,
            'category'           => $category,
        ];

        return view('index/goods/publish_edit', $ret);
    }

    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishEditStore()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect("/index/user/login");
        }

        $id = input('id');

        $reqParam = $this->request->param();
        validate(\app\validate\GoodsValidate::class)->scene('userEdit')->check($reqParam);

        $commonService = new CommonService();
        $baseConfig = $commonService->getBaseConfigInfo();

        Db::startTrans();
        try {

            $goods = \app\model\Goods::find($id);

            $goods->goods_name          = $reqParam['goods_name'];
            $goods->goods_price         = $reqParam['goods_price'];
            $goods->goods_img           = $reqParam['goods_img'];
            $goods->goods_img_thumb     = $reqParam['goods_img_thumb'];

            $goods->stock_num           = $reqParam['stock_num'];
            $goods->goods_category_id   = $reqParam['goods_category_id'];
            $goods->goods_desc          = $reqParam['goods_desc'];

            // 自动审核
            if ($baseConfig['user_publish_audit'] == 2) {
                $goods->audit_status  = 2;
                $goods->audit_time  = time();
                $goods->audit_admin_id  = 0;
            } else {
                $goods->audit_status  = 2;
                $goods->audit_time  = 0;
                $goods->audit_admin_id  = 0;
            }

            $goods->save();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return failed_response($e->getMessage());
        }
        return success_response();
    }

}

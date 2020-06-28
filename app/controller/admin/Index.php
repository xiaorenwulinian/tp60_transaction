<?php

namespace app\controller\admin;


use think\facade\Db;
use think\facade\View;

class Index
{
    /**
     * 后台首页
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        /********** 取出当前管理员所拥有的前两级的权限 ************/
        // 取出当前管理员所有的权限
        $adminId = session('admin_user_id');
        $adminId = 1;
        if($adminId == 1){
            $sql = 'SELECT * FROM privilege WHERE is_display=0';
        }
        else{
            $sql = 'SELECT b.*
			  FROM role_privilege a
			   LEFT JOIN privilege b ON a.pri_id=b.id
			   LEFT JOIN admin_role c ON a.role_id=c.role_id
			    WHERE c.admin_id='.$adminId;
        }
        $pri =  Db::query($sql);
        foreach ($pri as $k=>$v) {
            if ($v['parent_id'] == 0) {
                foreach ($pri as $k1=>$v1) {
                    if ($v1['parent_id'] == $v['id']) {
                        foreach ($pri as $k2=>$v2) {
                            if ($v2['parent_id'] == $v1['id']) {
                                $v1['children'][] = $v2;
                            }
                        }
                        $v['children'][] = $v1;
                    }
                }
                $allMenu[] = $v;
            }
        }
        View::assign([
            'allMenu' => $allMenu,
        ]);
        return View::fetch('admin/index/index');
    }
    /**
     *  首页菜单，通过 include 包含
     */
    public function menu()
    {
//        return View::fetch('admin/index/menu');
    }

    /**
     * 首页主体部分
     * @return string
     * @throws \Exception
     */
    public function mainContent() {
        return View::fetch('admin/index/main_content');
    }
}

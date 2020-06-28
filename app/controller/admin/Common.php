<?php

namespace app\controller\admin;


use think\facade\Db;
use think\facade\Filesystem;

class Common extends AdminBase
{

    public function addUpload()
    {
        $reqParam = $this->request->param();
        $secondDir = $reqParam['second_dir'] ?? '';
        $fileType  = $reqParam['file_type'] ?? 1;  // 1 是文件，2 是图片
        $hasThumb  = $reqParam['has_thumb'] ?? 0; //  1。生成缩略图，其他不生成
        $file = request()->file('common_upload_file');
        try {
            if ($fileType == 2) {
                $fileExt = 'jpg,png,gif,jpeg';
            } else {
                $fileExt = 'jpg,png,gif,jpeg,rar,zip,avi,rmvb,3gp,flv,mp3,txt,doc,xls,ppt,pdf,xls,docx,xlsx,doc';
            }

            validate(['common_upload_file' => "fileSize:10000000|fileExt:{$fileExt}"])->check(['common_upload_file' => $file]);
            $savename = \think\facade\Filesystem::disk('uploads')->putFile($secondDir, $file);
            $thumbName = $savename;
            if ($hasThumb == 1) {
                // topthink/think-image tp6.0 图片处理尚未有文档
            }
            $ret = [
                'file_path' => $savename,
                'file_path_thumb' => $thumbName
            ];
        } catch (\think\exception\ValidateException $e) {
            return failed_response($e->getMessage());
        }
        return success_response($ret);

    }

    public function addMultiUpload()
    {
        $reqParam = $this->request->param();
        $secondDir = $reqParam['second_dir'] ?? '';
        $fileType  = $reqParam['file_type'] ?? 1;  // 1 是文件，2 是图片
        $hasThumb  = $reqParam['has_thumb'] ?? 0; //  1。生成缩略图，其他不生成
        $file = request()->file('common_upload_multi_file');
        try {
            if ($fileType == 2) {
                $fileExt = 'jpg,png,gif,jpeg';
            } else {
                $fileExt = 'jpg,png,gif,jpeg,rar,zip,avi,rmvb,3gp,flv,mp3,txt,doc,xls,ppt,pdf,xls,docx,xlsx,doc';
            }

            validate(['common_upload_multi_file' => "fileSize:10000000|fileExt:{$fileExt}"])->check(['common_upload_multi_file' => $file]);
            $savename = \think\facade\Filesystem::disk('uploads')->putFile($secondDir, $file);
            $thumbName = $savename;
            if ($hasThumb == 1) {
                // topthink/think-image tp6.0 图片处理尚未有文档
            }
            $ret = [
                'file_path' => $savename,
                'file_path_thumb' => $thumbName
            ];
        } catch (\think\exception\ValidateException $e) {
            return failed_response($e->getMessage());
        }
        return success_response($ret);

    }

    /**
     * 添加时删除图片
     * @return \think\response\Json
     */
    public function addDeleteFile()
    {
        $file_path = input('file_path');
        $file_path_thumb = input('file_path_thumb');
        if($file_path || $file_path_thumb) {
            $driver = \think\facade\Filesystem::disk('uploads');
            if ($driver->has($file_path)) {
                $driver->delete($file_path);
            }
            if ($driver->has($file_path_thumb)) {
                $driver->delete($file_path_thumb);
            }
            return  success_response();
        }
        return failed_response('no image ');
    }


    /**
     * 修改时上传文件
     * @return \think\response\Json
     */
    public function editUpload()
    {
        $reqParam = $this->request->param();
        $secondDir = $reqParam['second_dir'] ?? '';
        $fileType  = $reqParam['file_type'] ?? 1;  // 1 是文件，2 是图片
        $hasThumb  = $reqParam['has_thumb'] ?? 0; //  1。生成缩略图，其他不生成
        $file = request()->file('common_upload_file');
        try {
            if ($fileType == 2) {
                $fileExt = 'jpg,png,gif,jpeg';
            } else {
                $fileExt = 'jpg,png,gif,jpeg,rar,zip,avi,rmvb,3gp,flv,mp3,txt,doc,xls,ppt,pdf,xls,docx,xlsx,doc';
            }

            validate(['common_upload_file' => "fileSize:10000000|fileExt:{$fileExt}"])->check(['common_upload_file' => $file]);
            $savename = \think\facade\Filesystem::disk('uploads')->putFile($secondDir, $file);
            $thumbName = $savename;
            if ($hasThumb == 1) {
                // topthink/think-image tp6.0 图片处理尚未有文档
            }
            $ret = [
                'file_path' => $savename,
                'file_path_thumb' => $thumbName
            ];
        } catch (\think\exception\ValidateException $e) {
            return failed_response($e->getMessage());
        }
        return success_response($ret);

    }

    /**
     * 修改时删除图片
     * @return \think\response\Json
     */
    public function editDeleteFile()
    {
        /*
         *  保留备份
        $file_path = input('file_path','');
        $file_path_thumb = input('file_path_thumb', '');
        if($file_path || $file_path_thumb) {
            $driver = \think\facade\Filesystem::disk('uploads');
            if ($driver->has($file_path)) {
                $driver->delete($file_path);
            }
            if ($driver->has($file_path_thumb)) {
                $driver->delete($file_path_thumb);
            }
            return  success_response();
        }
        return failed_response('no image ');
        */
    }



}

<?php
// 应用公共文件

if (!function_exists('success_response')) {
    /**
     * 成功返回json
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \think\response\Json
     */
    function success_response($data = [], $message = 'success', $code = 0)
    {
        $ret = [
            'code'      => $code,
            'msg'       => $message,
            'timestamp' => time(),
            'payload'   => $data
        ];
        $header = [];
        return json($ret, 200, $header);
    }
}

if (!function_exists('failed_response')) {
    /**
     * 失败返回json
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \think\response\Json
     */
    function failed_response($message = '请求失败', $code = 1)
    {
        $ret = [
            'code'      => $code,
            'msg'       => $message,
            'timestamp' => time(),
            'payload'   => []
        ];
        $header = [];
        return json($ret, 200, $header);
    }
}

if (!function_exists('page_size_select')) {
    /**
     * 生成页码跳转
     * @param int $page_size 每页显示条数
     * @return string
     */
    function page_size_select($page_size = 0){
        $str  = '<select class="form-control page_size_select">';
        $all_page  = [
            1, 2,5,10,20,50,100
        ];
        foreach ($all_page as $cur_page) {
            $has_selected = $page_size == $cur_page ? "selected='selected'" : '';
            $str .= "<option value='{$cur_page}' {$has_selected}>{$cur_page}条/页</option>";
        }
        $str .= '</select>';
        return $str;
    }
}

if (!function_exists('show_image')) {
    /**
     * 图片展示
     * @param string $url
     * @param string $width
     * @param string $height
     * @param string $alt
     * @return string
     */
    function show_image($url = '', $width = '', $height ='' , $alt='' ) {
        if (empty($url)) {
            $url = "/favicon.ico";
        } else {
            $url = '/uploads/' . $url;
        }
        if (!empty($width)) {
            $width = "width = '{$width}'";
        }

        if (!empty($width)) {
            $height = "height = '{$height}'";
        }
        if (empty($alt)) {
            $alt  = '图片加载中';
        }
        return "<img src='{$url}' $width $height alt='{$alt}' />";
    }

}


if (!function_exists('show_image_full')) {
    /**
     * 图片展示
     * @param string $url
     * @param string $width
     * @param string $height
     * @param string $alt
     * @return string
     */
    function show_image_full($url = '', $width = '', $height ='' , $alt='' ) {
        if (!empty($width)) {
            $width = "width = '{$width}'";
        }

        if (!empty($width)) {
            $height = "height = '{$height}'";
        }
        if (empty($alt)) {
            $alt  = '图片加载中';
        }
        return "<img src='{$url}' $width $height alt='{$alt}' />";
    }

}

if (!function_exists('default_image')) {
    /**
     * 用户默认的图片
     * @return string
     */
    function default_image($type = 'goods') {

        return "/static/index/img/nopic.jpg";
    }

}


if (!function_exists('show_goods_image_no_upload')) {
    /**
     * 图片展示图片上传的路径，不加 /upload
     * @param string $url
     * @param string $width
     * @param string $height
     * @param string $alt
     * @return string
     */
    function show_goods_image_no_upload($url = '', $width = '', $height ='' , $alt='' ) {

        if (empty($url)) {
            $url = default_image();
        } else {
            $url = "/uploads/" . ltrim($url,'/');
        }

        if (!empty($width)) {
            $width = "width = '{$width}'";
        }

        if (!empty($width)) {
            $height = "height = '{$height}'";
        }
        if (empty($alt)) {
            $alt  = '图片加载中';
        }
        return "<img src='{$url}' $width $height alt='{$alt}' />";
    }

}

if (!function_exists('show_goods_image_has_upload')) {
    /**
     * 图片展示图片上传的路径，加 /upload
     * @param string $url
     * @param string $width
     * @param string $height
     * @param string $alt
     * @return string
     */
    function show_goods_image_has_upload($url = '', $width = '', $height ='' , $alt='' ) {
        if (!empty($width)) {
            $width = "width = '{$width}'";
        }

        if (!empty($width)) {
            $height = "height = '{$height}'";
        }
        if (empty($alt)) {
            $alt  = '图片加载中';
        }
        return "<img src='{$url}' $width $height alt='{$alt}' />";
    }

}

if (!function_exists('show_goods_image_all')) {
    /**
     * 图片展示图片上传的路径，加 /upload
     * @param string $url
     * @param string $width
     * @param string $height
     * @param string $alt
     * @return string
     */
    function show_goods_image_all($url = '', $width = '', $height ='' , $alt='' ) {

        if (empty($url)) {
            $url = default_image();
        } else {
            $url = ltrim($url,'/');
            if (substr($url,0,7) == "uploads") {
                $url = ltrim(substr($url,7),'/');
            }
            $url = "/uploads/" . ltrim($url,'/');
        }


        if (!empty($width)) {
            $width = "width = '{$width}'";
        }

        if (!empty($width)) {
            $height = "height = '{$height}'";
        }
        if (empty($alt)) {
            $alt  = '图片加载中';
        }
        return "<img src='{$url}' $width $height alt='{$alt}' />";
    }

}

// getDefaultImg


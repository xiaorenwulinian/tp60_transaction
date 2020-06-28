<?php

namespace app\common\tools;


use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FileTool
{
    /**
     * 返回文件夹路径，以分隔符结尾
     * @param string $path
     * @return string|string[]
     */
    public static function dirPath($path = '')
    {
        if (empty($path)) {
            return '';
        }
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/') {
            $path .= '/';
        }
        return $path;
    }

    /*
     * 文件的后缀
     */
    public static function fileExtension($file = '')
    {
        $fileArr = explode('.', $file);
        return end($fileArr);
    }

    /**
     *  获取当前文件夹下的所有文件，不递归
     */
    public static function getDir($dir)
    {
        $files = @scandir($dir);
        $fileArr = [];
        foreach ($files as $v) {
            if ($v != '../' || $v != './') {
                array_push($fileArr, $v);
            }
        }
        return $fileArr;
    }

    public static function  dirList($path)
    {

    }




    /*
文件按行导出
*/
   public function importFile()
    {
        set_time_limit(0);
        $filename = './kuaishou.txt';
        $fh = fopen($filename, 'r');
        $content_arr = [];
        while (! feof($fh)) {
            $line = fgets($fh);
            $line = trim($line);
            if(empty($line)){
                continue;
            }
            $content_arr[] = $line;
        }
    }

    /**
     * 获取客户端ip,转化成整数
     *
     * @return string
     */
    public function ip()
    {
        $ip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '0.0.0.0';
        $ip = ip2long($ip) ? ip2long($ip) : ip2long('0.0.0.0');
        return $ip;
    }

//获取文件夹中的文件,含目录
       public function get_dir_file_list($path, $ext='', $list = []) {
            $path = dir_path($path);
            $files = glob($path.'*');
            foreach ($files as $key => $value) {
                $file_exe = file_exe($files);
                if (!$ext || preg_match("/\.$ext/i", $file)) {
                    $list[] = $v;
                    if (is_dir($v)) {
                        $list = get_dir_file_list($v, $ext, $list);
                    }
                }
            }
        }
}

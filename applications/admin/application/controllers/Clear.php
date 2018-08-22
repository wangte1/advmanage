<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clear extends MY_Controller{

    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $path = "./excel";
        self::deldir($path);
        $this->success('清除成功', '/');
    }
    
    private static function deldir($path){
        $dh = opendir($path);
        while(($d = readdir($dh)) !== false){
            //排除.或..
            if($d == '.' || $d == '..'){
                continue;
            }
            $tmp = $path.'/'.$d;
            if(!is_dir($tmp)){//如果为文件
                unlink($tmp);
            }else{//如果为目录
                self::deldir($tmp);
            }
        }
        closedir($dh);
        if($path != './excel'){
            rmdir($path);
        }
    }
}
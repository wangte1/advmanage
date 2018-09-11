<?php
use OSS\OssClient;
use OSS\Core\OssException;
class File extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model([
            "Model_file_oss_task" => "Mfile_oss_task"
        ]);
    }
    /**
     * 指定上传文件的服务器端程序
     */
    public function upload(){
        $file_dir = $this->input->get('dir') == 'image' ? 'image/' : 'files/';
        $config = array(
                        'upload_path'   => 'uploads/'.$file_dir,
                        'allowed_types' => '*',
                        // 'max_size'     => 1024*5,
                        // 'max_width'    => 2000,
                        // 'max_height'   => 2000,
                        'encrypt_name' => TRUE,
                        'remove_spaces'=> TRUE,
                        'use_time_dir'  => TRUE,      //是否按上传时间分目录存放
                        'time_method_by_day'=> TRUE, //分目录存放的方式：按天
        );
        $this->load->library('upload', $config);
        if ( !$this->upload->do_upload('imgFile') ) {
            if(!$this->upload->do_upload('file')){
                $error = $this->upload->display_errors();
                echo json_encode(array('error' => 1, 'message' => '上传错误！'.$error));
            }
        }
        $data = $this->upload->data();
        $name = $file_dir.$data['file_name'];
        $this->add_task($name);
        echo json_encode(array('error' => 0, 'url' => '/uploads/'.$name));
        exit();
    }
    
    public function add_task($path){
        $allpath = C('root.path').$path;
        $this->Mfile_oss_task->create(['local' => $allpath]);
    }
    
    /**
     * 移动至oss
     * @param unknown $path
     */
    private function moveToOss($path){
        $user = $this->data['userInfo'];
        $loclFileName = './uploads/'.$path;
        $bucket = "timedia";
        $ossFileName = 'uploads/'.$path;
        $res = $this->upToOss($bucket, $ossFileName, $loclFileName);
        if($res['code'] != 1){
            $token = decrypt($this->token);
            $this->write_log($user['id'], 1, '文件：'.$ossFileName.'上传oss失败！原因：'.$res['msg']);
        }
    }
    
    function upToOss($bucket, $ossFileName, $loclFileName){
        if(empty($bucket)){
            return ['code' => 0, 'msg' => 'bucket不能为空'];
        }
        if(empty($ossFileName)){
            return ['code' => 0, 'msg' => '存储名称不能为空'];
        }
        if(!file_exists($loclFileName)){
            return ['code' => 0, 'msg' => '文件不存在'];
        }
        try {
            $config = C('aliyunoss');
            $ossclient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint_out']);
            try{
                $ossclient->uploadFile($bucket, $ossFileName, $loclFileName);
                return ['code' => 1, 'msg' => 'ok'];
            }catch (OssException $e){
                return ['code' => 0, 'msg' => $e->getErrorMessage()];
            }
        }catch (OssException $e){
            return ['code' => 0, 'msg' => $e->getErrorMessage()];
        }
    }
    
    
}

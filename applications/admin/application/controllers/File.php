<?php
class File extends MY_Controller {
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
        echo json_encode(array('error' => 0, 'url' => '/uploads/'.$file_dir.$data['file_name']));
        exit();
    }
    
    
}

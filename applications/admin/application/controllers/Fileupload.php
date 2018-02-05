<?php


class Fileupload extends MY_Controller {
    /**
     * 指定上传文件的服务器端程序
     */
    public function upload(){
        $file_dir = $this->input->get('dir') == 'image' ? 'image/' : 'files/';
        $config = array(
                        'upload_path'   => 'uploads/'.$file_dir,
                        'allowed_types' => 'gif|jpg|jpeg|png|bmp|swf|flv|doc|docx|xls|xlsx|ppt',
                        // 'max_size'     => 1024*5,
                        // 'max_width'    => 2000,
                        // 'max_height'   => 2000,
                        'encrypt_name' => TRUE,
                        'remove_spaces'=> TRUE,
                        'use_time_dir'  => TRUE,      //是否按上传时间分目录存放
                        'time_method_by_day'=> TRUE, //分目录存放的方式：按天
        );
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload('file')){
            $error = $this->upload->display_errors();
            echo json_encode(array('error' => 1, 'message' => '上传错误！'.$error));
        } else {
            $data = $this->upload->data();
            $imgsrc =  $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$file_dir.$data['file_name'];
            $imgdst =  $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$file_dir.$data['file_name'];
            $this->image_png_size_add($imgsrc, $imgdst);
            echo json_encode(array('error' => 0, 'url' => '/uploads/'.$file_dir.$data['file_name']));
        }
        exit();
    }
    
    
    /**
     * desription 压缩图片
     * @param sting $imgsrc 图片路径
     * @param string $imgdst 压缩后保存路径
     */
    function image_png_size_add($imgsrc,$imgdst){
    	list($width,$height,$type)=getimagesize($imgsrc);
    	$new_width= ($width>800?800:$width)*0.9;
    	$new_height=($height>600?600:$height)*0.9;
    	switch($type){
    		case 1:
    		$giftype=check_gifcartoon($imgsrc);
    		if($giftype){
    			header('Content-Type:image/gif');
    			$image_wp=imagecreatetruecolor($new_width, $new_height);
    			$image= imagecreatefromgif($imgsrc);
    			imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    			imagejpeg($image_wp, $imgdst,75);
    			imagedestroy($image_wp);
    		}
    		break;
    		case 2:
    		header('Content-Type:image/jpeg');
    		$image_wp=imagecreatetruecolor($new_width, $new_height);
    		$image= imagecreatefromjpeg($imgsrc);
    		imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    		imagejpeg($image_wp, $imgdst,75);
    		imagedestroy($image_wp);
    		break;
    		case 3:
    		header('Content-Type:image/png');
    		$image_wp=imagecreatetruecolor($new_width, $new_height);
    		$image= imagecreatefrompng($imgsrc);
    		imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    		imagejpeg($image_wp, $imgdst,75);
    		imagedestroy($image_wp);
    		break;
    	}
    }
    
}

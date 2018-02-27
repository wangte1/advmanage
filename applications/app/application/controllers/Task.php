<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yangxiong
 * 867332352@qq.com
 */
class Task extends MY_Controller {
    
    private $token;
    
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_token' => 'Mtoken',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses_orders' => 'Mhouses_orders',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        ]);
    }
    
    /**
     * 派工任务列表接口
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        $status = (int) $this->input->get_post('status');
        
        $where = ['A.is_del' => 0];
        if(!$size) $size = $pageconfig['per_page'];
        if($status == 4) {
        	$where['A.status'] = $status;
        }else {
        	$where['A.status<>'] = 4;
        }
        
        $token = decrypt($this->input->get_post('token'));
        $where['A.charge_user'] = $token['user_id'];
        
        $list = $this->Mhouses_assign->get_join_lists($where,['A.id'=>'desc'],$size,($page-1)*$size);
 
        $this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
    
    /**
     * 根据任务id获取任务信息
     */
    public function get_info() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$where['A.id'] = $assignId;
    	$list = $this->Mhouses_assign->get_join_lists($where);
    	$this->return_json(['code' => 1, 'data' => json_encode($list)]);
    }
    
    /**
     * 确认任务
     */
    public function confirm() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$res = $this->Mhouses_assign->update_info(['status' => 3], ['id' => $assignId]);
    	if(!$res){
    		$this->return_json(['code' => 1, 'msg' => '确认成功!']);
    	}
    	
    	$this->return_json(['code' => 0, 'msg' => '确认失败，请联系管理员!']);
    }
    
    /**
     * 获取任务中的点位列表
     */
    public function get_point_list() {
    	
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page = (int) $this->input->get_post('page') ? : '1';
    	$size = (int) $this->input->get_post('size');
    	
    	if(!$size) $size = $pageconfig['per_page'];
    	
    	$assignId = (int) $this->input->get_post('assignId');
    	$where['id'] = $assignId;
    	$assign_list = $this->Mhouses_assign->get_one('id, order_id, houses_id, ban', $where);
    	$order_list = $this->Mhouses_orders->get_one('id, point_ids', ['id' => $assign_list['order_id']]);
    	
    	$where_point['in']['A.id'] = explode(',', $order_list['point_ids']);
    	$where_point['A.houses_id'] = $assign_list['houses_id'];
    	if($assign_list['ban']) {
    		$where_point['A.ban'] = $assign_list['ban'];
    	}
    	$points = $this->Mhouses_points->get_points_lists($where_point,[],$size,($page-1)*$size);
    	
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['in'] = array("point_id"=>array_column($points,"id"));
    		$where['order_id'] = $assign_list['order_id'];
    		$where['assign_id'] = $assignId;
    		$where['assign_type'] = 1;	//暂时只取上画
    		$where['type'] = 1;
    		$data['images'] = $this->Mhouses_order_inspect_images->get_lists("*",$where);
    	}
    	
    	$list = array();
    	foreach ($points as $key => $val) {
    		$val['image'] = array();
    		if($data['images']){
    			foreach($data['images'] as $k=>$v){
    				if($val['id'] == $v['point_id']){
    					$val['image'][] = $v;
    				}
    			}
    		}
    		$list[] = $val;
    	}
    	
    	$this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
    
    /**
     * 获取点位详情
     */
    public function get_point_detail() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$pointId = (int) $this->input->get_post('pointId');
    	
    	$where_point['A.id'] = $pointId;
    	$points = $this->Mhouses_points->get_points_lists($where_point)[0];
    	$this->return_json(['code' => 1, 'data' => json_encode($points)]);
    }
    
    /**
     * 指定上传文件的服务器端程序
     */
    public function upload(){
    	
    	$file_dir = $this->input->get('dir') == 'image' ? 'image/' : 'files/';
    	$config = array(
    			'upload_path'   => '../../admin/uploads/'.$file_dir,
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
    		$this->return_json(array('error' => 1, 'message' => '上传错误！'.$error));
    	} else {
    		$data = $this->upload->data();
    		$imgsrc =  '../../admin/uploads/'.$file_dir.$data['file_name'];
    		$imgdst =  '../../admin/uploads/'.$file_dir.$data['file_name'];
    		$this->image_png_size_add($imgsrc, $imgdst);
    		$this->return_json(array('error' => 0, 'url' => '/uploads/'.$file_dir.$data['file_name']));
    	}
    }
    
    /**
     * 保存上传的图片信息
     */
    public function upload_save() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$pointId = (int) $this->input->get_post('pointId');
    	$imgUrl = (int) $this->input->get_post('imgUrl');
    	
    	$assign_type = 1;	//暂时只添加上画的
    	$where = array('order_id' => $order_id, 'assign_id' => $assign_id, 'point_id' => $key, 'type' => 1);
    	$img = $this->Mhouses_order_inspect_images->get_one('*', $where);
    	
    	//如果是修改验收图片，则先删除该订单下所有验收图片，再重新添加
    	if ($img) {
    		$this->Mhouses_order_inspect_images->delete($where);
    	}
    	
    	$insert_data['order_id'] = $order_id;
    	$insert_data['assign_id'] = $assign_id;
    	$insert_data['assign_type'] = $assign_type;
    	$insert_data['point_id'] = $key;
    	$insert_data['front_img'] = $v;
    	$insert_data['back_img'] = '';
    	$insert_data['type'] = 1;
    	$insert_data['create_user'] = $insert_data['update_user'] = $data['userInfo']['id'];
    	$insert_data['create_time'] = $insert_data['update_time'] = date('Y-m-d H:i:s');
    	$this->Mhouses_order_inspect_images->create($insert_data);
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghua
 */
class Task extends MY_Controller {
    
    private $token;
    
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_token' => 'Mtoken',
            'Model_admins' => 'Madmins',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_work_order' => 'Mhouses_work_order',
            'Model_houses_work_order_detail' => 'Mhouses_work_order_detail',
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
        $where = [];
        $token = decrypt($this->token);
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = intval($this->input->get_post("page",true)) ?  : 1;
        $size = (int) $this->input->get_post('size');
        if(!$size){$size = $pageconfig['page'];}
        $data['assign_type'] = $assign_type = $this->input->get('assign_type') ? : 1;
        $where['type'] = $assign_type;
        
        //$where['charge_user'] = $token['user_id']; //临时关闭
        $data['customer_list'] = $customer_list = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);        
        
        $where['is_del'] = 0;
        
        $data['list'] = $list = $this->Mhouses_work_order->get_lists("*", $where, ['create_time'=>'desc'], $size, ($page-1)*$size);
        if($data['list']){
            //查询这些工单的订单信息
            $order_ids= array_unique(array_column($data['list'], 'order_id'));
            $order_list = $this->Mhouses_orders->get_lists('id, order_code, order_type, customer_id', ['in' => ['id' => $order_ids]]);
            
            foreach ($list as $k => $v){
                foreach ($order_list as $key => $val){
                    if($val['id'] == $v['order_id']){
                        $data['list'][$k]['order_code'] = $val['order_code'];
                        $data['list'][$k]['customer_name'] = "";
                        $data['list'][$k]['order_type'] = $val['order_type'];
                    }
                }
            }
            $list = $data['list'];
            foreach ($list as $k => $v){
                foreach ($customer_list as $key => $val){
                    if($v['customer_id'] == $val['id']){
                        $data['list'][$k]['customer_name'] = $val['name'];
                    }
                }
            }
        }
        $where = [];
        $where = ['is_del' => 1];
        $where['in'] = ['group_id' => [4,6]];
        $tmp_user = $this->Madmins->get_lists('id,fullname', $where);
        foreach ($data['list'] as $k => &$v){
            foreach ($tmp_user as $key => $val){
                if($v['charge_user'] == $val['id']) $data['list'][$k]['fullname'] = $val['fullname'];
            }
        }
        $this->return_json(['code' => 1, 'data' => $data['list'], 'page' => $page, 'msg' => 'ok']);
    }
    
    /**
     * 根据任务id获取任务信息
     */
    public function get_info() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$where['A.id'] = $assignId;
    	$list = $this->Mhouses_assign->get_join_lists($where);
    	$this->return_json(['code' => 1, 'data' => $list]);
    }
    
    /**
     * 确认任务
     */
    public function confirm() {
    	$data = $this->data;
    	$token = decrypt($this->token);
    	$id = (int) $this->input->get_post('id');
    	//获取任务详细
    	$info = $this->Mhouses_work_order->get_one('*', ['id' => $id, 'status' => 0]);
    	if(!$info)  $this->return_json(['code' => 0, 'msg' => "任务不存在或已确认"]);
    	$where['is_del'] = 0;
    	//统计父级订单
    	$assign_type = $info['type'];
    	$order_id = $info['order_id'];
    	if($assign_type == 3) {
    	    //换画
    	    $tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    	    //1上画，2下画
    	    $tmp_moudle = $this->Mhouses_orders;
    	}
    	
	    //更新工单为已确认
	    $up['status'] = 1;
	    $res = $this->Mhouses_work_order->update_info($up, ['id' => $id]);
	    
	    if($res) {
	        //统计这个子订单是否全部已经确认
	        $count = $this->Mhouses_work_order->count(['status' => 0, 'type' => $assign_type, 'order_id' => $order_id]);
	        if($count == 0){
	            
	            $res = $tmp_moudle->update_info(['assign_status' => 3], ['id' => $order_id]);
	            if($res) $this->write_log($token['user_id'], 2, "更新派单状态为已确认失败：".$order_id);
	            
	            $fatherOrder = $tmp_moudle->get_one('pid', ['id' => $order_id]);
	            if($fatherOrder['pid']){
	                //下画则更新为7
	                $fup = ['assign_status' => 3];
	                if($assign_type != 2){
	                    $fup['order_status'] = 4;
	                }else{
	                    $fup['order_status'] = 7;
	                }
	                
	                $res = $tmp_moudle->update_info($fup, ['id' => $fatherOrder['pid']]);
	                if($res) $this->write_log($token['user_id'], 2, "更新派单状态为已确认失败：".$fatherOrder['pid']);
	            }
	        }
	        
	        $this->return_json(['code' => 1, 'msg' => "确认派单成功！"]);
	        $this->write_log($token['user_id'], 1, "确认派单：".$this->input->post('id'));
	    }
	    
	    $this->return_json(['code' => 0, 'msg' => "确认派单失败，请重试或联系管理员！"]);
    	
    }
    
    /**
     * 获取任务中的点位列表
     */
    public function get_point_list() {
    	
        $data = $this->data;
        $id = $this->input->get_post('id');
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = intval($this->input->get_post("page",true)) ?  : 1;
        $size = (int) $this->input->get_post('size');
        if(!$size){$size = $pageconfig['page'];}
        //获取这个工单的点位列表
        $workOrderPoint = $this->Mhouses_work_order_detail->get_lists('id,pid,point_id,status,no_img,pano_img', ['pid' => $id], [], $size, ($page-1)*$size);
        if(!$workOrderPoint) $this->return_json(['code' => 0, 'data' => [], 'page' => $page]);
        $where_p['in']['A.id'] = array_column($workOrderPoint, 'point_id');
        //投放点位
        $selected_points = $this->Mhouses_points->get_points_lists($where_p);
        $code = 0;
        if(count($selected_points)) $code = 1;
    	foreach ($workOrderPoint as $k => &$v){
    	    foreach ($selected_points as $key => $val){
    	        if($val['id'] == $v['point_id']){
    	            foreach ($val as $key1 => $val1){
    	                if($key1 != 'id'){
    	                   $v[$key1] = $val1;
    	                }
    	            }
    	        }
    	    }
    	}
    	unset($selected_points);
    	$this->return_json(['code' => 1, 'data' => $workOrderPoint, 'page' => $page]);
    }
    
    /**
     * 获取点位详情
     */
    public function get_point_detail() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$pointId = (int) $this->input->get_post('pointId');
    	
    	$tmpList = $this->Mhouses_assign->get_one('order_id,type', ['id' => $assignId]);
    	
    	$where_point['A.id'] = $pointId;
    	$points = $this->Mhouses_points->get_points_lists($where_point)[0];
    	
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['point_id'] = $pointId;
    		$where['order_id'] = $tmpList['order_id'];
    		$where['assign_id'] = $assignId;
    		$where['assign_type'] = $tmpList['type'];
    		$where['type'] = 1;
    		$tmpImg = $this->Mhouses_order_inspect_images->get_one("front_img",$where);
    	}
    	
    	if(isset($tmpImg['front_img'])) {
    		$points['image'] = $tmpImg['front_img'];
    	}
    	
    	$this->return_json(['code' => 1, 'data' => $points]);
    }
    
    /**
     * 指定上传文件的服务器端程序
     */
    public function upload(){
    	
    	$file_dir = 'image/';
    	$config = array(
    	    'upload_path'   => '../../admin/uploads/'.$file_dir,
    			'allowed_types' => 'gif|jpg|jpeg|png',
    			'max_size'     => 1024*3,
    			'max_width'    => 2000,
    			'max_height'   => 2000,
    			'encrypt_name' => TRUE,
    			'remove_spaces'=> TRUE,
    			'use_time_dir'  => TRUE,      //是否按上传时间分目录存放
    			'time_method_by_day'=> TRUE, //分目录存放的方式：按天
    	);
    	$this->load->library('upload', $config);
    	
    	if ( ! $this->upload->do_upload('file')){
    		$error = $this->upload->display_errors();
    		$this->return_json(array('code' => 0, 'message' => '上传错误！'.$error));
    	} else {
    		$data = $this->upload->data();
    		$this->return_json(array('code' => 1, 'url' => '/uploads/'.$file_dir.$data['file_name']));
    	}
    }
    
    /**
     * 保存上传的图片信息
     */
    public function upload_save() {
    	$id = (int) $this->input->post('id');
    	$img_url = $this->input->post('img_url');
    	$info = $this->Mhouses_work_order_detail->get_one('pid', ['id' => $id, 'status' => 0]);
    	if($info){
    	    $up = [
    	        'status' => 1,
    	        'no_img' => $img_url
    	    ];
    	    $res = $this->Mhouses_work_order_detail->update_info($up, ['id' => $id, 'status' => 0]);
    	    if(!$res){
    	        $this->return_json(['code' => 0, 'msg' => '操作失败']);
    	    }
    	    $this->Mhouses_work_order->update_info(['incr' => ['finish' => 1]], ['id' => $info['pid']]);
    	    $pinfo = $this->Mhouses_work_order->get_one('order_id,type', $info['pid']);
    	    $this->checkDoAllHasFinish($pinfo['order_id'], $pinfo['type']);
    	    $this->return_json(['code' => 1, 'msg' => '操作成功']);
    	}
    	$this->return_json(['code' => 0, 'msg' => '已审核或点位不存在']);
    }
    
    /**
     * 更新父订单与子订单的订单状态
     * @param number $order_id
     * @param number $type
     */
    private function checkDoAllHasFinish($order_id= 0, $type = 0){
        //根据当前的orderid找到父orderid
        if($type == 3) {
            $tmp_moudle = $this->Mhouses_changepicorders;
        }else {
            $tmp_moudle = $this->Mhouses_orders;
        }
        $fatherOrder = $tmp_moudle->get_one('pid,order_status', ['id' => $order_id]);
        if($fatherOrder){
            //统计所有子订单id
            $sonList = $tmp_moudle->get_one('id', ['pid' => $fatherOrder['pid']]);
            if($sonList){
                //提取ids
                $ids = array_column($sonList, 'id');
                $count = $this->Mhouses_work_order_detail->count(['in' => ['pid' => $ids], 'status' => 0]);
                if(!$count){
                    //表示全部都已经完成上传，需要更订单状态
                    $order_status = $fatherOrder['order_status'];
                    //如果是下画则派单更新订单为已下画,改为7
                    $up['order_status'] = 7;
                    //如果是上画则更新订单为投放中
                    if($order_status == 4) $up['order_status'] = 6;
                    //更新父订单
                    $tmp_moudle->update_info($up, ['id' => $fatherOrder['pid']]);
                    //更新子订单
                    $tmp_moudle->update_info($up, ['in' => ['id' => $ids] ]);
                }
            }
        }
    }
    
    /**
     * 保存上传的全景、远景图片信息
     */
    public function upload_save2() {
        $id = (int) $this->input->post('id');
        $img_url = $this->input->post('img_url2');
        $info = $this->Mhouses_work_order_detail->get_one('pid', ['id' => $id]);
        if($info){
            $up = [
                'pano_img' => $img_url
            ];
            $res = $this->Mhouses_work_order_detail->update_info($up, ['id' => $id]);
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败']);
            }
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
        $this->return_json(['code' => 0, 'msg' => '点位不存在']);
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
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
            'Model_houses_tour_points' => 'Mhouses_tour_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_work_order' => 'Mhouses_work_order',
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_houses_points_format' => 'Mhouses_points_format',
            'Model_houses_work_order_detail' => 'Mhouses_work_order_detail',
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses_orders' => 'Mhouses_orders',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        ]);
        $this->load->library([
            'MyRedis' => 'redis'
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
        if(!$size){$size = $pageconfig['per_page'];}
        $data['assign_type'] = $assign_type = $this->input->get_post('assign_type') ? : 1;
        $where['type'] = $assign_type;
        
        if($token['user_id'] != 1){
            $where['charge_user'] = $token['user_id']; //临时关闭
        }
        
        $data['customer_list'] = $customer_list = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);        
        
        $where['is_del'] = 0;
        
        $data['list'] = $list = $this->Mhouses_work_order->get_lists("*", $where, ['create_time'=>'desc'], $size, ($page-1)*$size);
        
        if($data['list']){
            //查询这些工单的订单信息
            $order_ids= array_unique(array_column($data['list'], 'order_id'));
            $order_list = $this->Mhouses_orders->get_lists('id, order_code, order_type, customer_id, release_start_time, release_end_time', ['in' => ['id' => $order_ids]]);
            
            foreach ($list as $k => $v){
                foreach ($order_list as $key => $val){
                    if($val['id'] == $v['order_id']){
                        $data['list'][$k]['order_code'] = $val['order_code'];
                        $data['list'][$k]['customer_name'] = "";
                        $data['list'][$k]['order_type'] = $val['order_type'];
                        $data['list'][$k]['release_start_time'] = $val['release_start_time'];
                        $data['list'][$k]['release_end_time'] = $val['release_end_time'];
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
            
            $where = [];
            $where = ['is_del' => 1];
            $where['in'] = ['group_id' => [4,6]];
            $tmp_user = $this->Madmins->get_lists('id,fullname', $where);
            foreach ($data['list'] as $k => &$v){
                $data['list'][$k]['fullname'] = "";
                foreach ($tmp_user as $key => $val){
                    if($v['charge_user'] == $val['id']) $data['list'][$k]['fullname'] = $val['fullname'];
                }
            }
            $this->return_json(['code' => 1, 'data' => $data['list'], 'page' => $page, 'msg' => 'ok']);
        }
        $this->return_json(['code' => 0, 'data' => [], 'page' => $page, 'msg' => 'no data']);
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
	            if(!$res) $this->write_log($token['user_id'], 2, "更新派单状态为已确认失败：".$order_id);
	            
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
	                if(!$res) $this->write_log($token['user_id'], 2, "更新派单状态为已确认失败：".$fatherOrder['pid']);
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
        if(!$size){
            $size = $pageconfig['per_page'];
        }
        //获取这个工单的点位列表
        $workOrderPoint = $this->Mhouses_work_order_detail->get_lists('id,pid,point_id,status,no_img,pano_img,pano_status', ['pid' => $id], [], $size, ($page-1)*$size);
        if(!$workOrderPoint) $this->return_json(['code' => 0, 'data' => [], 'page' => $page]);
        $where_p['in']['A.id'] = array_column($workOrderPoint, 'point_id');
        //投放点位
        $selected_points = $this->Mhouses_points->get_points_lists($where_p);
        $code = 0;
        if(count($selected_points)) $code = 1;
    	foreach ($workOrderPoint as $k => &$v){
    	    $v['can_report'] = 1;//默认可以报损
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
    	//查询这些点位的报损情况
    	$ids = array_column($workOrderPoint, 'point_id');
    	$badList = $this->Mhouses_points_report->get_lists('point_id', ['in' => ['point_id' => $ids], 'repair_time' => 0]);
    	if($badList){
    	    //提取id
    	    $bad_point_ids = array_column($badList, 'point_id');
    	    foreach ($workOrderPoint as $k => $v){
    	        if(in_array($v['point_id'], $bad_point_ids)){
    	            $workOrderPoint[$k]['can_report'] = 0;
    	        }
    	    }
    	}
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
        $token = decrypt($this->token);
    	$id = (int) $this->input->get_post('id');
    	$img_url = $this->input->get_post('img_url');
    	$status = $this->input->get_post('status');//临时接收
    	$this->write_log($token['user_id'], 1, "上画或下画提交的数据：".json_encode($_REQUEST));
    	$info = $this->Mhouses_work_order_detail->get_one('point_id,pid', ['id' => $id, 'status' => 0]);
    	if($info){
    	    $up = [
    	        'status' => $status,
    	        'no_img' => $img_url
    	    ];
    	    $res = $this->Mhouses_work_order_detail->update_info($up, ['id' => $id]);
    	    if(!$res){
    	        $this->write_log($token['user_id'], 2, '更新工单详情失败：'.$this->db->last_query());
    	        $this->return_json(['code' => 0, 'msg' => '操作失败']);
    	    }
    	    
    	    $this->add_redis(['user_id' => $token['user_id'], 'detail_id' => $id, 'img' => $img_url]);
    	    
    	    $this->write_log($token['user_id'], 2, '结果:'.$res.'执行的sql:'.$this->db->last_query());
    	    
    	    $this->Mhouses_work_order->update_info(['incr' => ['finish' => 1]], ['id' => $info['pid']]);
    	    
    	    $pinfo = $this->Mhouses_work_order->get_one('order_id,type', ['id' => $info['pid']]);
    	    
    	    $this->checkDoAllHasFinish($pinfo['order_id'], $pinfo['type']);
    	    
    	    //每次上画都是一次巡视
    	    $create_time = date('Y-m-d H:i:s');
    	    $add = [
    	        'point_id' => $info['point_id'],
    	        'principal_id' => $token['user_id'],
    	        'img' => $img_url,
    	        'status' => 1,
    	        'create_time' => date('Y-m-d H:i:s')
    	    ];
    	    $res = $this->Mhouses_tour_points->create($add);
    	    if(!$res){
    	        $this->write_log($token['user_id'], 1, json_encode($add));
    	        $this->return_json(['code' => 0, 'msg' => '创建巡视记录失败，已记录到系统日志']);
    	    }
    	    //更新点位数据
    	    $up = ['tour_time' => $create_time, 'tour_id' => $res];
    	    $ret = $this->Mhouses_points->update_info($up, ['id' => $info['point_id']]);
    	    if(!$ret){
    	        $this->write_log($token['user_id'], 2, json_encode($up));
    	        $this->return_json(['code' => 0, 'msg' => '巡视日志已更新，点位数据未能更新']);
    	    }
    	    
    	    $this->return_json(['code' => 1, 'msg' => '操作成功']);
    	}
    	$this->return_json(['code' => 0, 'msg' => '已审核或点位不存在']);
    }
    
    
    private function add_redis($data = []){
        $key = "AppUpload_".date("Y_m_d");
        $this->redis->lpush($key, json_encode($data));
    }
    
    /**
     * 更新父订单与子订单的订单状态
     * @param number $order_id
     * @param number $type
     */
    private function checkDoAllHasFinish($order_id= 0, $type = 1){
        //根据当前的orderid找到父orderid
        if($type == 3) {
            $tmp_moudle = $this->Mhouses_changepicorders;
        }else {
            $tmp_moudle = $this->Mhouses_orders;
        }
        //获取此工单对应订单的子订单
        $THisOrder = $tmp_moudle->get_one('pid', ['id' => $order_id]);
        if($THisOrder){
            //获取所有同胞订单id
            $SlibingList = $tmp_moudle->get_lists('id', ['pid' => $THisOrder['pid']]);
            if($SlibingList){
                //提取ids
                $ids = array_column($SlibingList, 'id');
                $tmp = $this->Mhouses_work_order->get_lists('id', ['in' =>['order_id'=> $ids], 'type' => $type]);
                $_ids = array_column($tmp, 'id');
                $count = $this->Mhouses_work_order_detail->count(['in' => ['pid' => $_ids], 'status' => 0]);
                //获取主订单状态
                if(!$count){
                    //获取主订单状态
                    $father = $this->Mhouses_orders->get_one('id,order_status', ['id' => $THisOrder['pid']]);
                    //表示全部都已经完成上传，需要更订单状态
                    $order_status = $father['order_status'];
                    //如果是下画则派单更新订单为已下画,改为7
                    $up['order_status'] = 7;
                    $up['draw_finish_time'] = date('Y-m-d');
                    //如果是上画则更新订单为投放中
                    if($order_status == 4 ) {
                        $up['order_status'] = 6;
                    }
                    //更新父订单
                    $tmp_moudle->update_info($up, ['id' => $father['id']]);
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
        $token = decrypt($this->token);
        $id = (int) $this->input->get_post('id');
        $count = $this->Mhouses_work_order_detail->count(['id' => $id, 'pano_status' => 1]);
        if($count)$this->return_json(['code' => 0, 'msg' => '请勿重复提交']);
        $img_url = $this->input->get_post('img_url2');
        $is_news_hand_img = (int) $this->input->get_post('is_news_hand_img'); //是否报头照
        $info = $this->Mhouses_work_order_detail->get_one('pid', ['id' => $id]);
        if($info){
            $up = [
                'pano_status' => 1,
                'pano_img' => $img_url,
                'is_news_hand_img' => $is_news_hand_img
            ];
            $res = $this->Mhouses_work_order_detail->update_info($up, ['id' => $id]);
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败']);
            }
            
            $this->add_redisp(['user_id' => $token['user_id'], 'detail_id' => $id, 'pano_img' => $img_url]);
            
            $this->Mhouses_work_order->update_info(['incr' => ['pano_num' => 1]], ['id' => $info['pid']]);
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
        $this->return_json(['code' => 0, 'msg' => '点位不存在']);
    }
    
    private function add_redisp($data = []){
        $key = "AppUploadp_".date("Y_m_d");
        $this->redis->lpush($key, json_encode($data));
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
    
    /**
     * 查询点位id附近同等级的空闲点位列表
     */
    public function getSiblingPoint(){
        
        $pointId = $this->input->get_post('pointId');
        $assignId = $this->input->get_post('assignId'); 
        $type = $this->input->get_post('type');
        //查询点位id附近同等级的空闲点位列表
        $list = [];
        //获取点位信息
        $info = $this->Mhouses_points->get_one('*', ['id' => $pointId]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '参数错误1']);
        $workeInfo = $this->Mhouses_work_order->get_one('order_id', ['id' => $assignId]);
        if(!$workeInfo) $this->return_json(['code' => 0, 'msg' => '参数错误2']);
        if($workeInfo){
            $fields = 'id,code,houses_id,area_id,ban,unit,floor,addr,type_id,ad_num, ad_use_num, lock_num,point_status';
            $findby = ['unit','ban','area_id'];
            foreach ($findby as $k => $v){
                $res = $this->loop($info, $fields, $v, $workeInfo['order_id'], $type);
                if($res) {
                    $list = $res;
                    break;
                }
            }
        }
        if(!count($list)){
            //此楼盘下暂无可用点位，现在进行区域查点位
            $housesInfo = $this->Mhouses->get_one('area', ['id' => $info['houses_id']]);
            if(!$housesInfo) $this->return_json(['code' => 1, 'msg' => '暂无数据']);
            $housesList = $this->Mhouses->get_lists('id', ['area' => $housesInfo['area']]);
            if(!$housesList) $this->return_json(['code' => 1, 'msg' => '暂无数据']);
            $houses_ids = array_column($housesList, 'id');

            $pointList = $this->Mhouses_points->app_get_usable_point(
                $fields,
                [
                    'in' => ['houses_id' => $houses_ids],
                    'point_status' => 1
                ],
                $workeInfo['order_id'],
                $type
            );
            if(!$pointList){
                $this->return_json(['code' => 0, 'msg' => '本区域暂无空闲点位']);
            }
            $list = $pointList;
        }
        $points_lists = $list;
        //拼接楼盘、组团名字、点位规格
        if(count($points_lists) > 0) {
            $housesid = array_unique(array_column($points_lists, 'houses_id'));
            $area_id = array_unique(array_column($points_lists, 'area_id'));
            
            if(!empty($this->input->post('put_trade'))) {
                $housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid], 'put_trade<>' => $this->input->post('put_trade')]);
            }else {
                $housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid]]);
            }
            
            $wherea['in']['id'] = $area_id;
            $areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
            //获取规格列表
            $size_list = $this->Mhouses_points_format->get_lists('id,type,size', ['is_del' => 0]);
            foreach ($points_lists as $k => &$v) {
                //设置状态
                $v['point_status_txt'] = C('public.points_status')[$v['point_status']];
                
                $mark = false;
                foreach($housesList as $k1 => $v1) {
                    if($v['houses_id'] == $v1['id']) {
                        $v['houses_name'] = $v1['name'];
                        $mark = true;
                        break;
                    }
                }
                
                if($mark == false) {
                    unset($points_lists[$k]);
                    continue;
                }
                
                foreach($areaList as $k2 => $v2) {
                    if($v['area_id'] == $v2['id']) {
                        $v['area_name'] = $v2['name'];
                        break;
                    }
                }
                
                $v['size'] = '';
                if($size_list){
                    foreach ($size_list as $key => $val){
                        if($val['type'] == $v['type_id']){
                            $v['size'] = $val['size'];break;
                        }
                    }
                }
            }
            unset($list);
            $this->return_json(['code' => 1, 'data' => $points_lists]);
        }
        $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        
    }
    
    /**
     * 逐级查询附近空闲点位
     * @param string $fields
     * @param string $findby
     * @param number $order_id
     * @param number $type
     * @return boolean|unknown
     */
    private function loop($info, $fields='', $findby='', $order_id=0, $type=0){
        $pointList = $this->Mhouses_points->app_get_usable_point(
            $fields, 
            [
                'houses_id' => $info['houses_id'], 
                'point_status' => 1, 
                $findby => $info[$findby]
            ], 
            $order_id, 
            $type
        );
        if(!$pointList){
            return false;
        }
        return $pointList;
    }
    
    /**
     * 提交异常，更新点位状态为4（异常状态），能上画，则不跟新为4，而是提交报告
     */
    public function report(){
        $token = decrypt($this->token);
        $id = $this->input->get_post('id');//工单详情id report
        $report_img = $this->input->get_post('report_img');
        $this->write_log($token['user_id'], 1, '报损图片url'.$report_img);
        if(!$report_img) $this->return_json(['code' => 0, 'msg' => '请拍照上传图片']);
        $report = $this->input->get_post('report');
        if(!$report) $this->return_json(['code' => 0, 'msg' => '请选择异常选项']);
        $report_msg = $this->input->get_post('report_msg');
        $report_msg = $report_msg ? $report_msg : "";
        if($report == 14){
            if(empty($report_msg)){
                $this->return_json(['code' => 0, 'msg' => '您勾选了其他，请填写对应的说明']);
            }
        }
        $usable = (int) $this->input->get_post('usable');
        
        $info = $this->Mhouses_work_order_detail->get_one('*', ['id' => $id]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '数据不存在']);
        //检查是否已经存在未修复的记录，如果有则不允许提交
        $count = $this->Mhouses_points_report->count(['point_id' => $info['point_id'], 'repair_time' => 0]);
        if($count){
            $this->return_json(['code' => 0, 'msg' => '该点位已报损，请勿重复提交']);
        }
        
        $up = [
            'report_img' => $report_img,
            'point_id' => $info['point_id'],
            'report' => $report,
            'create_id' => $token['user_id'],
            'report_msg' => $report_msg,
            'create_time' => strtotime(date('Y-m-d')),
            'usable' => $usable
        ];
        $res = $this->Mhouses_points_report->create($up);
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '操作失败，请重试']);
        }
        //如果不能上画，则更新为4
        if($usable == 0){
            //更新点位为异常状态
            $res = $this->Mhouses_points->update_info(['point_status' => 4], ['id' => $info['point_id']]);
            if(!$res){
                $this->write_log($token['user_id'], 1, '点位成功报异常，但未能更新点位状态,工单详情id'.$id);
            }
        }
        
        $this->return_json(['code' => 1, 'msg' => '提交成功']);
    }
    
    /**
     * 根据已选择附近的点位，替换当前工单的点位
     * 并更新订单、子订单对应的点位
     */
    public function exchange_point(){
        $token = decrypt($this->token);
        $detailId = $this->input->get_post('detailId'); //工单详情记录的id
        $new_point = $this->input->get_post('new_point');
        if(!$new_point) $this->return_json(['code' => 0, 'msg' => '请选择一个附近的可用点位']);
        $info = $this->Mhouses_work_order_detail->get_one('*', ['id' => $detailId]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '数据不存在']);
        $old_point = $info['point_id'];
        //根据pid查询工单所在的工单
        $infos = $this->Mhouses_work_order->get_one('*', ['id' => $info['pid']]);
        if(!$infos) $this->return_json(['code' => 0, 'msg' => '工单不存在']);
        //查询订单
        $sonOrder = $this->Mhouses_orders->get_one('id,pid,point_ids', ['id' => $infos['order_id']]);
        if(!$sonOrder) $this->return_json(['code' => 0, 'msg' => '数据不存在']);
        //更新订单的点位
        $point_ids = explode(',', $sonOrder['point_id']);
        foreach ($point_ids as $k => $v){
            if($v == $old_point){
                $point_ids[$k] = $new_point;break;
            }
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);//临时开启
        $this->Mhouses_orders->update_info(['point_ids' => implode(',', $point_ids)], ['id' => $sonOrder['id']]);
        $fatherOrder = $this->Mhouses_orders->get_one('id,point_ids', ['id' => $sonOrder['pid']]);
        if($fatherOrder){
            $point_ids = explode(',', $fatherOrder['point_id']);
            foreach ($point_ids as $k => $v){
                if($v == $old_point){
                    $point_ids[$k] = $new_point;break;
                }
            }
            $this->Mhouses_orders->update_info(['point_ids' => implode(',', $point_ids)], ['id' => $fatherOrder['id']]);
        }
        //更新此订单详情记录的新点位
        $res = $info = $this->Mhouses_work_order_detail->update_info(['point_id' => $new_point], ['id' => $detailId]);
        if(!$res){
            $this->write_log($token['user_id'], 1, '订单的新点位已更新,但工单详情id'.$detailId.'未能更新成新点位'.$new_point);
        }
        //更新新点位占用各种状态
        $this->Mhouses_points->update_info(['incr' => ['ad_use_num' => 1] ], ['id' => $new_point]);
        $_where['id'] = $new_point;
        $_where['field']['`ad_num`<='] = '`ad_use_num` + `lock_num`';
        $this->Mhouses_points->update_info(['point_status' => 3], $_where);
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
}
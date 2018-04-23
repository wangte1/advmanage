<?php 
/**
* 订单管理控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesconfirm extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_orders' => 'Mhouses_orders',
        	'Model_houses_change_pic_orders' => 'Mhouses_changepicorders',
        	'Model_admins' => 'Madmins',
        	'Model_houses_customers' => 'Mhouses_customers',
        	'Model_houses_points' => 'Mhouses_points',
        	
        	'Model_salesman' => 'Msalesman',
        	'Model_make_company' => 'Mmake_company',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        	'Model_houses_change_points_record' => 'Mhouses_change_points_record',
        	'Model_houses_status_operate_time' => 'Mhouses_status_operate_time',
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_confirm_list';

        $this->data['order_type_text'] = C('housesorder.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('housesorder.houses_assign_status'); //派单状态
        $this->data['houses_assign_type'] = C('housesorder.houses_assign_type'); //派单类型
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
    }
    

    /**
     * 订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
		
        $where = [];
        if($data['userInfo']['group_id'] != 1 && $data['userInfo']['group_id'] != 5) {
        	$where['A.charge_user'] = $data['userInfo']['id'];
        }
        
        if ($this->input->get('province')) $where['like']['B.province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['like']['B.city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['like']['B.area'] = $this->input->get('area');
        if ($this->input->get('houses_name')) $where['like']['B.name'] = $this->input->get('houses_name');
        if ($this->input->get('customer_name')) $where['like']['D.name'] = $this->input->get('customer_name');
        if ($this->input->get('charge_name')) $where['like']['E.fullname'] = $this->input->get('charge_name');
        if ($this->input->get('status')) $where['A.status'] = $this->input->get('status');
        
        $assign_type = $this->input->get('assign_type') ? : 1;
        
        //未确认派单的数量
        $data['no_confirm_count1'] = $this->Mhouses_assign->join_count(array_merge(['A.status'=> 2,'A.type'=> 1], $where));
        $data['no_confirm_count2'] = $this->Mhouses_assign->join_count(array_merge(['A.status'=> 2,'A.type'=> 2],$where));
        $data['no_confirm_count3'] = $this->Mhouses_assign->join_count(array_merge(['A.status'=> 2,'A.type'=> 3],$where));
        
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['houses_name'] = $this->input->get('houses_name');
        $data['customer_name'] = $this->input->get('customer_name');
        $data['charge_name'] = $this->input->get('charge_name');
        $data['status'] = $this->input->get('status');
        $data['assign_type'] = $this->input->get('assign_type') ? : 1;
        

        $where['A.type'] = $assign_type;
        $where['A.is_del'] = 0;
        
        $data['list'] = $this->Mhouses_assign->get_join_lists($where,['A.id'=>'desc'],$size,($page-1)*$size);
        
        $data_count = $this->Mhouses_assign->join_count($where);
        $data_count = $data_count[0]['count'];
        $data['data_count'] = $data_count;
        $data['page'] = $page;
        
        $where = [];
        $tmp_user = $this->Madmins->get_lists('id,fullname', $where);
        $data['user_list'] = array_column($tmp_user, 'fullname', 'id');
        
        //获取分页
        $pageconfig['base_url'] = "/housesconfirm";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $this->load->view("housesconfirm/index", $data);
    }
    
    /*
     * 详情
     */
    public function order_detail($id, $assign_type) {
    	$data = $this->data;
    	 
    	if($assign_type == 3) {
    		$data['info'] = $this->Mhouses_changepicorders->get_one('*',array('id' => $id));
    		$tmp_info = $this->Mhouses_orders->get_one('*',array('order_code' => $data['info']['order_code']));
    		$data['info']['sales_id'] = $tmp_info['sales_id'];
    		$data['info']['total_price'] = $tmp_info['total_price'];
    		$data['info']['release_start_time'] = $tmp_info['release_start_time'];
    		$data['info']['release_end_time'] = $tmp_info['release_end_time'];
    	}else {
    		$data['info'] = $this->Mhouses_orders->get_one('*',array('id' => $id));
    	}
    	 
    	if($this->input->get('houses_id')) {
    		$where_p['A.houses_id'] = $data['houses_id'] = $this->input->get('houses_id');
    	}
    	
    	if($this->input->get('ban')) {
    		$where_p['A.ban'] = $data['ban'] = $this->input->get('ban');
    	}
    
    	//客户名称
    	$data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];
    
    	//业务员
    	$data['info']['salesman'] = $this->Msalesman->get_one('name, phone_number', array('id' => $data['info']['sales_id']));
    	
    	$where_p['in']['A.id'] = explode(',', $data['info']['point_ids']);
    	//投放点位
    	$data['info']['selected_points'] = $this->Mhouses_points->get_points_lists($where_p);
    
    	//广告画面
    	$data['info']['adv_img'] = $data['info']['adv_img'] ? explode(',', $data['info']['adv_img']) : array();
    
    	//制作公司
    	$data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];
    	$data['status_text'] = C('housesorder.houses_order_status.text');
    
    	$data['id'] = $id;
    	$data['assign_type'] = $assign_type;
    	$this->load->view('housesconfirm/order_detail', $data);
    }
    
    
    /*
     * 确认派单
     */
    public function do_confirm() {
    	
    	$where['is_del'] = 0;
    	if ($this->input->post('id')) {

    		$assign_type = $this->input->post('assign_type');
    		$where['id'] = $this->input->post('id');
    		$update_data['status'] = 3;	//已确认派单
    		$res1 = $this->Mhouses_assign->update_info($update_data, $where);

    		
    		if($res1) {
    			if($this->input->post('order_id')) {
    				$where['status'] = 2;

    				$data_count = $this->Mhouses_assign->count($where);
    				if($data_count == 0 && ($assign_type == 1 || $assign_type == 3)) {

    					
    					if($this->input->post('assign_type') == 3) {	//换画派单
    						$where  = $update_data = [];
    						$where['id'] = $this->input->post('order_id');
    						$update_data['assign_status'] = 3;	//订单中的派单状态更新为已派单（已确认）
    						$update_data['order_status'] = 5;	//订单状态更新为派单完成
    						$res2 = $this->Mhouses_changepicorders->update_info($update_data, $where);
    						
    					}else {
    						$where  = $update_data = [];
    						$where['id'] = $this->input->post('order_id');
    						$update_data['assign_status'] = 3;	//订单中的派单状态更新为已派单（已确认）
    						
    						if($this->input->post('assign_type') == 1) {
    							$update_data['order_status'] = 5;	//订单状态更新为派单完成
    						}
    						$res2 = $this->Mhouses_orders->update_info($update_data, $where);
    					}
    					
    				}
    			}
    			
    			$this->return_json(['code' => 1, 'msg' => "确认派单成功！"]);
    			$this->write_log($data['userInfo']['id'],1,"确认派单楼盘：".$this->input->post('id'));
    		}
    		
    		$this->return_json(['code' => 0, 'msg' => "确认派单失败，请重试或联系管理员！"]);
    		
    	}
    	
    }
    
    
    /*
     * 验收图片
     * 1034487709@qq.com
     */
    public function check_upload_img(){
    	
    	$data = $this->data;
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page =  intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	
    	$data['assign_id'] = $assign_id = $this->input->get('assign_id');
    	$data['order_id'] = $order_id = $this->input->get('order_id');
    	$data['houses_id'] = $houses_id = $this->input->get('houses_id');
    	$data['area_id'] = $area_id = $this->input->get('area_id');
    	$data['ban'] = $ban = $this->input->get('ban');
    	$data['assign_type'] = $assign_type = $this->input->get('assign_type');
    	$data['num'] = $num = $this->input->get('num');
    	
    	if(IS_POST){
    		$post_data = $this->input->post();
    		foreach ($post_data as $key => $value) {
    			$where = array('order_id' => $order_id, 'assign_id' => $assign_id, 'point_id' => $key, 'type' => 1, 'assign_type' => $assign_type);
    			$img = $this->Mhouses_order_inspect_images->get_one('*', $where);
    
    			//如果是修改验收图片，则先删除该订单下所有验收图片，再重新添加
    			if ($img) {
    				$this->Mhouses_order_inspect_images->delete($where);
    			}
    
    			if (isset($value['front_img']) && count($value['front_img']) > 0) {
    				foreach ($value['front_img'] as $k => $v) {
    					$insert_data['order_id'] = $order_id;
    					$insert_data['assign_id'] = $assign_id;
    					$insert_data['assign_type'] = $assign_type;
    					$insert_data['point_id'] = $key;
    					$insert_data['front_img'] = $v;
    					$insert_data['back_img'] = isset($value['back_img'][$k]) ? $value['back_img'][$k] : '';
    					$insert_data['type'] = 1;
    					$insert_data['create_user'] = $insert_data['update_user'] = $data['userInfo']['id'];
    					$insert_data['create_time'] = $insert_data['update_time'] = date('Y-m-d H:i:s');
    					$this->Mhouses_order_inspect_images->create($insert_data);
    				}
    			}
    		}
    
    		
    
    		$this->write_log($data['userInfo']['id'], 2, "社区上传订单验收图片，订单id【".$order_id."】");
    		
    		$this->success("保存验收图片成功！");
    		exit;
    	}
    	
    	if($assign_type == 3) {
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    		$tmp_moudle = $this->Mhouses_orders;
    	}
    	$order = $tmp_moudle->get_one("*",array("id" => $order_id));
    	$where_point['in']['A.id'] = $point_ids_arr = explode(',', $order['point_ids']);
    	$where_point['A.houses_id'] = $houses_id;
    	if($ban) {
    		$where_point['A.ban'] = $ban;
    	}
    	if($area_id){
    	    $where_point['A.area_id'] = $area_id;
    	}
    	
    	//获取该订单下面的所有楼盘
    	$points = $this->Mhouses_points->get_points_lists($where_point,[],$size,($page-1)*$size);
    	$points_count = $this->Mhouses_points->get_points_lists($where_point);
    	$data['page'] = $page;
    	$data['data_count'] = count($points_count);
    	
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['in'] = array("point_id"=>array_column($points,"id"));
    		$where['order_id'] = $order_id;
    		$where['assign_id'] = $assign_id;
    		$where['assign_type'] = $assign_type;
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
    
    	$data['list'] = $list;
    	
    	//获取分页
    	$pageconfig['base_url'] = "/housesconfirm/check_upload_img";
    	$pageconfig['total_rows'] = $data['data_count'];
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    
    	$this->load->view('housesconfirm/check_adv_img', $data);
    }
    
    /**
     * 导出工单
     */
    public function  task_exports(){
        $data = $this->data;
        $assign_id = $this->input->get('assign_id');
        $order_id = $this->input->get('order_id');
        $houses_id = $this->input->get('houses_id');
        $area_id = $this->input->get('area_id');
        $ban = $this->input->get('ban');
        $assign_type = $this->input->get('assign_type');
        if($assign_type == 3) {
            $tmp_moudle = $this->Mhouses_changepicorders;
        }else {
            $tmp_moudle = $this->Mhouses_orders;
        }
        $order = $tmp_moudle->get_one("*",array("id" => $order_id));
        $where_point['in']['A.id'] = $point_ids_arr = explode(',', $order['point_ids']);
        $where_point['A.houses_id'] = $houses_id;
        if($ban) {
            $where_point['A.ban'] = $ban;
        }
        if($area_id){
            $where_point['A.area_id'] = $area_id;
        }
        //获取该订单下面的所有楼盘
        $list = $this->Mhouses_points->get_points_lists($where_point);
        $customerName = '';
        //获取订单客户名称
        $orderInfo = $this->Mhouses_orders->get_one('customer_id', ['id' => $order_id]);
        if($orderInfo){
            $customer = $this->Mhouses_customers->get_one('name', ['id' => $orderInfo['customer_id']]);
            $customerName = $customer['name'];
        }
        if($list){
            //加载phpexcel
            $this->load->library("PHPExcel");
            //设置表头
            $table_header =  array(
                '点位编号'=>"code",
                '楼盘'=>"houses_name",
                '组团'=>"houses_area_name",
                '楼栋'=>"ban",
                '单元'=>"unit",
                '楼层'=>"floor",
                '点位位置'=>"addr"
            );
            
            $i = 0;
            foreach($table_header as  $k=>$v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
                $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
                $i++;
            }

            
            $h = 2;
            foreach($list as $key=>$val){
                $j = 0;
                foreach($table_header as $k => $v){
                    $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
                    $value = '';
                    if($v == 'addr') {
                        if(isset($data['point_addr'][$val[$v]]))
                            $value = $data['point_addr'][$val[$v]];
                    }else {
                        $value = $val[$v];
                    }
                    $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
                }
                $h++;
            }
            
            $this->phpexcel->setActiveSheetIndex(0);
            // 输出
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=客户：'.$customerName.'的点位列表.xls');
            header('Cache-Control: max-age=0');
            
            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
            $objWriter->save('php://output');
        }

    }
    
    
    /*
     * 查看验收图片详情
     * 1034487709@qq.com
     */
    public function upload_detail(){
    	$data = $this->data;
    	 
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page = intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	$data['assign_id'] = $assign_id = $this->input->get('assign_id');
    	$data['order_id'] = $order_id = $this->input->get('order_id');
    	$data['houses_id'] = $houses_id = $this->input->get('houses_id');
    	$data['area_id'] = $area_id = $this->input->get('area_id');
     	$data['ban'] = $ban = $this->input->get('ban');
    	$data['assign_type']= $assign_type = $this->input->get('assign_type');
    	 
    	if($assign_type == 3) {
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    		$tmp_moudle = $this->Mhouses_orders;
    	}
    	$order = $tmp_moudle->get_one("*",array("id" => $order_id));
    	if($area_id) $where_point['A.area_id'] = $area_id;
    	if(isset($order['point_ids'])) {
    		$point_ids_arr = explode(',', $order['point_ids']);
    		$where_point['in']['A.id'] = $point_ids_arr;
    	}
    	$where_point['A.houses_id'] = $houses_id;
    	$where_point['A.ban'] = $ban;
    	$where_point['A.is_del'] = 0;
    	 
    	//获取该订单下面的所有楼盘
    	$points = $this->Mhouses_points->get_points_lists($where_point,[],$size,($page-1)*$size);
    	$points_count = $this->Mhouses_points->get_points_lists($where_point);
    	$data['page'] = $page;
    	$data['data_count'] = count($points_count);
    	 
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['in'] = array("point_id"=>array_column($points,"id"));
    		$where['assign_id'] = $assign_id;
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
    
    	$data['list'] = $list;
    	 
    	//获取分页
    	$pageconfig['base_url'] = "/housesorders/check_upload_img";
    	$pageconfig['total_rows'] = count($points_count);
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    
    	$this->load->view('housesconfirm/upload_detail', $data);
    }
    
    
    /**
     * 提交上画
     */
    public function submit_upload() {
    	$assign_id = $this->input->post('assign_id');
    	$assign_type = $this->input->post('assign_type');
    	//如果全部上传完，则将派单表的状态改成已上画
    	$where_count['assign_id'] = $assign_id;
    	$where_count['assign_type'] = $assign_type;
    	$where_count['front_img<>'] = '';
    	$tmp_count = $this->Mhouses_order_inspect_images->get_one('count(DISTINCT assign_id, point_id) as count', $where_count);
    	if(isset($tmp_count['count'])) {
    		$upload_count = $tmp_count['count'];
    	}
    	
    	if($assign_type == 2) {
    		$mark_str = "下画";
    		$tmp_status = 7;

    	}else if($assign_type == 3) {
    		$mark_str = "换画";
    		$tmp_status = 4;
    	}else{
    		$mark_str = "上画";
    		$tmp_status = 4;
    	}
    	
    	$assign_count = $this->Mhouses_assign->get_one('points_count', ['id' => $assign_id, 'is_del' => 0]);

    	
    	if(isset($upload_count) && isset($assign_count['points_count']) && $upload_count != $assign_count['points_count']) {
    		$this->return_json(['code' => 0, 'msg' => "提交失败，您还有点位没有上传".$mark_str."图片！"]);
    	}
    	
    	$update_data['status'] = $tmp_status;
    	$update_data['confirm_remark'] = '';

    	$res = $this->Mhouses_assign->update_info($update_data, ['id' => $assign_id]);

    	if($res) {
    		$this->return_json(['code' => 1, 'msg' => "已经提交".$mark_str."至媒介管理人员处审核！"]);
    	}
    	
    }
    
}


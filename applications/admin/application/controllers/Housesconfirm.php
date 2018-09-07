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
            'Model_houses_work_order' => 'Mhouses_work_order',
            'Model_houses_work_order_detail' => 'Mhouses_work_order_detail',
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
        $charge_user = (int) $this->input->get('charge_user');
        if(!$charge_user){
            if($data['userInfo']['group_id'] != 1 && $data['userInfo']['group_id'] != 5) {
                $where['charge_user'] = $data['userInfo']['id'];
            }
        }else{
            $data['charge_user'] = $where['charge_user'] = $charge_user;
        }
        
        $data['assign_type'] = $assign_type = $this->input->get('assign_type') ? : 1;
        
        $data['customer_list'] = $customer_list = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);
        
        $customer_name = trim($this->input->get('customer_name'));
        
        if(!empty($customer_name)){
            $data['customer_name'] = $customer_name;
            foreach ($data['customer_list'] as $k => $v){
                if($customer_name == $v['name']){
                    $where['customer_id'] = $v['id'];
                }
            }
            
        }
        
        $status = trim($this->input->get('status'));
        
        if($status !=""){
            $data['status'] = $where['status'] = $status;
        }else{
            $data['status'] = -1;
        }
        
        $where['type'] = $assign_type;
        $where['is_del'] = 0;
        
        $list = $this->Mhouses_work_order->get_lists("*", $where, ['create_time'=>'desc'], $size, ($page-1)*$size);
        $data['list']= $list;
        if($data['list']){
            //查询这些工单的订单信息
            $order_ids= array_unique(array_column($data['list'], 'order_id'));
            if ($assign_type != 3) {	//上画和下画派单
                $tmp_moudle = $this->Mhouses_orders;
            }else {
                //换画派单
                $tmp_moudle = $this->Mhouses_changepicorders;
            }
            $order_list = $tmp_moudle->get_lists('id, order_code, order_type, customer_id', ['in' => ['id' => $order_ids]]);
            
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

        //解决换画订单类型的问题
        if($order_list){
            $code_list  = array_unique(array_column($order_list, 'order_code'));
            $tmp_where = [
                'in' => ['id' => $code_list],
                'pid' => 0
            ];
            $oldOrderList = $this->Mhouses_orders->get_lists('order_code, order_type', $tmp_where);
            if($oldOrderList){
                foreach ($data['list'] as $k => $v){
                    foreach ($oldOrderList as $key => $val){
                        if($v['order_code'] == $val['order_code']){
                            $data['list'][$k]['order_type'] = $val['order_type'];
                        }
                    }
                }
            }
        }

        $data_count = $this->Mhouses_work_order->count($where);
        
        $data['data_count'] = $data_count;
        $data['page'] = $page;
        
        $where = [];
        $where['in'] = ['group_id' => [4,6]];
        $tmp_user = $this->Madmins->get_lists('id,fullname,is_del', $where);
        $data['user_list'] = array_column($tmp_user, 'fullname', 'id');
        $data['user_canuse_list'] = [];
        foreach ($tmp_user as $k => $v){
            if($v['is_del'] == 1) array_push($data['user_canuse_list'], $v);
        }

        //获取分页
        $pageconfig['base_url'] = "/housesconfirm";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['count1'] = (int) $this->Mhouses_work_order->count(['type' => 1, 'status' => 0]);
        $data['count2'] = (int) $this->Mhouses_work_order->count(['type' => 2, 'status' => 0]);
        $data['count3'] = (int) $this->Mhouses_work_order->count(['type' => 3, 'status' => 0]);

        $this->load->view("housesconfirm/index", $data);
    }
    
    /*
     * 详情
     */
    public function order_detail($id, $assign_type) {
    	$data = $this->data;
    	$workOrder = $this->Mhouses_work_order->get_one("order_id", ['id' => $id]);
    	if(!$workOrder) show_404();
    	$order_id = $workOrder['order_id'];
    	if($assign_type == 3) {
    	    $data['info'] = $this->Mhouses_changepicorders->get_one('*',array('id' => $order_id));
    		$tmp_info = $this->Mhouses_orders->get_one('*',array('order_code' => $data['info']['order_code']));
    		$data['info']['sales_id'] = $tmp_info['sales_id'];
    		$data['info']['total_price'] = $tmp_info['total_price'];
    		$data['info']['release_start_time'] = $tmp_info['release_start_time'];
    		$data['info']['release_end_time'] = $tmp_info['release_end_time'];
    	}else {
    	    $data['info'] = $this->Mhouses_orders->get_one('*',array('id' => $order_id));
    	}
    	
    	//获取这个工单的点位列表
    	$workOrderPoint = $this->Mhouses_work_order_detail->get_lists('point_id', ['pid' => $id]);
        
    	//客户名称
    	$data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];
    
    	//业务员
    	$data['info']['salesman'] = $this->Msalesman->get_one('name, phone_number', array('id' => $data['info']['sales_id']));
    	
    	$where_p['in']['A.id'] = array_column($workOrderPoint, 'point_id');
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
        $data = $this->data;
    	$where['is_del'] = 0;
    	$id = (int) $this->input->post('id');
    	//统计父级订单
    	
    	$assign_type = $this->input->post('assign_type');
    	$order_id = $this->input->post('order_id');
    	if($assign_type == 3) {
    	    //换画
    	    $tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    	    //1上画，2下画
    	    $tmp_moudle = $this->Mhouses_orders;
    	}
    	if ($id) {
    		//更新工单为已确认
    		$up['status'] = 1;
    		$res = $this->Mhouses_work_order->update_info($up, ['id' => $id]);
    		
    		if($res) {
    		    //统计这个子订单是否全部已经确认
    		    $count = $this->Mhouses_work_order->count(['status' => 0, 'type' => $assign_type, 'order_id' => $order_id]);
    		    if($count == 0){
    		        $res = $tmp_moudle->update_info(['assign_status' => 3], ['id' => $order_id]);
    		        if(!$res) $this->write_log($data['userInfo']['id'], 2, "更新派单状态为已确认失败：".$order_id);
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
    		            if(!$res) $this->write_log($data['userInfo']['id'], 2, "更新派单状态为已确认失败：".$fatherOrder['pid']);
    		        }
    		    }
    		    
    			$this->return_json(['code' => 1, 'msg' => "确认派单成功！"]);
    			$this->write_log($data['userInfo']['id'], 1, "确认派单：".$this->input->post('id'));
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
    	$data['type'] = $type = $this->input->get('type');
    	$data['id'] = $id = $this->input->get('id');
    	$workDetailList = $this->Mhouses_work_order_detail->get_lists("*", ['pid' => $id], [], $size, ($page-1)*$size);
        //提取点位
        $point_ids = array_column($workDetailList, 'point_id');
    	
        $data['list'] = $list = $this->Mhouses_points->get_points_lists(['in' => ['A.id' => $point_ids]]);

        foreach ($list as $k => $v){
            foreach ($workDetailList as $key => $val){
                if($v['id'] == $val['point_id']){
                    $data['list'][$k]['status'] = $val['status'];
                    $data['list'][$k]['no_img'] = $val['no_img'];
                    $data['list'][$k]['pano_img'] = $val['pano_img'];
                }
            }
        }
        $data['data_count'] = $this->Mhouses_work_order_detail->count(['pid' => $id]);
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
    
    private function get_export_list($order_id, $houses_id, $area_id, $ban, $assign_type){
        $list = [];
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
        return $list;
    }
    
    /**
     * 导出工单
     */
    public function  user_all_task_export(){
        $data = $this->data;
        $pid = $this->input->get('id');
        $type = $this->input->get('type');
        //获取所有的工单点位
        $point_list = $this->Mhouses_work_order_detail->get_lists("point_id",array("pid" => $pid));
        //获取订单类型
        if($point_list){
            //提取点位id
            $point_ids = array_column($point_list, 'point_id');
            $list = $this->Mhouses_points->get_points_lists(['in' => ['A.id' => $point_ids]]);
            $case = '';
            if(strpos($list[0]['code'],'G') !== false){
                //var_dump('可以，这是一组广告机');
                foreach ($list as $k => $v){
                    $temp[$k] = $v['customer_id'];
                    $temp[$k] = substr($temp[$k], 1);
                    //$case = array_merge($case,$temp[$k]);
                    $list[$k]['customer_ids'] = $temp[$k];
                    $case = $case . $temp[$k] . ',';
                    $list[$k]['customer_ids'] = explode(',', $list[$k]['customer_ids']);
                }
                $arr = explode(',', $case);
                $arr = array_unique($arr);
                $arr = array_values($arr);
                array_pop($arr);
                $where['in']['id'] = $arr;
                $res = $this->Mhouses_customers->get_lists('name',$where);
                $temparr = [];
                foreach ($arr as $k => $v){
                    $temparr[$v] = $res[$k];
                }
                foreach ($list as $k => $v){
                    $list[$k]['kf'] = '';
                    foreach ($temparr as $k1 => $v1){
                        foreach ($list[$k]['customer_ids'] as $k2 => $v2){
                            if($v2 == $k1){
                                //$list[$k]['kf'] = $list[$k]['kf'] . $v1;
                                $list[$k]['kf'] .= $v1['name'] . ',';
                            }
                        }
                    }
                    unset($list[$k]['customer_ids']);
                }
            }else{
                foreach ($list as $k => $v){
                    $list[$k]['kf'] = "";
                }
            }
            //获取客户信息
            $workOrder = $this->Mhouses_work_order->get_one('customer_id, charge_user', ['id' => $pid]);
            //获取工程人员信息
            $customer =  $this->Mhouses_customers->get_one("name", ['id' => $workOrder['customer_id']]);
            $user = $this->Madmins->get_one('fullname', ['id' => $workOrder['charge_user']]);
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
                    '点位位置'=>"addr",
                    '客户'=>'kf'
                );
                //设置表格标题格式 客户-点位数-上画人
                $num = count($table_header) -1;
                $typeTXT = '';
                switch ($type) {
                    case 1 :
                        $typeTXT = '上画';
                        break;
                    case 2 :
                        $typeTXT = '下画';
                        break;
                    default:
                        $typeTXT = '换画';
                }
                //合并单元格
                $this->phpexcel->getActiveSheet(0)->mergeCells('A1:'.chr(ord('A1')+$num).'1');
                $cell = PHPExcel_Cell::stringFromColumnIndex(0).'1';
                $val = $typeTXT.'派单--【客户：'.$customer["name"].'】-【负责人：'.$user["fullname"].'】-【点位数：'.count($list).'】个';
                $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $val);
                
                
                $i = 0;
                foreach($table_header as  $k=>$v){
                    $cell = PHPExcel_Cell::stringFromColumnIndex($i).'2';
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
                header('Content-Disposition: attachment;filename='.$user["fullname"].' -【'.$customer["name"].'】的点位列表-合计'.count($list).'个.xls');
                header('Cache-Control: max-age=0');
                
                $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
                $objWriter->save('php://output');
            }
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
    /**
     * @desc 更新负责人
     */
    public function update_charge_user(){
        $charge_user = $this->input->post('user_id');
        $id = $this->input->post('id');
        if(!$charge_user && !$id){
            $this->return_json(['code' => 0, 'msg' => '失败，失败原因：缺少参数']);
        }
        $where['charge_user'] = $charge_user;
        $res = $this->Mhouses_work_order->update_info($where, ['id' => $id]);
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '失败，失败原因：数据库没写成功']);
        }
        $this->return_json(['code' => 1, 'msg' => '成功']);
    }
}


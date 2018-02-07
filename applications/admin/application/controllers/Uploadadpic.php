<?php 
/**
* 业务上传广告画面
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Uploadadpic extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_orders' => 'Mhouses_orders',
        	'Model_houses_points_format' => 'Mhouses_points_format',
            'Model_houses_customers' => 'Mhouses_customers',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_salesman' => 'Msalesman',
        	'Model_make_company' => 'Mmake_company',
        	'Model_admins' => 'Madmins',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        	'Model_houses_status_operate_time' => 'Mhouses_status_operate_time',
        	'Model_houses_change_points_record' => 'Mhouses_change_points_record',
        	'Model_houses_orders_log' => 'Mhouses_orders_log',
        	'Model_houses_order_inspect_images_log' => 'Mhouses_order_inspect_images_log',
        	'Model_houses_change_pic_orders' => 'Mhouses_change_pic_orders',
        	'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders',
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses_assign_down' => 'Mhouses_assign_down',
        	'Model_houses_change_pic_orders' => 'Mhouses_changepicorders',
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'uploadadpic_list';

        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('housesorder.houses_order_type'); //订单类型
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
        $this->data['houses_assign_status'] = C('housesorder.houses_assign_status'); //派单状态
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
    }
    

    /**
     * 订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where =  array();
        
        //$where['A.is_del'] = 0;
        if ($this->input->get('order_code')) $where['A.order_code'] = $this->input->get('order_code');
        if ($this->input->get('order_type')) $where['A.order_type'] = $this->input->get('order_type');
        if ($this->input->get('customer_id')) $where['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('order_status')) $where['A.order_status'] = $this->input->get('order_status');

        //即将到期
        $data['expire_time'] = $this->input->get("expire_time");
        if($this->input->get("expire_time")) {
            $where['A.release_end_time>='] = date("Y-m-d");
            $where['A.release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
            $where['A.order_status'] =  C('housesorder.houses_order_status.code.in_put');
        }

        //已到期未下画
        $data['overdue'] = $this->input->get('overdue');
        if($this->input->get("overdue")) {
            $where['A.release_end_time<'] =  date("Y-m-d");
            $where['A.order_status'] =  C('housesorder.houses_order_status.code.in_put');
        }

        $data['order_code'] = $this->input->get('order_code');
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['order_status'] = $this->input->get('order_status');

        //$data['project'] = array_column($this->Mcustomer_project->get_lists('id, project_name', array('is_del' => 0)), 'project_name', 'id');
		
        //如果不是超级管理员业务员只能查看自己负者的订单
        if($data['userInfo']['group_id'] != 1) {
        	$where['A.sales_id'] = $data['userInfo']['id'];
        }
        
        $data['list'] = $this->Mhouses_orders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_orders->get_order_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/housesorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $data['status_text'] = C('housesorder.houses_order_status.text');
        
        $this->load->view("uploadadpic/index", $data);
    }

    /**
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {

    	$where['is_del'] = 0;
    	$where['is_lock'] = 0;
//     	if($this->input->post('point_status') == 1) {
//     		$where['point_status'] = $this->input->post('point_status');
//     	}else if($this->input->post('point_status') == 2) {
//     		$where['is_lock'] = $this->input->post('is_lock');
//     		$where['lock_customer_id'] = $this->input->post('customer_id');
//     	}
    	$where['point_status'] = 1;
    	if($this->input->post('order_type')) $where['type_id'] = $this->input->post('order_type');
    	if($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	if(!empty($this->input->post('ban'))) $where['ban'] = $this->input->post('ban');
    	if(!empty($this->input->post('unit'))) $where['unit'] = $this->input->post('unit');
    	if(!empty($this->input->post('floor'))) $where['floor'] = $this->input->post('floor');
    	if(!empty($this->input->post('addr'))) $where['addr'] = $this->input->post('addr');
    	if($this->input->post('is_lock')) {
    		$where['is_lock'] = $this->input->post('is_lock');
    		if($this->input->post('customer_id')) {
    			$where['lock_customer_id'] = $this->input->post('customer_id');
    		}
    	}
    	
    	$points_lists = $this->Mhouses_points->get_lists("id,code,houses_id,area_id,ban,unit,floor,addr,type_id,point_status", $where);
    	$areaList = [];
    	if(count($points_lists) > 0) {
    		$housesid = array_column($points_lists, 'houses_id');
    		$area_id = array_column($points_lists, 'area_id');
    		$type_id = array_column($points_lists, 'type_id');
    		
    		if(!empty($this->input->post('put_trade'))) {
    			$housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid], 'put_trade<>' => $this->input->post('put_trade')]);
    		}else {
    			$housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid]]);
    		}

    		$wherea['in']['id'] = $area_id;
    		$areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
    		
    		$wheref['in']['type'] = $type_id;
    		$formatList = $this->Mhouses_points_format->get_lists("type,size", $wheref);
    		
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
    			
    			foreach($formatList as $k3 => $v3) {
    				if($v['type_id'] == $v3['type']) {
    					$v['size'] = $v3['size'];
    					break;
    				}
    			}
    			
    			
    		}
    	}
    	
    	$this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists), 'area_lists' => $areaList));
    }


    private function get_log_make_info($data) {
        //制作数量
        $make_num = $this->Mpoints_make_num_log->get_make_info(array('in' => array('A.id' => explode(',', $data['point_ids'])), 'C.change_id' => $data['change_id']));

        //计算总套数和总张数
        $high_count = 0;
        $total_counts = 0;
        $total_num = 0;
        foreach ($make_num as $value) {
            $total_counts += $value['counts'];
            $high_count += $value['high_count'];
            $total_num += $value['make_num'];
        }

        return array('make_num' => $make_num, 'total_counts' => $total_counts, 'high_count' => $high_count, 'total_num' => $total_num);
    }


    /*
     * 订单详情页面
     */
    public function detail($id){
        $data = $this->data;
        $data['info'] = $this->Mhouses_orders->get_one('*',array('id' => $id));

        //客户名称
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];

        //业务员
        $data['info']['salesman'] = $this->Msalesman->get_one('name, phone_number', array('id' => $data['info']['sales_id']));
		
        //投放点位
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))));

        //广告画面
        $data['info']['adv_img'] = $data['info']['adv_img'] ? explode(',', $data['info']['adv_img']) : array();

        //验收图片
        $data['info']['inspect_img'] = $this->Mhouses_order_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.type' => 1));
		
        //换画记录
        $data['info']['change_pic_record'] = $this->Mhouses_changepicorders->get_order_lists(array('A.order_code' => $data['info']['order_code']));

        //换点记录
        $data['info']['change_points_record'] = $this->Mhouses_change_points_record->get_lists('*', array('order_id' => $id), array('operate_time' => 'desc'));
        foreach ($data['info']['change_points_record'] as $key => $value) {
            $remove_points = $this->Mhouses_points->get_lists('code', array('in' => array('id' => explode(',', $value['remove_points']))));
            $data['info']['change_points_record'][$key]['remove_points'] = implode(',', array_column($remove_points, 'code'));

            $add_points= $this->Mhouses_points->get_lists('code', array('in' => array('id' => explode(',', $value['add_points']))));
            $data['info']['change_points_record'][$key]['add_points'] = implode(',', array_column($add_points, 'code'));
        }
        
        //上画派单列表
        $data['info']['assign_list'] = $this->Mhouses_assign->get_join_lists(['A.order_id' => $id, 'A.is_del' => 0]);
        
        //下画派单列表
        $data['info']['assign_down_list'] = $this->Mhouses_assign_down->get_join_lists(['A.order_id' => $id, 'A.is_del' => 0]);
        
//         if(count($data['info']['assign_list']) > 0) {
//         	$houses_ids = array_column($data['info']['assign_list'], 'houses_id');
//         	$where = [];
//         	$where['order_id'] = $id;
//         	$where['in']['houses_id'] = $houses_ids;
//         	$group_by = ['houses_id'];
//         	$data['houses_count'] = $this->Mhouses_points->get_lists('houses_id,count(0) as count', $where, [],  0,0,  $group_by);  //点位分组
//         }
        
        //制作公司
        if(!empty($data['info']['make_company_id'])) {
        	$data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];
        }
        $data['status_text'] = C('housesorder.houses_order_status.text');

        //获取对应订单状态的操作信息
        $operate_time = $this->Mhouses_status_operate_time->get_lists("value,operate_remark,operate_time",array("order_id" => $id , 'type' => 1));
        if($operate_time){
            $data['time'] = array_column($operate_time, "operate_time", "value");
            $data['operate_remark'] = array_column($operate_time, "operate_remark", "value");
        }

        $data['id'] = $id;

        $this->load->view('housesorders/detail', $data);
    }


    private function get_make_info($data) {
        //制作数量
        $make_num = $this->Mhouses_points->get_make_info(array('in' => array('A.id' => explode(',', $data['point_ids'])), 'C.order_id' => $data['id'], 'C.type' => 1));

        //计算总套数和总张数
        $high_count = 0;
        $total_counts = 0;
        $total_num = 0;
        foreach ($make_num as $value) {
            $total_counts += $value['counts'];
            $high_count += $value['high_count'];
            $total_num += $value['make_num'];
        }

        return array('make_num' => $make_num, 'total_counts' => $total_counts, 'high_count' => $high_count, 'total_num' => $total_num);
    }


    /*
     * 上传广告画面
     * 1034487709@qq.com
     */
    public function  upload_adv_img($order_id, $order_status){
        $data = $this->data;
		$data['order_status'] = $order_status;
        
        if(IS_POST){
            $adv_img = $this->input->post("cover_img");
            $is_sample = $this->input->post("is_sample");
            $res = $this->Mhouses_orders->update_info(array("adv_img"=>$adv_img, "is_sample" => $is_sample), array("id"=>$order_id));
            if ($res) {
                $this->write_log($data['userInfo']['id'], 2, "社区资源上传订单广告画面，订单id【".$order_id."】");
                $this->success("保存广告画面成功！", "/uploadadpic/upload_adv_img/".$order_id."/".$order_status);
            } else {
                $this->error("操作失败！请重试！");
            }
        } else {
            //获取广告画面的图片
            $info = $this->Mhouses_orders->get_one("adv_img",array("id"=>$order_id));
            $data['adv_img'] = "";
            $data['order_id'] = $order_id;
            if($info['adv_img']){
                $data['adv_img'] = explode(',', $info['adv_img']);
            }
            $this->load->view('uploadadpic/upload_adv_img', $data);
        }

    }


    /**
     * 导出投放点位列表
     */
    public function export($id, $type) {
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
	        '规格'=>"size",
       	);

        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }

        $order = $this->Mhouses_orders->get_one('*', array('id' => $id));

        $where['in']['A.id'] = explode(',', $order['point_ids']);

        $customers = array_column($this->Mhouses_customers->get_lists("id,name", array('is_del' => 0)), 'name', 'id'); //客户列表

        $list = $this->Mhouses_points->get_points_lists($where);

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
        header('Content-Disposition: attachment;filename=投放点位表（客户：'.$customers[$order['customer_id']].'）.xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * 获取楼栋，单元， 楼层列表
     * @author yonghua 254274509@qq.com
     * @return array[]|array[]
     */
    private function get_ban_unit_floor_list(){
        $array = [];
        
        $list = $this->Mhouses_points->get_lists(
            'ban, unit, floor',
            [
                'ban !=' => '',
                'unit !=' => '',
                'floor !=' => '',
                'is_del' => 0
            ],
            [
                'ban' => 'asc',
                'unit' => 'asc',
                'floor' => 'asc',
            ]
            );
        if(!$list) return $array;
        $array['ban'] = array_unique(array_column($list, 'ban'));
        $array['unit'] = array_unique(array_column($list, 'unit'));
        $array['floor'] = array_unique(array_column($list, 'floor'));
        
        return $array;
    }

}


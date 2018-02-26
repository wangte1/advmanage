<?php 
/**
* 意向订单管理控制器
* @author yangxiong 867332352@qq.com
*/

defined('BASEPATH') or exit('No direct script access allowed');
class Houseswantorders extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_want_orders' => 'Mhouses_want_orders',
            'Model_admins' => 'Madmins',
            'Model_medias' => 'Mmedias',
            'Model_houses' => 'Mhouses',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_area' => 'Mhouses_area',
            'Model_salesman' => 'Msalesman',
            'Model_make_company' => 'Mmake_company',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_points_format' => 'Mhouses_points_format',
        	'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houseswantorders_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
        $this->data['put_trade'] = C('housespoint.put_trade'); //禁投放行业
        $this->data['houses_type_text'] = C('public.houses_type'); //订单类型
        $this->data['admins'] = $this->Madmins->get_lists('id, group_id, fullname', array('is_del' => 1));        
        
    }
    
    /**
     * 意向订单首页
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';
        $where = array();
        if ($this->input->get('province')) $where['province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['area'] = $this->input->get('area');
        if ($this->input->get('customer_id')) $where['customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('put_trade')) $where['put_trade'] = $this->input->get('put_trade');
        if ($this->input->get('status')) $where['status'] = $this->input->get('status');
        
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['customer_id'] = $data['customer_id']= $this->input->get('customer_id');
        $data['put_trade'] = $this->input->get('put_trade');
        $data['status'] = $this->input->get('status');
        
        $data['list'] = $this->Mhouses_want_orders->get_lists('*', $where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_want_orders->count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;
        
        //var_dump($data['list']);
        
        //获取分页
        $pageconfig['base_url'] = "/housesscheduledorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['status_text'] = C('housesscheduledorder.order_status.text');
        $data['confirm_text'] = C('housesscheduledorder.customer_status');
        
        //获取所有客户
        $data['customer_list'] = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);
        
        $this->load->view('houseswantorders/index', $data);
    }
    
    /**
     * 添加意向订单
     * @param number $order_type
     */
    public function add($order_type=1, $put_trade=0){
        $data = $this->data;
        if(IS_POST){
            $post_data = $this->input->post();
            unset($post_data['s_houses_type']);
            $post_data['create_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = date('Y-m-d H:i:s');
            $id = $this->Mhouses_want_orders->create($post_data);
            if ($id) {
                $this->write_log($data['userInfo']['id'], 1, "新增意向订单,订单id【".$id."】");
                $this->success("添加成功！","/houseswantorders");
            } else {
                $this->success("添加失败！");
            }
        }
       
        $data['status_text'] = C('order.order_status.text');
        
        //end
        //获取所有业务员
        $data['yewu'] = $this->Madmins->get_lists('id, fullname', array('group_id' => 2,'is_del' => 1));
        $this->load->view('houseswantorders/add', $data);
    }
    
    /**
     * 订单详情
     */
    public function detail($id) {
    	$data = $this->data;
    	$data['info'] = $this->Mhouses_want_orders->get_one('*', ['id'=>$id]);
    	
    	$this->load->view("houseswantorders/detail", $data);
    }
    
    
    /**
     * 意向订单转预定订单
     */
    public function checkout($id) {
    	$data = $this->data;
    	$data['info'] = $this->Mhouses_want_orders->get_one('*', ['id'=>$id]);
    	
    	if(IS_POST){
    		$post_data = $this->input->post();
    		unset($post_data['province'], $post_data['city'], $post_data['area'],$post_data['houses_type'],$post_data['begin_year'],$post_data['end_year'],$post_data['put_trade']);
    		unset($post_data['ban'], $post_data['unit'], $post_data['floor']);
    		if (isset($post_data['area_id'])) unset($post_data['area_id']);
    		if (isset($post_data['addr'])) unset($post_data['addr']);
    	
    		$post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
    		$post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
    		$post_data['point_ids'] = implode(',', array_unique(explode(',', $post_data['point_ids'])));
    		$post_data['confirm_point_ids'] = '';
    		$id = $this->Mhouses_scheduled_orders->create($post_data);
    		if ($id) {
    			//意向订单
    			$update_data['status'] = 2;
    			$this->Mhouses_want_orders->update_info($update_data, ['id' => $id]);
    			
    			//decr
    			$update_data = [];
    			$update_data['incr']['lock_num'] = 1;
    			$this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
    			
    			//如果锁定数量+占用数量=可投放数量，那么点位状态变为已占满
    			$_where['field']['`ad_num`'] = '`lock_num`+`ad_use_num`';
    			$this->Mhouses_points->update_info(['point_status' => 3], $_where);
    			
    			$this->write_log($data['userInfo']['id'], 1, "新增".$data['order_type_text'][$post_data['order_type']]."预定订单,订单id【".$id."】");
    			$this->success("添加成功！","/housesscheduledorders");
    		} else {
    			$this->success("添加失败！");
    		}
    	}
    	
    	
    	$this->load->view("houseswantorders/checkout", $data);
    }
    
    
    /**
     * 根据模糊条件获取楼盘信息
     */
    public function get_houses() {

        if($this->input->post('order_type')) $where['A.type_id'] = $this->input->post('order_type');
        if(!empty($this->input->post('province'))) $where['B.province'] = $this->input->post('province');
        if(!empty($this->input->post('city'))) $where['B.city'] = $this->input->post('city');
        //if(!empty($this->input->post('area'))) $where['B.area'] = $this->input->post('area');        if(!empty($this->input->post('begin_year'))) $where['B.deliver_year>='] = $this->input->post('begin_year');
        if(!empty($this->input->post('end_year'))) $where['B.deliver_year<='] = $this->input->post('end_year');
        if(!empty($this->input->post('put_trade'))) $put_trade = $this->input->post('put_trade');
        
        if(!empty($this->input->post('area'))) {
        	$tmp_area_arr = array_filter(explode(',', $this->input->post('area')));
        	if(count($tmp_area_arr) > 0) {
        		$where['in']['B.area'] = $tmp_area_arr;
        	}
        }
        
        if(!empty($this->input->post('houses_type'))) {
        	$tmp_type_arr = explode(",", $this->input->post('houses_type'));
        	$where['in']['B.type'] = $tmp_type_arr;
        }
       	
        $where['A.point_status'] = 1;
        $points_lists = $this->Mhouses_points->get_points_lists($where);
        
        $houses_lists = [];
        $tmp_arr1 = [];
        if(count($points_lists) > 0) {
        	$tmp_arr = array_column($points_lists, 'houses_name', 'houses_id');
        	foreach($points_lists as $k => $v) {
        		$mark = false;
        		if(!empty($put_trade)) {
        			if(in_array($put_trade, explode(",", $v['put_trade']))) {
        				unset($tmp_arr[$v['houses_id']]);
        				unset($points_lists[$k]);
        				$mark = true;
        			}
        		}
        		
        		if($mark == false) {
        			foreach($tmp_arr as $k1 => &$v1) {
        				if($k1 == $v['houses_id']) {
        					if(!isset($tmp_arr1[$k1])) {
        						$tmp_arr1[$k1] = 0;
        					}
        					$tmp_arr1[$k1] = $tmp_arr1[$k1] + 1;
        				}
        			}
        		}
        	}
        	
        	$i = 0;
        	foreach($tmp_arr as $k => $v) {
        		$houses_lists[$i]['houses_name'] = $v;
        		if(isset($tmp_arr1[$k])) {
        			$houses_lists[$i]['count'] = $tmp_arr1[$k];
        		}
        		
        		$i++;
        	}
        }
        $this->return_json(array('flag' => true, 'houses_lists' => $houses_lists, 'count' => array_sum($tmp_arr1)));
    }
    
    /*
     * 获取点位列表
     */
    public function get_points() {
    	
    	if($this->input->post('order_type')) $where['A.type_id'] = $this->input->post('order_type');
    	if(!empty($this->input->post('province'))) $where['B.province'] = $this->input->post('province');
    	if(!empty($this->input->post('city'))) $where['B.city'] = $this->input->post('city');
    	//if(!empty($this->input->post('area'))) $where['B.area'] = $this->input->post('area');
    	
    	
    	if(!empty($this->input->post('begin_year'))) $where['B.deliver_year>='] = $this->input->post('begin_year');
    	if(!empty($this->input->post('end_year'))) $where['B.deliver_year<='] = $this->input->post('end_year');
    	if(!empty($this->input->post('put_trade'))) $put_trade = $this->input->post('put_trade');
    	
    	if(!empty($this->input->post('houses_id'))) $where['A.houses_id'] = $this->input->post('houses_id');
    	if(!empty($this->input->post('area_id'))) $where['A.area_id'] = $this->input->post('area_id');
    	if(!empty($this->input->post('ban'))) $where['A.ban'] = $this->input->post('ban');
    	if(!empty($this->input->post('unit'))) $where['A.unit'] = $this->input->post('unit');
    	if(!empty($this->input->post('floor'))) $where['A.floor'] = $this->input->post('floor');
    	if(!empty($this->input->post('addr'))) $where['A.addr'] = $this->input->post('addr');
    	
    	if(!empty($this->input->post('area'))) {
    		$tmp_area_arr = array_filter(explode(',', $this->input->post('area')));
        	if(count($tmp_area_arr) > 0) {
        		$where['in']['B.area'] = $tmp_area_arr;
        	}
    	}
    	
    	if(!empty($this->input->post('houses_type'))) {
    		 
    		$tmp_type_arr = explode(",", $this->input->post('houses_type'));
    		 
    		$where['in']['B.type'] = $tmp_type_arr;
    	}
    	
    	
    	$where['A.point_status'] = 1;
    	
    	$points_lists = $this->Mhouses_points->get_points_lists($where);
    	
    	if(count($points_lists) > 0) {
    		$houses_lists = array_column($points_lists, 'houses_name', 'houses_id');
    		$area_lists = array_column($points_lists, 'houses_area_name', 'area_id');
    		$ban_lists = array_column($points_lists, 'ban');
    		$unit_lists = array_column($points_lists, 'unit');
    		$floor_lists = array_column($points_lists, 'floor');
    		$addr_lists = array_column($points_lists, 'addr');
    		
	    	foreach($points_lists as $k => &$v) {
	    		if(isset(C('public.houses_grade')[$v['grade']])) {
	    			$v['houses_grade'] = C('public.houses_grade')[$v['grade']];
	    		}else {
	    			$v['houses_grade'] = '';
	    		}
	    			    		if(isset(C('public.houses_grade')[$v['area_grade']])) {
	    			$v['area_grade_name'] = C('public.houses_grade')[$v['area_grade']];
	    		}else {
	    			$v['area_grade_name'] = '';
	    		}
	    		
	    		if(isset(C('housespoint.points_status')[$v['point_status']])) {
	    			$v['point_status_txt'] = C('housespoint.points_status')[$v['point_status']];
	    		}else {
	    			$v['point_status_txt'] = '';
	    		}
	    	}
    	}
    	
    	$this->return_json(array(
    			'flag' => true,
    			'points_lists' => $points_lists,
    			'houses_lists'=>$houses_lists,
    			'area_lists'=>$area_lists,
    			'ban_lists'=>$ban_lists,
    			'unit_lists'=>$unit_lists,
    			'floor_lists'=>$floor_lists,
    			'addr_lists'=>$addr_lists,
    			'count' => count($points_lists)));
    }
    
    
    /**
     * 导出预定点位列表
     */
    public function export($id, $type) {
        //加载phpexcel
        $this->load->library("PHPExcel");
        //设置表头
        $table_header =  array(
            '点位id' => 'id',
            '点位编号' => "code",
            '楼盘名称' => "houses_name",
            '组团' => 'houses_area_name',
            '位置' => "addr",
            '价格' => 'price',
            '规格' => "size"
        );
        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
        
        $scheduledorder = $this->Mhouses_scheduled_orders->get_one('*', array('id' => $id));
        
        $where['in']['A.id'] = explode(',', $scheduledorder['confirm_point_ids']);
        
        $customers = $this->Mhouses_customers->get_one("name", array('id' => $scheduledorder['lock_customer_id'], 'is_del' => 0)); //客户
        
        $list = $this->Mhouses_points->get_points_lists($where);
        foreach ($list as $k => $v){
            if($v['addr'] == 1){
                $list[$k]['addr'] = '门禁';
            }else{
                $list[$k]['addr'] = '电梯前室';
            }
        }  
        $h = 2;
        foreach($list as $key=>$val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
                $value = $val[$v];
                $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
            }
            $h++;
        }
        
        $this->phpexcel->setActiveSheetIndex(0);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=预定点位表（客户：'.$customers['name'].'）.xls');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * 撤回意向订单
     */
    public function cancle() {
    	$id = $this->input->post('id');
    	if(!empty($id)) {
    		$update_data['is_del'] = 1;
    		$this->Mhouses_want_orders->update_info($update_data, ['id' => $id]);
    		$this->return_json(['code' => 1, 'msg' => '撤回成功！' ]);
    	}
    	
    	$this->return_json(['code' => 0, 'msg' => '撤回失败！' ]);
    }
    
}
<?php 

//阿里大鱼短信
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use YYHhelper\Http;

/**
* 派单管理控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesassign extends MY_Controller{

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
        	'Model_status_operate_time' => 'Mstatus_operate_time',
        	'Model_houses_change_points_record' => 'Mhouses_change_points_record',
        	'Model_houses_orders_log' => 'Mhouses_orders_log',
        	'Model_houses_order_inspect_images_log' => 'Mhouses_order_inspect_images_log',
        	'Model_houses_change_pic_orders' => 'Mhouses_change_pic_orders',
        	'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders',
        	'Model_admins' => 'Madmins',
        	'Model_houses_assign' => 'Mhouses_assign'
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_assign_list';

        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('order.houses_assign_status'); //派单状态
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
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
        if ($this->input->get('assign_status')) $where['A.assign_status'] = $this->input->get('assign_status');

        //即将到期
        $data['expire_time'] = $this->input->get("expire_time");
        if($this->input->get("expire_time")) {
            $where['A.release_end_time>='] = date("Y-m-d");
            $where['A.release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
            $where['A.order_status'] =  C('order.order_status.code.in_put');
        }

        //已到期未下画
        $data['overdue'] = $this->input->get('overdue');
        if($this->input->get("overdue")) {
            $where['A.release_end_time<'] =  date("Y-m-d");
            $where['A.order_status'] =  C('order.order_status.code.in_put');
        }

        $data['order_code'] = $this->input->get('order_code');
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['assign_status'] = $this->input->get('assign_status');

        //$data['project'] = array_column($this->Mcustomer_project->get_lists('id, project_name', array('is_del' => 0)), 'project_name', 'id');

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
        $data['status_text'] = C('order.order_status.text');
        
        $this->load->view("housesassign/index", $data);
    }
    
    /*
     * 派单
     */
    public function assign() {
    	$data = $this->data;
    	
    	$where['is_del'] = 0;
    	if ($this->input->get('order_id')) $where['order_id'] = $data['order_id'] =  $this->input->get('order_id');
    	
    	if(IS_POST){
    		$order_id = $this->input->post('order_id');
    		$houses_ids = $this->input->post('houses_id');
    		$points_counts = $this->input->post('points_count');
    		$charge_users = $this->input->post('charge_user');
    		
    		$add_data = [];
    		$i = 0;
    		foreach ($houses_ids as $k => $v) {
    			$add_data[$i]['order_id'] = $order_id;
    			$add_data[$i]['houses_id'] = $v;
    			$add_data[$i]['points_count'] = $points_counts[$k];
    			$add_data[$i]['charge_user'] = $charge_users[$k];
    			$add_data[$i]['assign_user'] = $data['userInfo']['id'];
    			$add_data[$i]['assign_time'] = date("Y-m-d H:i:s");
    			$i++;
    		}
    		
    		$res = $this->Mhouses_assign->create_batch($add_data);
    		
    		if($res) {
    			$update_data['assign_status'] = 2;
    			$res1 = $this->Mhouses_orders->update_info($update_data,array("id" => $order_id));
    			
    			if($res1) {
    				$this->success("保存并通知成功","/housesassign/detail?order_id=".$order_id);
    			}
    		}
    		
    		$this->error("保存失败");
    		
    	}
    	
    	$group_by = ['houses_id'];
    	$list = $this->Mhouses_points->get_lists('houses_id,count(0) as count', $where, [],  0,0,  $group_by);  //点位分组
    	
    	if($list) {
    		$houses_ids = array_column($list, 'houses_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $houses_ids;
    		$hlist = $this->Mhouses->get_lists('id,name,province,city,area', $where);  //楼盘信息
    		
    		if($hlist) {
    			foreach ($list as $k => &$v) {
    				foreach ($hlist as $k1 => $v1) {
    					if($v['houses_id'] == $v1['id']) {
    						$v['ad_area'] = $v1['province']."-".$v1['city']."-".$v1['area'];
    						$v['houses_name'] = $v1['name'];
    					}
    				}
    			}
    		}
    		
    	}
    	
    	$data['list'] = $list;
    	
    	$where = [];
    	$where['group_id'] = 4;	//工程人员角色
    	$data['user_list'] = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	
    	$this->load->view('housesassign/assign', $data);
    }
    
    /*
     * 详情
     */
    public function detail() {
    	$data = $this->data;
    	 
    	$where['is_del'] = 0;
    	if ($this->input->get('order_id')) $where['order_id'] = $data['order_id'] =  $this->input->get('order_id');
    	 
    	$group_by = ['houses_id'];
    	$list = $this->Mhouses_points->get_lists('houses_id,count(0) as count', $where, [],  0,0,  $group_by);  //点位分组
    	 
    	if($list) {
    		$houses_ids = array_column($list, 'houses_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $houses_ids;
    		$hlist = $this->Mhouses->get_lists('id,name,province,city,area', $where);  //楼盘信息
    
    		if($hlist) {
    			foreach ($list as $k => &$v) {
    				foreach ($hlist as $k1 => $v1) {
    					if($v['houses_id'] == $v1['id']) {
    						$v['ad_area'] = $v1['province']."-".$v1['city']."-".$v1['area'];
    						$v['houses_name'] = $v1['name'];
    					}
    				}
    			}
    		}
    
    	}
    	 
    	$data['list'] = $list;
    	 
    	$where = [];
    	$tmp_arr = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	$data['user_list'] = array_column($tmp_arr, 'fullname', 'id');
    	
    	$data['assign_list'] = $this->Mhouses_assign->get_lists('id,houses_id,charge_user,assign_user,assign_time,status', ['order_id' => $data['order_id'], 'is_del' => 0]);  //点位分组
    	
    	$this->load->view('housesassign/detail', $data);
    }
    
    
    /*
     * 改派
     */
    public function edit() {
    	$data = $this->data;
    	
    	$where['is_del'] = 0;
    	if ($this->input->get('order_id')) $where['order_id'] = $data['order_id'] =  $this->input->get('order_id');
    	 
    	$where = [];
    	$where['group_id'] = 4;	//工程人员角色
    	$data['user_list'] = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	$data['user_list1'] = array_column($data['user_list'], 'fullname', 'id');
    	
    	if(IS_POST){
    		$order_id = $this->input->post('order_id');
    		$houses_ids = $this->input->post('houses_id');
    		$points_counts = $this->input->post('points_count');
    		$charge_users = $this->input->post('charge_user');
    
    		$up_data = [];
    		$i = 0;
    		foreach ($charge_users as $k => $v) {
    			if($v) {
    				$tmp_charge = $this->Mhouses_assign->get_one('charge_user', ['order_id' => $order_id, 'houses_id'=>$houses_ids[$k], 'is_del' => 0]);  //点位分组
    				
    				if($tmp_charge != $v) {
    					$up_data['charge_user'] = $v;
    					$up_data['assign_user'] = $data['userInfo']['id'];
    					$up_data['assign_time'] = date("Y-m-d H:i:s");
    					$result = $this->Mhouses_assign->update_info($up_data,array("order_id"=>$order_id, 'houses_id'=>$houses_ids[$k]));
    					
    					if($result) {
    						$this->write_log($data['userInfo']['id'],2,"派单更改负责人".$data['user_list1'][$tmp_charge['charge_user']]."为：".$data['user_list1'][$v].",order_id-".$order_id.",houses_id-".$houses_ids[$k]);	//后期空闲时加上记录表
    					}
    				}
    			}
    			
    			$i++;
    		}
    
    		if($result) {
    			$update_data['assign_status'] = 2;
    			$res1 = $this->Mhouses_orders->update_info($update_data,array("id" => $order_id));
    			 
    			if($res1) {
    				$this->success("保存并通知成功","/housesassign/detail?order_id=".$order_id);
    			}
    		}
    
    		$this->error("保存失败");
    
    	}
    	 
    	$where = [];
    	$where['is_del'] = 0;
    	$group_by = ['houses_id'];
    	$list = $this->Mhouses_points->get_lists('houses_id,count(0) as count', $where, [],  0,0,  $group_by);  //点位分组
    	 
    	if($list) {
    		$houses_ids = array_column($list, 'houses_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $houses_ids;
    		$hlist = $this->Mhouses->get_lists('id,name,province,city,area', $where);  //楼盘信息
    
    		if($hlist) {
    			foreach ($list as $k => &$v) {
    				foreach ($hlist as $k1 => $v1) {
    					if($v['houses_id'] == $v1['id']) {
    						$v['ad_area'] = $v1['province']."-".$v1['city']."-".$v1['area'];
    						$v['houses_name'] = $v1['name'];
    					}
    				}
    			}
    		}
    
    	}
    	 
    	$data['list'] = $list;
    	
    	$data['assign_list'] = $this->Mhouses_assign->get_lists('id,houses_id,charge_user,assign_user,assign_time,status', ['order_id' => $data['order_id'], 'is_del' => 0]);  //点位分组
    	$this->load->view('housesassign/edit', $data);
    }
    
    /**
     * 短信通知
     */
    public function sendMsg($uid) {
    	//根据预定订单获取客户电话
    	$info = $this->Madmins->get_one('tel', ['id' => $uid]);
    	
    	if(!$info) $this->return_json(['code' => 0, 'msg' => '客户不存在']);
    	if(empty($info['tel'])){
    		$this->return_json(['code' => 0, 'msg' => '电话不能为空！']);
    	}
    	if(!preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/', $info['tel'])){
    		$this->return_json(['code' => 0, 'msg' => '客户手机号格式不正确！']);
    	}
    	//系统网址
    	$url = 'https://api.wesogou.com';
    	// 配置信息
    	$sms = C('sms.config');
    	$client = new Client(new App(['app_key' => $sms['app_key'], 'app_secret' => $sms['app_secret']]));
    	$req    = new AlibabaAliqinFcSmsNumSend();
    	$req->setRecNum($info['tel'])
    	->setSmsParam([
    			'url' => $url
    	])
    	->setSmsFreeSignName($sms['FreeSignName'])
    	->setSmsTemplateCode($sms['TemplateCode']);
    	
    	$sendRes = (array) $client->execute($req);
    	if(isset($sendRes['result'])) {
    		$res = (array) $sendRes['result'];
    		if(isset($res['success']) && $res['success'] == 1){
    			$this->return_json(['code' => 1, 'msg' => '发送成功']);
    		}
    	}
    	$this->return_json(['code' => 0, 'msg' => '短信error：'.$sendRes['sub_msg']]);
    }

}


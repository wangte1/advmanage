<?php 
/**
* 排班日志
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Networklog extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network' => 'Mnetwork',
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_log' => 'Mnetwork_log',
        ]);
        $this->data['code'] = 'net_manage';
        $this->data['active'] = 'network_log_list';
    }
    

    /**
     * 申请列表
     */
    public function index() {
    	$data = $this->data;
		
    	//媒介人员才能审核 begin
    	if($data['userInfo']['group_id'] == 3) {
    		$data['role_auth'] = 1;
    	}else { //其他角色只能查看自己的
    		$where['apply_user_id'] = $data['userInfo']['id'];
    		$data['role_auth'] = 0;
    	}
    	//end
    	
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page = $this->input->get_post('per_page') ? : '1';
    	
    	if ($this->input->get('status') != null) $where['status'] = $this->input->get('status');
    	$data['status'] = $this->input->get('status');
    	
    	if ($this->input->get('apply_user_name')) $where['like']['apply_user_name'] = $this->input->get('apply_user_name');
    	$data['apply_user_name'] = $this->input->get('apply_user_name');
    	
    	if ($this->input->get('customer')) $where['like']['customer'] = $this->input->get('customer');
    	$data['customer'] = $this->input->get('customer');
    	
    	$where['is_del'] =  null;
    	$data['list'] = $this->Mnetwork_log->get_lists("*", $where,array("apply_time" => "desc"), $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
    	$data_count = $this->Mnetwork_log->count($where);
    	$data['data_count'] = $data_count;
    	$data['page'] = $page;
    	
    	//获取分页
    	$pageconfig['base_url'] = "/networkapply";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    	
    	$this->load->view("networklog/index", $data);
    }
    
    /**
     * 详情页面
     */
    public function detail() {
    	$data = $this->data;
    	
    	//媒介人员才能审核 begin
    	if($data['userInfo']['group_id'] == 3) {
    		$data['role_auth'] = 1;
    	}else { //其他角色只能查看自己的
    		$data['role_auth'] = 0;
    	}
    	//end
    	
    	$id = $this->input->get_post('id');
    	
    	$where['is_del'] =  null;
    	$where['id'] =  $id;
    	$list = $this->Mnetwork_log->get_lists("*", $where,array("apply_time" => "asc"));
    	
    	$newArr = array();
    	foreach($list as $key => &$value) {
    		$tmpArr = json_decode($value['apply_content'], true);
    		if(count($tmpArr) > 0) {
    			foreach($tmpArr as $key1 => &$value1) {
    				$where['id'] =  (int)$key1;
    				$list1 = $this->Mnetwork->get_lists("name,type", $where);
    				
    				if($list1[0]['type']) {
    					$tmpType = $this->getNetType($list1[0]['type']);
    					$list1[0]['mod'] = $tmpType['mod'];
    					if($tmpType['mod'] == 1) {
    						$data['mod'] = '腾讯房产';
    					}else {
    						$data['mod'] = '凤凰房产';
    					}
    					$list1[0]['type_name'] = $tmpType['name'];
    				}
    				
    				$value['networkinfo'][$key1] = $list1[0];
    			}
    		}
    		$value['apply_content'] = $tmpArr;
    		
    		foreach($value['apply_content'] as $key2=>&$value2) {
    			foreach ($value2 as $k3=> $v3) {
    				$newArr[$key2][$v3['dateid']] = $v3;
    			}
    		}
    		
    	}
    		
    	$data['list1'] = $list;
    	$data['newArr'] = $newArr;
    	
    	$this->load->view("networklog/detail", $data);
    }
    
    /*
     * 获取network的tab页
     */
    public function getNetType($type) {
    	$where['is_del'] =  null;
    	$where['id'] = $type;
    	$list = $this->Mnetwork_type->get_lists("name, mod", $where);
    	if(count($list) > 0) {
    		return $list[0];
    	}
    	 
    	return null;
    }

}


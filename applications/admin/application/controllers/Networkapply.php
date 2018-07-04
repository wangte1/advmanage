<?php 
/**
* 排班申请
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Networkapply extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network' => 'Mnetwork',
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_apply' => 'Mnetwork_apply',
        ]);
        $this->data['code'] = 'net_manage';
        $this->data['active'] = 'network_apply_list';
    }
    

    /**
     * 申请列表
     */
    public function index() {
    	$data = $this->data;
		
    	//媒介人员才能审核 begin
    	if($data['userInfo']['group_id'] == 3 || $data['userInfo']['group_id'] == 1) {
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
    	$data['list'] = $this->Mnetwork_apply->get_lists("*", $where,array("apply_time" => "desc"), $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
    	$data_count = $this->Mnetwork_apply->count($where);
    	$data['data_count'] = $data_count;
    	$data['page'] = $page;
    	
    	//获取分页
    	$pageconfig['base_url'] = "/networkapply";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    	
    	$this->load->view("networkapply/index", $data);
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
    	$list = $this->Mnetwork_apply->get_lists("*", $where,array("apply_time" => "asc"));
    	
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
    		
    	}
    		
    	$data['list1'] = $list;
    	
    	$this->load->view("networkapply/detail", $data);
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
    
    /**
     * 撤回
     */
    public function takeback() {
    	$data = $this->data;
    	$id = $this->input->get_post('id');
    	
    	if($id) {
    		$where['id'] = $id;
    		$editInfo['is_del'] = 1;
    		$updateid = $this->Mnetwork_apply->update_info($editInfo, $where);
    		
    		if($updateid) {
    			$this->return_json(array("code"=>0,"msg"=>"撤回成功！"));
    		}
    	}
    	
    	$this->return_json(array("code"=>0,"msg"=>"撤回失败！"));
    	
    }
	
    /**
     * 通过
     */
   	public function pass() {
   		$data = $this->data;
   		 
   		$id = $this->input->get_post('id');
   		$pass = $this->input->get_post('pass');
   		
   		$where['is_del'] =  null;
   		$where['id'] =  $id;
   		$list = $this->Mnetwork_apply->get_lists("apply_content,year,month", $where);
   		
   		$mark = false;
   		
   		$tmpArr = [];
   		if(isset($list[0]['apply_content'])) {
   			$tmpArr = json_decode($list[0]['apply_content'], true); 
   		}
   		
   		if(count($tmpArr) > 0) {
   			foreach($tmpArr as $key => $value) {
   				//var_dump($value); //小的
   				$where0['is_del'] =  null;
   				$where0['id'] =  (int)$key;
   				$where0['year'] =  $list[0]['year'];
   				$where0['month'] =  $list[0]['month'];
   				$tmpList = $this->Mnetwork->get_lists("id,name,content", $where0);
   				//var_dump($tmpList);
   				//exit;
   				
   				$tmpArr2 = json_decode($tmpList[0]['content'], true);
   				$tmpIdArr = $tmpList[0]['id'];
   				//var_dump($tmpArr2); //大的
   				if(is_array($value)) {
	   				foreach( $value as $val ){
	   					if(is_array($tmpArr2)) {
	   						foreach($tmpArr2 as $val2) {
	   							if($val['dateid'] == $val2['dateid']) {
	   								$mark = true;
	   								break;
	   							}
	   						}
	   					}
	   					
	   				}
   				}
   			}
   		}else {
   			$this->return_json(array("code"=>0,"msg"=>"处理失败，用户已经撤回！"));
   		}
   		
   		if($mark == true) {
   			$this->return_json(array("code"=>0,"msg"=>"处理失败，预定的天数中有的已被占用！"));
   		}else {
   			
   			if(count($tmpArr) > 0) {
   				foreach($tmpArr as $key => $value) {
   					if($value && $key) {
   						$where0['id'] =  (int)$key;
   						$tmpList = $this->Mnetwork->get_lists("content", $where0);
   						$tmpArr = json_decode($tmpList[0]['content'], true);
   						if(count($tmpArr) > 0) {
   						    $tmpvalue = array_merge($value,$tmpArr);
   						}else {
   						    $tmpvalue = $value;
   						}
   						 
   						$editInfo['content'] = json_encode($tmpvalue);
   						$ids = $this->Mnetwork->update_info($editInfo, $where0);
   						
   					}	
   				}
   			}
   			
   			
   			$where1['id'] = $id;
   			$where1['is_del'] = null;
   			$editInfo1['reply_content'] = $pass;
   			$editInfo1['reply_time'] = time();
   			$editInfo1['status'] = 1;
   			$ids = $this->Mnetwork_apply->update_info($editInfo1, $where1);
   			
   			$this->return_json(array("code"=>1,"msg"=>"处理成功！"));
   		}
   	}
   	
   	/**
   	 * 不通过
   	 */
   	function nopass() {
   		$data = $this->data;
   		
   		$id = $this->input->get_post('id');
   		$nopass = $this->input->get_post('nopass');
   		
   		$where1['id'] = $id;
   		$where1['is_del'] = null;
   		$editInfo1['reply_content'] = $nopass;
   		$editInfo1['reply_time'] = time();
   		$editInfo1['status'] = 2;
   		$ids = $this->Mnetwork_apply->update_info($editInfo1, $where1);
   		if($ids) {
   			$this->return_json(array("code"=>1,"msg"=>"处理成功！"));
   		}else {
   			$this->return_json(array("code"=>0,"msg"=>"处理失败！"));
   		}
   		
   	}
   	
   	/**
   	 * 上画
   	 */
   	function used() {
   		$data = $this->data;
   		$id = $this->input->get_post('id');
   		
   		
   		//媒介人员才能审核 begin
   		if($data['userInfo']['group_id'] != 3) {
   			$this->return_json(array("code"=>0,"msg"=>"您没有上画权限！"));
   		}
   		//end
   		
   		
   		$where['is_del'] =  null;
   		$where['id'] =  $id;
   		$list = $this->Mnetwork_apply->get_lists("apply_content", $where);
   		
   		//var_dump($list[0]['apply_content']);
   		$newArr = json_decode($list[0]['apply_content'], true);
   		
   		if(count($newArr) > 0) {
   			foreach($newArr as $key => $value) {
   				$where['id'] =  (int)$key;
   				
   				$tmpList = $this->Mnetwork->get_lists("content", $where);
    			$tmpArr = json_decode($tmpList[0]['content'], true);
    			 
    			if(count($tmpArr) > 0) {
    
    				foreach($tmpArr as $key2 => &$value2) {	//原有的
    					foreach($value as $key3 => $value3) {	//取消的
    						//if($value2['userid'] == $data['userInfo']['id'] && $value2['dateid'] == $value3['dateid']) {
    						if($value2['dateid'] == $value3['dateid']) {
    							$value2['status'] = 'used';
    							break;
    						}
    					}
    				}
    
    			}
    			
    			$editInfo['content'] = json_encode($tmpArr);
    			$ids = $this->Mnetwork->update_info($editInfo, $where);
    			
   			}
   			
   			if($ids) {
   				
   				$where1['is_del'] = null;
   				$where1['id'] = $id;
   				$editInfo1['status'] = 3;
   				$ids2 = $this->Mnetwork_apply->update_info($editInfo1, $where1);
   				
   				
   				$this->return_json(array("code"=>1,"msg"=>"上画成功！"));
   			}else {
   				$this->return_json(array("code"=>0,"msg"=>"上画失败！"));
   			}
   		}
   		
   	}
   	
   	/*
   	 * 取消预定
   	 */
   	public function unOrder() {
   		$data = $this->data;
   		$id = $this->input->get_post('id');
   		
   		//媒介人员才能审核 begin
   		if($data['userInfo']['group_id'] != 3) {
   			$this->return_json(array("code"=>0,"msg"=>"您没有权限取消预定！"));
   		}
   		//end
   		
   		$where['is_del'] =  null;
   		$where['id'] =  $id;
   		$list = $this->Mnetwork_apply->get_lists("apply_content", $where);
   		
   		$newArr = json_decode($list[0]['apply_content'], true);
   		 
   		if(count($newArr) > 0) {
   			foreach($newArr as $key => $value) {
   				$where['id'] =  (int)$key;
   					
   				$tmpList = $this->Mnetwork->get_lists("content", $where);
   				$tmpArr = json_decode($tmpList[0]['content'], true);
   		
   				if(count($tmpArr) > 0) {
   		
   					foreach($tmpArr as $key2 => &$value2) {	//原有的
   						foreach($value as $key3 => $value3) {	//取消的
   							//if($value2['userid'] == $data['userInfo']['id'] && $value2['dateid'] == $value3['dateid']) {
   							if($value2['dateid'] == $value3['dateid'] && $value2['status'] == 'order') {
   								unset($tmpArr[$key2]);
   								break;
   							}
   						}
   					}
   		
   				}
   				 
   				$editInfo['content'] = json_encode($tmpArr);
   				$ids = $this->Mnetwork->update_info($editInfo, $where);
   				 
   			}
   		
   			if($ids) {
   					
   				$where1['is_del'] = null;
   				$where1['id'] = $id;
   				$editInfo1['status'] = 4;
   				$ids2 = $this->Mnetwork_apply->update_info($editInfo1, $where1);
   					
   					
   				$this->return_json(array("code"=>1,"msg"=>"取消预定成功！"));
   			}else {
   				$this->return_json(array("code"=>0,"msg"=>"取消预定失败！"));
   			}
   		}
   		
   	}
   	
   	/*
   	 * 取消上画
   	 */
   	public function unUsed() {
   		$data = $this->data;
   		$id = $this->input->get_post('id');
   	
   		//媒介人员才能审核 begin
   		if($data['userInfo']['group_id'] != 3) {
   			$this->return_json(array("code"=>0,"msg"=>"您没有权限取消上画！"));
   		}
   		//end
   	
   		$where['is_del'] =  null;
   		$where['id'] =  $id;
   		$list = $this->Mnetwork_apply->get_lists("apply_content", $where);
   	
   		$newArr = json_decode($list[0]['apply_content'], true);
   	
   		if(count($newArr) > 0) {
   			foreach($newArr as $key => $value) {
   				$where['id'] =  (int)$key;
   	
   				$tmpList = $this->Mnetwork->get_lists("content", $where);
   				$tmpArr = json_decode($tmpList[0]['content'], true);
   	
   				if(count($tmpArr) > 0) {
   						
   					foreach($tmpArr as $key2 => &$value2) {	//原有的
   						foreach($value as $key3 => $value3) {	//取消的
   							//if($value2['userid'] == $data['userInfo']['id'] && $value2['dateid'] == $value3['dateid']) {
   							if($value2['dateid'] == $value3['dateid']) {
   								unset($tmpArr[$key2]);
   								break;
   							}
   						}
   					}
   						
   				}
   	
   				$editInfo['content'] = json_encode($tmpArr);
   				$ids = $this->Mnetwork->update_info($editInfo, $where);
   	
   			}
   				
   			if($ids) {
   	
   				$where1['is_del'] = null;
   				$where1['id'] = $id;
   				$editInfo1['status'] = 5;
   				$ids2 = $this->Mnetwork_apply->update_info($editInfo1, $where1);
   	
   	
   				$this->return_json(array("code"=>1,"msg"=>"取消上画成功！"));
   			}else {
   				$this->return_json(array("code"=>0,"msg"=>"取消上画失败！"));
   			}
   		}
   	
   	}

}


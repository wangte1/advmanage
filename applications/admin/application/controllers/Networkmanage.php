<?php 
/**
* 网络资源管理
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Networkmanage extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network' => 'Mnetwork',
        	'Model_network_base' => 'Mnetwork_base',
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_apply' => 'Mnetwork_apply',
        	'Model_network_log' => 'Mnetwork_log',
        ]);
        $this->data['code'] = 'net_manage';
        $this->load->driver('cache');
    }
    
    //腾讯
    public function goindex1() {
    	
    	$mod = 1;
    	$year = $this->input->get_post('year') ? :date('Y');
    	$month = $this->input->get_post('month') ? :date('m');
    	$this->data['active'] = 'network_manage_list';
    	
    	//页面记忆
    	if($this->input->get_post('year') && $this->input->get_post('month')) {
    		$myDate = array($this->input->get_post('year'), $this->input->get_post('month'));
    		$_SESSION['mod1'] = $myDate;
    	}
    	if(isset($_SESSION['mod1'])) {
    		$year = $_SESSION['mod1'][0];
    		$month = $_SESSION['mod1'][1];
    	}
    	
    	$this->index($mod, $year, $month);
    	
    }
    
    //凤凰
    public function goindex2() {
    	
    	//$mod = $this->input->get_post('mod')? : 2;
    	$mod = 2;
    	$year = $this->input->get_post('year') ? :date('Y');
    	$month = $this->input->get_post('month') ? :date('m');
    	$this->data['active'] = 'network_manage_fh_list';
    	
    	//页面记忆
    	if($this->input->get_post('year') && $this->input->get_post('month')) {
    		$myDate = array($this->input->get_post('year'), $this->input->get_post('month'));
    		$_SESSION['mod2'] = $myDate;
    	}
    	if(isset($_SESSION['mod2'])) {
    		$year = $_SESSION['mod2'][0];
    		$month = $_SESSION['mod2'][1];
    	}
    	
    	$this->index($mod, $year, $month);
    	
    }
    

    /**
     * 网络资源列表
     */
    public function index($mod, $year, $month) {
    	
    	$data = $this->data;
        $data['mod'] = $mod;
        $weekArr = [];
        
        //一个星期之内预定了没有上画的自动删除
        $this->checkOrder();
        
        $days = date('t', strtotime($year.'-'.$month));
        
        $data['year'] = $year;
        $data['month'] = $month;
        $data['days'] = $days;
        
        for($i = 0; $i < $days; $i++) {
        	$weekArr[$i] = $this->get_week($year.'-'.$month.'-'.($i+1));
        }
        
        $data['list1'] = $this->getNetType($mod);
        
        
        $tmplist1 = $this->getNetInfo($year, $month);
        $tmplist2 = $this->getNetBaseInfo();
        
        if(count($tmplist2) > 0) {
        	foreach($tmplist2 as $k => &$v) {
        		$v['base_id'] = -1;
        		if(count($tmplist1) > 0) {
        			foreach($tmplist1 as $k1 => $v1) {
        				if($v['id'] == $v1['base_id']) {
        					$v['name'] = $v1['name'];
        					$v['content'] = $v1['content'];
        					$v['base_id'] = $v1['base_id'];
        					break;
        				}
        			}
        		}
        	}
        }
        
        $data['list2'] = $tmplist2;
        
        $newArr = array();
        if(count($data['list2']) > 0) {
        	foreach($data['list2'] as $key=>&$value) {
        		$value['content'] = json_decode($value['content'], true);
        		if(count($value['content']) > 0) {
        			foreach($value['content'] as $key2=>&$value2) {
        				$newArr[$key][$value2['dateid']] = $value2;
        			}
        		}
        	}
        }
        
        
        $data['newArr'] = $newArr;
        $data['weeks'] = $weekArr;
        $_SESSION['weeks'] = $weekArr;
        $data['roleid'] = $data['userInfo']['group_id'];
        $this->load->view("networkmanage/index", $data);
    }
    
    /*
     * 获取network的tab页，mod=1代表腾讯，mod=2代表凤凰
     */
    public function getNetType($mod) {
    	$where['is_del'] =  null;
    	$where['mod'] = $mod;
    	$list = $this->Mnetwork_type->get_lists("id,name,sort", $where,array("sort" => "asc"));
    	if(count($list) > 0) {
    		return $list;
    	}
    	
    	return null;
    }
    
    /*
     * 获取network的信息
     */
    public function getNetInfo($year, $month) {
    	$where['is_del'] =  null;
    	$where['year'] =  $year;
    	$where['month'] =  $month;
    	$list = $this->Mnetwork->get_lists("id,name,type,format,content,base_id", $where,array("sort" => "asc"));
    	if(count($list) > 0) {
    		return $list;
    	}
    	 
    	return null;
    }
    
    /*
     * 获取network_base的信息
     */
	public function getNetBaseInfo($id = null) {
		
		if(!empty($id)) {
			$where['id'] = $id;
			$list = $this->Mnetwork_base->get_lists("id,name,type,format,sort,content", $where, array("sort" => "asc"));
		}else {
			$list = $this->cache->file->get('netbase');
			 
			if(!$list) {
				$where['is_del'] =  null;
				$list = $this->Mnetwork_base->get_lists("id,name,type,format,sort,content", $where, array("sort" => "asc"));
				if($list) {
					$this->cache->file->save('netbase', $list, 5*60);//缓存5分钟
				}
			}
		}
		
    	if(count($list) > 0) {
    		return $list;
    	}
    	
    	return null;
    }
    
    /*
     * 预定
     */
    public function order() {
    	$data = $this->data;
    	 
    	$checkstr = $this->input->get_post('checkstr');
    	$customer = $this->input->get_post('customer');
    	$year = $this->input->get_post('year');
    	$month = $this->input->get_post('month');
    	$days = $this->input->get_post('days');
    	$gobase = $this->input->get_post('gobase');
    	$baseid = $this->input->get_post('baseid');
    	
    	$nowYear = (int)date('Y');
    	$nowMonth = (int)date('m');
    	
    	if(($nowYear == $year && $nowMonth > (int)$month) || $nowYear > (int)$year) {
    		$this->return_json(array("code"=>0,"msg"=>"预定日期只能是当月或之后的日期"));
    	}
    	 
    	$newArr = array();
    	 
    	if(!empty($checkstr)) {
    		$checkArr = explode(",",$checkstr);
    		$baseidArr = explode(",",$baseid);
    
    		if(count($checkArr) > 0) {
    			foreach($checkArr as $key => $value) {
    				
    				$tmpPos = strpos($value, "-");
    				$tmpkey = substr($value, 0, $tmpPos);
    				$tmpvalue = substr($value, $tmpPos+1, count($value)+1);

    				if($baseidArr[$key] == -1) {//-1表示baseid要重新获取
    					$tmpbase = $this->check_insert($tmpkey, $year, $month);
    					//$tmpkey = $tmpbase;
    					
    					if(empty($tmpbase)) {//没有找到，需要插入记录
    						$baseInfo = $this->getNetBaseInfo($tmpkey);
    						if(count($baseInfo) > 0) {
    							$i = 0;
    							foreach($baseInfo as $k => $v) {
//     								$tmpAdd['name'] = $v['name'];
//     								$tmpAdd['type'] = $v['type'];
//     								$tmpAdd['year'] = $year;
//     								$tmpAdd['month'] = $month;
//     								$tmpAdd['adform'] = $v['adform'];
//     								$tmpAdd['format'] = $v['format'];
//     								$tmpAdd['unitprice'] = $v['unitprice'];
//     								$tmpAdd['totalprice'] = $v['totalprice'];
//     								$tmpAdd['discount'] = $v['discount'];
//     								$tmpAdd['netprice'] = $v['netprice'];
//     								$tmpAdd['base_id'] = $v['id'];
//     								$tmpid = $this->Mnetwork->create($tmpAdd);
//     								$tmpkey = $tmpid;
    								$tmpAdd[$i]['name'] = $v['name'];
    								$tmpAdd[$i]['type'] = $v['type'];
    								$tmpAdd[$i]['year'] = $year;
    								$tmpAdd[$i]['month'] = $month;
    								//$tmpAdd[$i]['adform'] = $v['adform'];
    								$tmpAdd[$i]['format'] = $v['format'];
    								//$tmpAdd[$i]['unitprice'] = $v['unitprice'];
    								//$tmpAdd[$i]['totalprice'] = $v['totalprice'];
    								//$tmpAdd[$i]['discount'] = $v['discount'];
    								//$tmpAdd[$i]['netprice'] = $v['netprice'];
    								$tmpAdd[$i]['base_id'] = $v['id'];
    								
    								$i++;
    								
    							}
    							
    							$this->Mnetwork->create_batch($tmpAdd);
    							
    						}
    				
    					}
    				}
    				
    				$tmpkey = $this->check_insert($tmpkey, $year, $month);
    			
    				if($tmpkey) {
    					$newArr[$tmpkey][] = array(
    							'dateid'=>$tmpvalue,
    							'userid'=>$data['userInfo']['id'],
    							'username'=>$data['userInfo']['fullname'],
    							'optime'=>time(),
    							'customer'=>$customer,
    							'status'=>'order'
    					);
    				}
    				
    			}
    		}
    	}
    	
    	if(count($newArr) > 0) {
    		$addInfo['apply_user_id'] = $data['userInfo']['id'];
    		$addInfo['apply_user_name'] = $data['userInfo']['fullname'];
    		$addInfo['apply_time'] = time();
    		$addInfo['customer'] = $customer;
    		$addInfo['year'] = $year;
    		$addInfo['month'] = $month;
    		$addInfo['days'] = $days;
    		$addInfo['weeks'] = json_encode($_SESSION['weeks']);
    		$addInfo['apply_content'] = json_encode($newArr);
    		$addInfo['status'] = 0;
    		$id = $this->Mnetwork_apply->create($addInfo);
    		
    		if($id) {
    			$this->return_json(array("code"=>1,"msg"=>"申请预定成功！"));
    		}
    	}
    }
    
    /*
     * 检查是否需要插入记录,数据库已经添加了唯一约束
     */
    public function check_insert($baseid, $year, $month) {
    	$where['is_del'] =  null;
    	$where['base_id'] =  $baseid;
    	$where['year'] =  $year;
    	$where['month'] =  $month;
    	$list = $this->Mnetwork->get_lists("id", $where);
    	if(count($list) > 0) {
    		return $list[0]['id'];
    	}
    	 
    	return null;
    }
    
    
    
    /*
     * 取消预定
     */
    public function unOrder() {
    	$data = $this->data;
    	 
    	$checkstr = $this->input->get_post('checkstr');
    	$year = $this->input->get_post('year');
    	$month = $this->input->get_post('month');
    	$days = $this->input->get_post('days');
    	
    	$newArr = array();
    	$logArr = array();
    	
    	if(!empty($checkstr)) {
    		$checkArr = explode(",",$checkstr);
    
    		if(count($checkArr) > 0) {
    			foreach($checkArr as $key => $value) {
    				$tmpPos = strpos($value, "-");
    				$tmpkey = substr($value, 0, $tmpPos);
    				$tmpvalue = substr($value, $tmpPos+1, count($value)+1);
    				if($tmpkey) {
    					$newArr[$tmpkey][] = array(
    							'dateid'=>$tmpvalue,
    					);
    				}
    			}
    		}
    	}
    	 
    	foreach ($newArr as $key => $value) {
    		if($value && $key) {
    			//$where['id'] = (int)$key;
    			$baseid = (int)$key;
    			$tmpid = $this->check_insert($baseid, $year, $month);
    			if(!empty($tmpid)) {
    				$where['id'] = $tmpid;
    				
    				$tmpList = $this->Mnetwork->get_lists("content", $where);
    				$tmpArr = json_decode($tmpList[0]['content'], true);
    				 
    				if(count($tmpArr) > 0) {
    				
    					foreach($tmpArr as $key2 => &$value2) {	//原有的
    						foreach($value as $key3 => $value3) {	//取消的
    							if($value2['dateid'] == $value3['dateid']) {
    								
    								$logArr[$tmpid][] = array(
    										'dateid'=>$value2['dateid'],
    										'userid'=>$value2['userid'],
    										'username'=>$value2['username'],
    										'optime'=>$value2['optime'],
    										'customer'=>$value2['customer'],
    										'status'=>$value2['status']
    								);
    								
    								unset($tmpArr[$key2]);
    								break;
    							}
    						}
    					}
    				
    				}
    				
    				$editInfo['content'] = json_encode($tmpArr);
    				$id = $this->Mnetwork->update_info($editInfo, $where);
    				
    			}
    			
    		}
    	}
    	
    	if(count($logArr) > 0) {
    		$addInfo['apply'] = '取消预定';
    		$addInfo['apply_user_id'] = $data['userInfo']['id'];
    		$addInfo['apply_user_name'] = $data['userInfo']['fullname'];
    		$addInfo['apply_time'] = time();
    		$addInfo['year'] = $year;
    		$addInfo['month'] = $month;
    		$addInfo['days'] = $days;
    		$addInfo['weeks'] = json_encode($_SESSION['weeks']);
    		$addInfo['apply_content'] = json_encode($logArr);
    		$id = $this->Mnetwork_log->create($addInfo);
    	
    		if($id) {
    			$this->return_json(array("code"=>1,"msg"=>"取消预定成功！"));
    		}
    	}
    	
    }
    
    /*
     * 上画
     */
    public function used() {
    	
    	$data = $this->data;
    	//媒介人员才能审核 begin
    	if($data['userInfo']['group_id'] != 3) {
    		$this->return_json(array("code"=>0,"msg"=>"您没有上画权限！"));
    	}
    	//end
    	
    	$checkstr = $this->input->get_post('checkstr');
    	$year = $this->input->get_post('year');
    	$month = $this->input->get_post('month');
    	$days = $this->input->get_post('days');
    	$gobase = $this->input->get_post('gobase');
    	$baseid = $this->input->get_post('baseid');
    
    	$newArr = array();
    	
    	if(!empty($checkstr)) {
    		$checkArr = explode(",",$checkstr);
    		$baseidArr = explode(",",$baseid);
    	
    		if(count($checkArr) > 0) {
    			foreach($checkArr as $key => $value) {
    	
    				$tmpPos = strpos($value, "-");
    				$tmpkey = substr($value, 0, $tmpPos);
    				$tmpvalue = substr($value, $tmpPos+1, count($value)+1);
    	
    				$tmpkey = $this->check_insert($tmpkey, $year, $month);
    				
    				
    				if($tmpkey) {
    					$newArr[$tmpkey][] = array(
    							'dateid'=>$tmpvalue,
    					);
    				}
    	
    			}
    		}
    	}
    	 
    	 
    	foreach ($newArr as $key => $value) {
    		if($value && $key) {
    			$where['id'] = (int)$key;
    
    			$tmpList = $this->Mnetwork->get_lists("content", $where);
    			$tmpArr = json_decode($tmpList[0]['content'], true);
    			 
    			if(count($tmpArr) > 0) {
    
    				foreach($tmpArr as $key2 => &$value2) {	//原有的
    					foreach($value as $key3 => $value3) {	//取消的
    						if($value2['dateid'] == $value3['dateid']) {
    							//unset($tmpArr[$key2]);
    							$value2['status'] = 'used';
    							break;
    						}
    					}
    				}
    
    			}
    
    			$editInfo['content'] = json_encode($tmpArr);
    			$id = $this->Mnetwork->update_info($editInfo, $where);
    
    			
    		}
    	}
    	if($id) {
    		$this->return_json(array("code"=>1,"msg"=>"上画成功！"));
    	}
    }
    
    
    /*
     * 取消上画
     */
    public function unUsed() {
    	$data = $this->data;
    	//媒介人员才能审核 begin
    	if($data['userInfo']['group_id'] != 3) {
    		$this->return_json(array("code"=>0,"msg"=>"您没有上画权限！"));
    	}
    	//end
    	
    	$checkstr = $this->input->get_post('checkstr');
    	$year = $this->input->get_post('year');
    	$month = $this->input->get_post('month');
    	$days = $this->input->get_post('days');
    
    	$newArr = array();
    	$logArr = array();
    	
    	if(!empty($checkstr)) {
    		$checkArr = explode(",",$checkstr);
    
    		if(count($checkArr) > 0) {
    			foreach($checkArr as $key => $value) {
    				$tmpPos = strpos($value, "-");
    				$tmpkey = substr($value, 0, $tmpPos);
    				$tmpvalue = substr($value, $tmpPos+1, count($value)+1);
    				if($tmpkey) {
    					$newArr[$tmpkey][] = array(
    							'dateid'=>$tmpvalue,
    					);
    				}
    			}
    		}
    	}
    	 
    	
    	foreach ($newArr as $key => $value) {
    		if($value && $key) {
    			//$where['id'] = (int)$key;
    			$baseid = (int)$key;
    			$tmpid = $this->check_insert($baseid, $year, $month);
    			if(!empty($tmpid)) {
    				$where['id'] = $tmpid;
    				
    				$tmpList = $this->Mnetwork->get_lists("content", $where);
    				$tmpArr = json_decode($tmpList[0]['content'], true);
    				 
    				if(count($tmpArr) > 0) {
    				
    					foreach($tmpArr as $key2 => &$value2) {	//原有的
    						foreach($value as $key3 => $value3) {	//取消的
    							if($value2['dateid'] == $value3['dateid']) {
    								$logArr[$tmpid][] = array(
    										'dateid'=>$value2['dateid'],
    										'userid'=>$value2['userid'],
    										'username'=>$value2['username'],
    										'optime'=>$value2['optime'],
    										'customer'=>$value2['customer'],
    										'status'=>$value2['status']
    								);
    								unset($tmpArr[$key2]);
    								break;
    							}
    						}
    					}
    				
    				}
    				
    				$editInfo['content'] = json_encode($tmpArr);
    				$id = $this->Mnetwork->update_info($editInfo, $where);
    				
    				
    				
    			}
    		}
    		
//     		if($id) {
//     			$this->return_json(array("code"=>1,"msg"=>"取消上画成功！"));
//     		}
    	}
    	
    	
    	if(count($logArr) > 0) {
    		$addInfo['apply'] = '取消上画';
    		$addInfo['apply_user_id'] = $data['userInfo']['id'];
    		$addInfo['apply_user_name'] = $data['userInfo']['fullname'];
    		$addInfo['apply_time'] = time();
    		$addInfo['year'] = $year;
    		$addInfo['month'] = $month;
    		$addInfo['days'] = $days;
    		$addInfo['weeks'] = json_encode($_SESSION['weeks']);
    		$addInfo['apply_content'] = json_encode($logArr);
    		$id = $this->Mnetwork_log->create($addInfo);
    		 
    		if($id) {
    			$this->return_json(array("code"=>1,"msg"=>"取消上画成功！"));
    		}
    	}
    }
    
    /*
     * 导出excel
     */
    function exportExcel() {
    	
    	$data = $this->data;
    	
    	$year = $this->input->get_post('year');
    	$month = $this->input->get_post('month');
    	$days = $this->input->get_post('days');
    	$mod = $this->input->get_post('mod');
    	$weeks = $_SESSION['weeks'];
    	
    	$data = '';
    	$data .= '<table border="1"><tbody>';
    	//$data .= '<tr><th rowspan="3">所属tab</th><th rowspan="3">投放位置</th><th rowspan="3">广告形式</th><th rowspan="3">格式</th><th colspan="'.($days+2).'">'.$year.'年'.$month.'月</th><th rowspan="3">单价</th><th rowspan="3">总价</th><th rowspan="3">折扣</th><th rowspan="3">净价</th></tr>';
    	$data .= '<tr><th rowspan="3">所属tab</th><th rowspan="3">投放位置</th><th rowspan="3">格式</th><th colspan="'.($days+2).'">'.$year.'年'.$month.'月</th></tr>';
    	$data .= '<tr><th>日期</th>';
    	for($i=0; $i<$days; $i++) {
    		$data .= '<th>'.($i+1).'</th>';
    	}
    	
    	$data .= '<th rowspan="2">天数</th></tr>';
    	$data .= '<tr><th>星期</th>';
    	for($i=0; $i<$days; $i++) {
    		$data .= '<th>'.$weeks[$i].'</th>';
    	}
    	
    	$data .= '</tr>';
    	
    	//数据区 begin
    	$list1 = $this->getNetType($mod);
    	
    	$tmplist1 = $this->getNetInfo($year, $month);
    	$tmplist2 = $this->getNetBaseInfo();
    	
    	if(count($tmplist2) > 0) {
    		foreach($tmplist2 as $k => &$v) {
    			$v['base_id'] = -1;
    			if(count($tmplist1) > 0) {
    				foreach($tmplist1 as $k1 => $v1) {
    					if($v['id'] == $v1['base_id']) {
    						$v['name'] = $v1['name'];
    						$v['content'] = $v1['content'];
    						$v['base_id'] = $v1['base_id'];
    						break;
    					}
    				}
    			}
    		}
    	}
    	
    	$list2 = $tmplist2;
    	
    	$newArr = array();
    	if(count($list2) > 0) {
    		foreach($list2 as $key=>&$value) {
    			$value['content'] = json_decode($value['content'], true);
    			if(count($value['content']) > 0) {
    				foreach($value['content'] as $key2=>$value2) {
    					$newArr[$key][$value2['dateid']] = $value2;
    				}
    			}
    	
    		}
    	}
    	
    	foreach($list1 as $key1 => $val1) {
    		foreach($list2 as $key2 => $val2) {
    			if($val2['type'] == $val1['id']) {
    				$tmpType = $this->getNetTypeInfo($val1['id']);
    				//$data .= '<tr><td>'.$tmpType['name'].'</td><td>'.$val2['name'].'</td><td>'.$val2['adform'].'</td><td>'.$val2['format'].'</td><td></td>';
    				$data .= '<tr><td>'.$tmpType['name'].'</td><td>'.$val2['name'].'</td><td>'.$val2['format'].'</td><td></td>';
    				for($i=0; $i<$days; $i++) {
    					if(isset($newArr[$key2][$i+1])) {
    						if($newArr[$key2][$i+1]['status'] == 'order') {
    							//$data .= '<td class="date-td date-order" style="background-color:#FF9900;"></td>';
    							$data .= '<td class="date-td date-order" ></td>';
    						}else {
    							$data .= '<td class="date-td date-order">'.$newArr[$key2][$i+1]['customer'].'</td>';
    							//$data .= '<td class="date-td date-order" style="background-color:green;">'.$newArr[$key2][$i+1]['customer'].'</td>';
    						}
    					}else {
    						$data .= '<td class="date-td date-free" ></td>';
    					}
    				}
    				
    				//$data .= '<td></td><td>'.$val2['unitprice'].'</td><td>'.$val2['totalprice'].'</td><td>'.$val2['discount'].'</td><td>'.$val2['netprice'].'</td></tr>';
    				$data .= '</tr>';
    			}
    		}
    	}
    	//end
    	
    	$data .= '</tbody></table>';
    	
    	if($mod == 1) {
    		$filename = $year."-".$month."腾讯广告资源库存.xls";
    	}else {
    		$filename = $year."-".$month."凤凰广告资源库存.xls";
    	}
    	
    	header("Content-type: application/vnd.ms-excel; charset=utf8");
    	header("Content-Disposition: attachment; filename=".$filename);
    	
    	echo $data. "\t";
    	
    }
    
    /*
     * 获取network的tab页
     */
    public function getNetTypeInfo($type) {
    	$where['is_del'] =  null;
    	$where['id'] = $type;
    	$list = $this->Mnetwork_type->get_lists("name, mod", $where);
    	if(count($list) > 0) {
    		return $list[0];
    	}
    
    	return null;
    }
    
    /*
     * 一个周没有上画的预定项自动删除
     */
    function checkOrder() {
    	$data = $this->data;
    	
    	$lastWeek = strtotime("-1 week");
    	
    	$where['is_del'] =  null;
    	$where['status'] =  1;
    	$where['reply_time<'] =  $lastWeek;
    	//$list = $this->Mnetwork_apply->get_lists("*", $where);
    	$list = $this->Mnetwork_apply->get_lists("apply_content", $where);
    	
    	if(count($list) > 0) {
    		foreach($list as $key => $value) {
    			$tmpArr = json_decode($value['apply_content'], true); //申请内容
    		
    			if(count($tmpArr) > 0) {
    				foreach($tmpArr as $key1 => $val1) {
    					
    					$where1['is_del'] =  null;
    					$where1['id'] =  (int)$key1;
    					
    					$tmplist = $this->Mnetwork->get_lists("content", $where1);
    					$conentArr = json_decode($tmplist[0]['content'], true);
    					
    					foreach($conentArr as $k=>$v) if(in_array($v, $val1)) unset($conentArr[$k]);
    					
    					$editInfo['content'] = json_encode($conentArr);
    					$ids = $this->Mnetwork->update_info($editInfo, $where1);
    				}
    			}
    		}
    		
    		if($ids) {
    			$editInfo2['is_del'] =  1;
    			$where2['is_del'] =  null;
    			$where2['status'] =  1;
    			$where2['reply_time<'] =  $lastWeek;
    			$ids2 = $this->Mnetwork_apply->update_info($editInfo2, $where2);
    		}
    		
    	}
    	
    }
    
    /*
     * 通过日期获取星期
     */
    public function get_week($date){
    	//强制转换日期格式
    	$date_str=date('Y-m-d',strtotime($date));
    	 
    	//封装成数组
    	$arr=explode('-', $date_str);
    
    	//参数赋值
    	//年
    	$year=$arr[0];
    
    	//月，输出2位整型，不够2位右对齐
    	$month=sprintf('%02d',$arr[1]);
    
    	//日，输出2位整型，不够2位右对齐
    	$day=sprintf('%02d',$arr[2]);
    
    	//时分秒默认赋值为0；
    	$hour = $minute = $second = 0;
    
    	//转换成时间戳
    	$strap = mktime($hour,$minute,$second,$month,$day,$year);
    
    	//获取数字型星期几
    	$number_wk=date('w',$strap);
    
    	//自定义星期数组
    	$weekArr=array('日','一','二','三','四','五','六');
    
    	//获取数字对应的星期
    	return $weekArr[$number_wk];
    }

}


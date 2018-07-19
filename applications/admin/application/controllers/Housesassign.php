<?php 

use YYHSms\SendSms;

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
        	'Model_houses_change_pic_orders' => 'Mhouses_changepicorders',
            'Model_houses_customers' => 'Mhouses_customers',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses_assign_down' => 'Mhouses_assign_down',
            'Model_houses_work_order' => 'Mhouses_work_order',
        	'Model_houses_work_order_detail' => 'Mhouses_work_order_detail',
        	'Model_salesman' => 'Msalesman',
        	'Model_make_company' => 'Mmake_company',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        	'Model_houses_change_points_record' => 'Mhouses_change_points_record',
        	'Model_houses_status_operate_time' => 'Mhouses_status_operate_time',
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_assign_list';

        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['order_type_text'] = C('housesorder.houses_order_type'); //订单类型
        $this->data['point_addr'] = C('housespoint.point_addr'); //订单类型
        $this->data['houses_assign_type'] = C('housesorder.houses_assign_type'); //派单类型
        $this->data['houses_assign_status'] = C('housesorder.houses_assign_status'); //派单状态
    }
    

    /**
     * 制作完成至上画完成的订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where = [];
        if ($this->input->get('order_code')) $where['A.order_code'] = $this->input->get('order_code');
        if ($this->input->get('order_type')) $where['A.order_type'] = $this->input->get('order_type');
        if ($this->input->get('customer_id')) $where['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('assign_status')) $where['A.assign_status'] = $this->input->get('assign_status');
        $assign_type = $data['assign_type'] = $this->input->get('assign_type') ? : 1;
        if ($data['assign_type'] == 2 || $data['assign_type'] == 1) {	//上画和下画派单
            $where['in'] = ['A.order_status' => [3,4,5,6,7]];
        	$where['A.assign_type'] = $data['assign_type'];
        	$tmp_moudle = $this->Mhouses_orders;
        }else {			
            //换画派单
        	$tmp_moudle = $this->Mhouses_changepicorders;
        }
        if($data['userInfo']['group_id'] == C('group.gc')){
            $where['A.group_id'] = $data['userInfo']['id'];
        }

        $data['order_code'] = $this->input->get('order_code');
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['assign_status'] = $this->input->get('assign_status');

        $data['list'] = $tmp_moudle->get_order_lists($where, ['A.create_time'=>'desc'], $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
        //var_dump($data['list']);
        //echo $this->db->last_query();exit;
        $data_count = $tmp_moudle->get_order_lists($where);
        $data['data_count'] = (int) count($data_count);
        $data['page'] = $page;
        
        //提取组长id
        $data['groupList'] = [];
        $group_id = array_column($data['list'], 'group_id');
        if(count($group_id)){
            $group_id = array_unique($group_id);
            $group_list = $this->Madmins->get_lists('id, fullname', ['in' => ['id' => $group_id]]);
            if($group_list){
                $data['groupList'] = $group_list;
            }
        }
        
        if($data['list']){
            $ordercodes = array_column($data['list'], 'order_code');
            $orderList = $this->Mhouses_orders->get_lists('order_code, order_type, release_start_time, release_end_time, total_price', ['in' => ['order_code' => $ordercodes], 'pid' => 0]);
            if($orderList){
                foreach ($orderList  as $k => $v){
                    foreach ($data['list'] as $k1 => $v1){
                        $data['list'][$k1]['order_type'] = $v['order_type'];
                        $data['list'][$k1]['release_start_time'] = $v['release_start_time'];
                        $data['list'][$k1]['release_end_time'] = $v['release_end_time'];
                        $data['list'][$k1]['total_price'] = $v['total_price'];
                    }
                }
            }
        }

        //获取分页
        $pageconfig['base_url'] = "/housesassign";
        $pageconfig['total_rows'] = $data['data_count'];
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $data['status_text'] = C('order.order_status.text');
        

        //未确认派单的数量
        $data['no_confirm_count1'] = $this->Mhouses_orders->get_order_count(['A.order_status'=> 3, 'A.assign_status'=>1]);
        $data['no_confirm_count2'] = $this->Mhouses_orders->get_order_count(['A.order_status'=> 7, 'A.assign_status'=>1]);
        $data['no_confirm_count3'] = $this->Mhouses_changepicorders->get_order_count(['A.order_status'=> 3, 'A.assign_status'=>1]);

        $this->load->view("housesassign/index", $data);
    }
    
    /*
     * 派单
     */
    public function new_assign() {
        $data = $this->data;
        
        $where['is_del'] = 0;
        if ($this->input->get('order_id')) $data['order_id'] =  $this->input->get('order_id');
        $assign_type = $this->input->get('assign_type');
        if($assign_type == 3) {
            //换画
            $tmp_moudle = $this->Mhouses_changepicorders;
        }else {
            //1上画，2下画
            $tmp_moudle = $this->Mhouses_orders;
        }
        if(IS_POST){
            $post_data = $this->input->post();
            $order_id = $this->input->post('order_id');
            $houses_ids = $this->input->post('houses_id');
            $points_counts = $this->input->post('points_count');
            $charge_users = $this->input->post('charge_user');
            $remark = $this->input->post('remark');
            //判断是否已经有派过
            $orderoList = $tmp_moudle->get_lists('id', ['pid' => $order_id]);
            if($orderoList){
                //提取ids
                $ids = array_column($orderoList, 'id');
                //删除订单
                $res = $tmp_moudle->delete(['in' => ['id' => $ids]]);
                if(!$res){
                    if(!$res) $this->write_log($data['userInfo']['id'], 3, "未能删除已生成的子订单".$this->db->last_query());
                }
                $this->write_log($data['userInfo']['id'], 3, "删除已生成的子订单".$this->db->last_query());
                $res = $this->Mhouses_work_order->delete(['in' => ['order_id' => $ids]]);
                if(!$res) {
                    $this->write_log($data['userInfo']['id'], 3, "未能删除已派工单".$this->db->last_query());
                }
                $this->write_log($data['userInfo']['id'], 3, "删除已派工单".$this->db->last_query());
            }
            
            $add_data = [];
            $tmp_arr = [];
            $i = $j = 0;
            foreach ($houses_ids as $k => $v) {
                
                if($charge_users[$k] == '') {	//通过楼栋派单
                    $tmp1 = array_filter(explode(',', $post_data['ban_charge'][$k]));
                    $tmp2 = explode(',', $post_data['ban_remark'][$k]);
                    $tmp3 = explode(',', $post_data['ban'][$k]);
                    $tmp4 = explode(',', $post_data['ban_count'][$k]);
                    $tmp5 = explode(',', $post_data['area_id'][$k]);
                    foreach($tmp1 as $k1 => $v1) {
                        $tmp_arr[$j]['type'] = $assign_type;
                        $tmp_arr[$j]['order_id'] = $order_id;
                        $tmp_arr[$j]['houses_id'] = $v;
                        $tmp_arr[$j]['area_id'] = $tmp5[$k1];
                        $tmp_arr[$j]['ban'] = $tmp3[$k1];
                        $tmp_arr[$j]['points_count'] = $tmp4[$k1];
                        $tmp_arr[$j]['charge_user'] = $v1;
                        $tmp_arr[$j]['assign_user'] = $data['userInfo']['id'];
                        $tmp_arr[$j]['assign_time'] = date("Y-m-d H:i:s");
                        if(isset($tmp2[$k1])) {
                            $tmp_arr[$j]['remark'] = $tmp2[$k1];
                        }
                        $j++;
                    }
                }else {
                    $add_data[$i]['type'] = $assign_type;
                    $add_data[$i]['order_id'] = $order_id;
                    $add_data[$i]['houses_id'] = $v;
                    $add_data[$i]['area_id'] = 0;
                    $add_data[$i]['ban'] = '';
                    $add_data[$i]['points_count'] = $points_counts[$k];
                    $add_data[$i]['charge_user'] = $charge_users[$k];
                    $add_data[$i]['assign_user'] = $data['userInfo']['id'];
                    $add_data[$i]['assign_time'] = date("Y-m-d H:i:s");
                    if(isset($remark[$k])) {
                        $add_data[$i]['remark'] = $remark[$k];
                    }
                    $add_data[$i]['type'] = $this->input->get('assign_type');
                }
                $i++;
            }
            
            if(count($tmp_arr) > 0) {
                $add_data = array_merge_recursive($add_data,$tmp_arr);
            }
            //获取工程组长各自分配好的点位；
            $group_data = [];
            $group = array_unique(array_column($add_data, 'charge_user'));
            //初始化组长数据
            foreach ($group as $k => $v){
                $group_data[$k]['id'] = $v;
                $group_data[$k]['houses_ids'] = '';
            }
            
            //匹配各个组长应分配的点位
            $orderInfo = $tmp_moudle->get_one('*', ['id' => $order_id]);
            $point_ids = array_unique(explode(',', $orderInfo['point_ids']));
            $point_lists = $this->Mhouses_points->get_lists('*', ['in' => ['id' => $point_ids]]);
            foreach ($group_data as $k => $v){
                foreach ($add_data as $key => $val){
                    if($val['charge_user'] == $v['id']){
                        foreach ($point_lists as $keys => $vals){
                            //如果一个人负责了一个楼盘
                            if($val['area_id'] == "" && $val['ban'] ==""){
                                if($vals['houses_id'] == $val['houses_id']){
                                    $group_data[$k]['point_ids'][] = $vals['id'];
                                }
                            }
                            //多人负责
                            if($vals['houses_id'] == $val['houses_id'] && $vals['area_id'] == $val['area_id'] && $vals['ban'] == $val['ban']){
                                $group_data[$k]['point_ids'][] = $vals['id'];
                            }
                        }
                    }
                }
            }
            
            //将订单拆分，由工程组长负责
            $insert_data = [];
            unset($orderInfo['id']);
            foreach ($group_data as $k => $v){
                $insert_data[$k] = $orderInfo;
                $insert_data[$k]['assign_status'] = 1;
                $insert_data[$k]['pid'] = $order_id;
                $insert_data[$k]['order_code'] = $orderInfo['order_code'];
                $insert_data[$k]['point_ids'] = implode(',', $v['point_ids']);
                $insert_data[$k]['group_id'] = $v['id'];
            }
            //批量插入
            $res = $tmp_moudle->create_batch($insert_data);
            if(!$res) $this->error('分配失败');
            $tmp_moudle->update_info(['assign_status' => 2], ['id' => $order_id]);
            echo "<script>alert('派单成功！');parent.location.reload();location.href='/housesassign/detail?order_id=".$order_id."&assign_type=".$assign_type."'</script>";
        }
        
        //从订单获取点位信息
        $tmp_order = $tmp_moudle->get_one('id,point_ids', ['id'=>$data['order_id']]);
        $where['in']['id'] = explode(',', $tmp_order['point_ids']);
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
        $where['group_id'] = C('group.gc');	//工程人员角色
        
        $tmp_users = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
        $data['user_list'] = array_column($tmp_users, 'fullname', 'id');
        
        
        if($this->input->get('assign_type') == 2) { //下画
            $assign_list = $this->Mhouses_assign->get_lists('*', ['order_id' => $data['order_id'], 'type' => 1, 'is_del' => 0]);
            
            //获取上画人的信息
            if(isset($assign_list)) {
                
                $result =   [];
                $tmp_upload_arr = [];
                foreach($assign_list as $k1=>$v1){
                    $result[$v1['houses_id']][] = $v1;
                }
                
                foreach ($result as $k2 => $v2) {
                    $tmp_upload_arr[$k2]['charge_user'] = '';
                    $tmp_upload_arr[$k2]['houses_id'] = $v2[0]['houses_id'];
                    if(count($v2) > 1) {
                        foreach ($v2 as $k3 => $v3) {
                            $tmp_upload_arr[$k2]['charge_user'] = $tmp_upload_arr[$k2]['charge_user'].','.$v3['charge_user'];
                        }
                    }else {
                        $tmp_upload_arr[$k2]['charge_user'] = $v2[0]['charge_user'];
                    }
                    
                }
                $data['assign_list'] = $tmp_upload_arr;
            }
        }
        
        $data['assign_type'] = $this->input->get('assign_type');
        
        $this->load->view('housesassign/new_assign', $data);
    }
    
    /*
     * 派单
     */
    public function assign() {
    	$data = $this->data;
    	
    	$where['is_del'] = 0;
    	if ($this->input->get('order_id')) $data['order_id'] =  $this->input->get('order_id');
    	$assign_type = $this->input->get('assign_type');
    	if($assign_type == 3) {
    	    //换画
    	    $tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    	    //1上画，2下画
    	    $tmp_moudle = $this->Mhouses_orders;
    	}
    	if(IS_POST){
    		$post_data = $this->input->post();
    		$order_id = $this->input->post('order_id');
    		$houses_ids = $this->input->post('houses_id');
    		$points_counts = $this->input->post('points_count');
    		$charge_users = $this->input->post('charge_user');
    		$remark = $this->input->post('remark');
    		
            //获取订单的父id
    		$orderInfo = $tmp_moudle->get_one('pid', ['id' => $order_id]);
    		$add_data = [];
    		$tmp_arr = [];
    		$i = $j = 0;
    		foreach ($houses_ids as $k => $v) {
    			if($charge_users[$k] == '') {	//通过楼栋派单
    				$tmp1 = array_filter(explode(',', $post_data['ban_charge'][$k]));
    				$tmp2 = explode(',', $post_data['ban_remark'][$k]);
    				$tmp3 = explode(',', $post_data['ban'][$k]);
    				$tmp4 = explode(',', $post_data['ban_count'][$k]);
    				$tmp5 = explode(',', $post_data['area_id'][$k]);
    				foreach($tmp1 as $k1 => $v1) {
    						$tmp_arr[$j]['type'] = $assign_type;
    						$tmp_arr[$j]['order_id'] = $orderInfo['pid'];
    						$tmp_arr[$j]['true_order_id'] = $order_id;
    						$tmp_arr[$j]['houses_id'] = $v;
    						$tmp_arr[$j]['area_id'] = $tmp5[$k1];
    						if(empty($tmp3[$k1])){
    						    $tmp_arr[$j]['ban'] = '';
    						}else{
    						    $tmp_arr[$j]['ban'] = $tmp3[$k1];
    						}
    						$tmp_arr[$j]['points_count'] = $tmp4[$k1];
    						$tmp_arr[$j]['charge_user'] = $v1;
    						$tmp_arr[$j]['assign_user'] = $data['userInfo']['id'];
    						$tmp_arr[$j]['assign_time'] = date("Y-m-d H:i:s");
    						if(isset($tmp2[$k1])) {
    							$tmp_arr[$j]['remark'] = $tmp2[$k1];
    						}
    						$tmp_arr[$j]['type'] = $this->input->get('assign_type');
    						$j++;
    				}
    			}else {
    				$add_data[$i]['type'] = $assign_type;
    				$add_data[$i]['order_id'] = $orderInfo['pid'];
    				$add_data[$i]['true_order_id'] = $order_id;
    				$add_data[$i]['houses_id'] = $v;
    				$add_data[$i]['area_id'] = 0;
    				$add_data[$i]['ban'] = '';
    				$add_data[$i]['points_count'] = $points_counts[$k];
    				$add_data[$i]['charge_user'] = $charge_users[$k];
    				$add_data[$i]['assign_user'] = $data['userInfo']['id'];
    				$add_data[$i]['assign_time'] = date("Y-m-d H:i:s");
    				if(isset($remark[$k])) {
    					$add_data[$i]['remark'] = $remark[$k];
    				}
    				$add_data[$i]['type'] = $this->input->get('assign_type');
    			}
    			$i++;
    		}
    		
    		if(count($tmp_arr) > 0) {
    		    $add_data = array_merge_recursive($add_data,$tmp_arr);
    		}
    		
    		
    		$group_data = [];
    		$group = array_unique(array_column($add_data, 'charge_user'));
    		//初始化组员数据
    		foreach ($group as $k => $v){
    		    $group_data[$k]['id'] = $v;
    		    $group_data[$k]['houses_ids'] = '';
    		}
    		
    		//匹配各个工程人员应分配的点位
    		$orderInfo = $tmp_moudle->get_one('*', ['id' => $order_id]);
    		$point_ids = array_unique(explode(',', $orderInfo['point_ids']));
    		$point_lists = $this->Mhouses_points->get_lists('*', ['in' => ['id' => $point_ids]]);
    		foreach ($group_data as $k => $v){
    		    foreach ($add_data as $key => $val){
    		        if($val['charge_user'] == $v['id']){
    		            foreach ($point_lists as $keys => $vals){
    		                //如果一个人负责了一个楼盘
    		                if($val['area_id'] == "" && $val['ban'] ==""){
    		                    if($vals['houses_id'] == $val['houses_id']){
    		                        $group_data[$k]['point_ids'][] = $vals['id'];
    		                    }
    		                }
    		                //多人负责
    		                if($vals['houses_id'] == $val['houses_id'] && $vals['area_id'] == $val['area_id'] && $vals['ban'] == $val['ban']){
    		                    $group_data[$k]['point_ids'][] = $vals['id'];
    		                }
    		            }
    		        }
    		    }
    		}
    		
    		//对点位进行排序
    		foreach ($group_data as $k => $v){
    		    //查询点位
    		    $tmp = $this->Mhouses_points->get_lists('id', ['in' => ['id' => $v['point_ids']] ], ['houses_id' => 'desc', 'area_id' => 'desc', 'ban' => 'desc']);
    		    if($tmp){
    		        $group_data[$k]['point_ids'] = array_column($tmp, 'id');
    		    }
    		}

    		foreach ($group_data as $k => $v){
    		    //工单数据
    		    $insert_data = [];
    		    $insert_data['order_id'] = $order_id;
    		    $insert_data['customer_id'] = $orderInfo['customer_id'];
    		    $insert_data['type'] = $orderInfo['assign_type'];
    		    $insert_data['assign_user'] = $orderInfo['group_id'];
    		    $insert_data['charge_user'] = $v['id'];
    		    $insert_data['create_time'] = date("Y-m-d H:i:s");
    		    $insert_data['total'] = count($v['point_ids']);
    		    //创建工程人员派单
    		    $_res = $this->Mhouses_work_order->create($insert_data);
    		    if($_res){
    		        //创建工单详情数据
    		        $insert_data_detail = [];
    		        foreach ($v['point_ids'] as $key => $val){
    		            $insert_data_detail[$key]['pid'] = $_res;
    		            $insert_data_detail[$key]['point_id'] = $val;
    		        }
    		        $res = $this->Mhouses_work_order_detail->create_batch($insert_data_detail);
    		    }else{
    		        $insert_data['point_ids'] = implode(',', $v['point_ids']);
    		        $this->write_log($v['id'], 1, json_encode($insert_data));	//创建工人人员派单失败
    		    }
    		}
    		
    		//提取所有的工程人员,并发送短信
    		$all = array_unique(array_column($add_data, 'charge_user'));
    		foreach ($all as $k => $v){
    		    $res_send = 1;//$this->sendMsg($v);
    		    if($res_send['code'] == 0) {
    		        $this->write_log($v, 2, "发送短信失败".date("Y-m-d H:i:s"));	//发送短信失败记录
    		    }
    		}
    		$update_data['assign_status'] = 2;
    		$res1 = $tmp_moudle->update_info($update_data,array("id" => $order_id));
    		if($res1) {
    		    echo "<script>alert('派单成功！');parent.location.reload();location.href='/housesassign/detail?order_id=".$order_id."&assign_type=".$assign_type."'</script>";
    		}
    		
    		
    		$this->error("保存失败");
    		
    	}
    	
    	//从换画订单获取点位信息
    	if($this->input->get('assign_type') == 3) {
    		$tmp_order = $this->Mhouses_changepicorders->get_one('id,point_ids', ['id'=>$data['order_id']]);
    		$where['in']['id'] = explode(',', $tmp_order['point_ids']);
    	}else {
    		$tmp_order = $this->Mhouses_orders->get_one('id,point_ids', ['id'=>$data['order_id']]);
    		$where['in']['id'] = explode(',', $tmp_order['point_ids']);
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
    	$orderInfo = $tmp_moudle->get_one('group_id', ['id' => $this->input->get('order_id')]);
    	$where = [];
    	//只查询改组的工程人员
    	$where['group_id'] = 4;	//工程人员角色
        $where['pid'] = $orderInfo['group_id'];
    	$tmp_users = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	$data['user_list'] = array_column($tmp_users, 'fullname', 'id');
    	$group = $this->Madmins->get_one('id,fullname', ['id' => $orderInfo['group_id']]);
    	$data['user_list'][$group['id']] = $group['fullname'];
    	if($this->input->get('assign_type') == 2) { //下画
    		$assign_list = $this->Mhouses_assign->get_lists('*', ['order_id' => $data['order_id'], 'type' => 1, 'is_del' => 0]);
    		
    		//获取上画人的信息
    		if(isset($assign_list)) {
    			
    			$result =   [];
    			$tmp_upload_arr = [];
    			foreach($assign_list as $k1=>$v1){
    				$result[$v1['houses_id']][] = $v1;
    			}
    			
    			foreach ($result as $k2 => $v2) {
    				$tmp_upload_arr[$k2]['charge_user'] = '';
    				$tmp_upload_arr[$k2]['houses_id'] = $v2[0]['houses_id'];
    				if(count($v2) > 1) {
    					foreach ($v2 as $k3 => $v3) {
    						$tmp_upload_arr[$k2]['charge_user'] = $tmp_upload_arr[$k2]['charge_user'].','.$v3['charge_user'];
    					}
    				}else {
    					$tmp_upload_arr[$k2]['charge_user'] = $v2[0]['charge_user'];
    				}
    				
    			}
    			
    			$data['assign_list'] = $tmp_upload_arr;
    		}
    		

    	}
    	
    	$data['assign_type'] = $this->input->get('assign_type');
    	
    	$this->load->view('housesassign/assign', $data);
    }
    
    /*
     * 详情
     */
    public function detail() {
    	$data = $this->data;
    
    	$where['is_del'] = 0;

    	if ($this->input->get('order_id')) $order_id =  $data['order_id'] =  $this->input->get('order_id');
    	
    	$assign_type = $data['assign_type'] = $this->input->get('assign_type') ? : 1;
    	
    	if($assign_type == 3) {	//换画
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {	//1上画，2下画
    		$tmp_moudle = $this->Mhouses_orders;
    	}

    	$order_list = $tmp_moudle->get_one("id,pid,point_ids",array("id" => $data['order_id']));
    
    	if(isset($order_list['point_ids'])) {
    		$point_ids_arr = explode(',', $order_list['point_ids']);
    		$where['in']['id'] = $point_ids_arr;
    	}
        if($order_list['pid']){
            $list = $this->Mhouses_assign->get_lists('id,houses_id, area_id, ban, points_count,status,charge_user,assign_user,assign_time,status,remark', ['order_id' => $order_list['pid'], 'type' => $assign_type]);  //点位分组
        }else{
            $list = $this->Mhouses_assign->get_lists('id,houses_id, area_id, ban, points_count,status,charge_user,assign_user,assign_time,status,remark', ['order_id' => $order_id, 'type' => $assign_type]);  //点位分组
        }
    	
    
    	if($list) {
    		$houses_ids = array_column($list, 'houses_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $houses_ids;
    		$hlist = $this->Mhouses->get_lists('id,name,province,city,area', $where);  //楼盘信息
    		
    		$area_ids = array_column($list, 'area_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $area_ids;
    		$alist = $this->Mhouses_area->get_lists('id,name', $where);  //楼盘信息
    
    		if($hlist) {
    			foreach ($list as $k => &$v) {
    				foreach ($hlist as $k1 => $v1) {
    					if($v['houses_id'] == $v1['id']) {
    						$v['ad_area'] = $v1['province']."-".$v1['city']."-".$v1['area'];
    						$v['houses_name'] = $v1['name'];
    					}
    				}
    				
    				foreach ($alist as $k2 => $v2) {
    					if($v['area_id'] == $v2['id']) {
    						$v['area_name'] = $v2['name'];
    					}
    				}
    			}
    		}
    
    	}
    
    	$data['list'] = $list;
    
    	$where = [];
    	$tmp_arr = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	$data['user_list'] = array_column($tmp_arr, 'fullname', 'id');
    	
    	//派单列表

    	//$data['assign_list'] = $this->Mhouses_assign->get_lists('id,houses_id,charge_user,assign_user,assign_time,status,remark', ['order_id' => $data['order_id'], 'type' => $assign_type, 'is_del' => 0]);  //点位分组
    	 
    	$this->load->view('housesassign/detail', $data);
    }
    
    /*
     * 详情
     */

    public function order_detail($id, $assign_type, $assign_status) {
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
        	$data['houses_id'] = $this->input->get('houses_id');
        }
        $data['groupInfo'] = false;
        if($data['info']['group_id']){
            $data['groupInfo'] = $this->Madmins->get_one('fullname', ['id' => $data['info']['group_id']]);
        }
        

        //客户名称
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];

        //业务员
        $data['info']['salesman'] = $this->Madmins->get_one('id, fullname as name, tel as phone_number', array('is_del' => 1, 'id' => $data['info']['sales_id']));  //业务员
        
        //投放点位
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))), [], 0,0,  $group_by = array('houses_id'));
        //广告画面
        $data['info']['adv_img'] = $data['info']['adv_img'] ? explode(',', $data['info']['adv_img']) : array();
        
        //制作公司
        $data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];
        $data['status_text'] = C('housesorder.houses_order_status.text');

        //获取对应订单状态的操作信息
        $operate_time = $this->Mhouses_status_operate_time->get_lists("value,operate_remark,operate_time",array("order_id" => $id , 'type' => 1));
        if($operate_time){
            $data['time'] = array_column($operate_time, "operate_time", "value");
            $data['operate_remark'] = array_column($operate_time, "operate_remark", "value");
        }

        $data['id'] = $id;
        $data['assign_type'] = $assign_type;
        $data['assign_status'] = $assign_status;
    	$this->load->view('housesassign/order_detail', $data);
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
    	
    	$assign_type = $this->input->get('assign_type') ? : '1';
    	
    	$data['assign_type'] = $assign_type;
    	
    	if(IS_POST){
    		$order_id = $this->input->post('order_id');
    		$houses_ids = $this->input->post('houses_id');
    		$points_counts = $this->input->post('points_count');
    		$charge_users = $this->input->post('charge_user');
    		$remark = $this->input->post('remark');
    		
    		$up_data = [];
    		$i = 0;
    		foreach ($charge_users as $k => $v) {
    			if($v) {
    				$tmp_charge = $this->Mhouses_assign->get_one('charge_user', ['order_id' => $order_id, 'houses_id'=>$houses_ids[$k], 'is_del' => 0]);  //点位分组
    				
    				if($tmp_charge != $v) {
    					$up_data['charge_user'] = $v;
    					$up_data['assign_user'] = $data['userInfo']['id'];
    					$up_data['assign_time'] = date("Y-m-d H:i:s");
    					$up_data['remark'] = $remark[$k];
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
    				$this->success("保存并通知成功","/housesassign/detail?order_id=".$order_id."&assign_type=".$assign_type);
    			}
    		}
    
    		$this->error("保存失败");
    
    	}
    	
    	if($assign_type == 1 || $assign_type == 2) {
    		$point_ids = $this->Mhouses_orders->get_one('id,point_ids', ['id' => $this->input->get('order_id')]);
    	}else {
    		$point_ids = $this->Mhouses_changepicorders->get_one('id, point_ids', ['id' => $this->input->get('order_id')]);
    	}
    	
    	$list = $this->Mhouses_assign->get_lists('id,houses_id,area_id,ban,charge_user,assign_user,assign_time,status, points_count as count', ['order_id' => $this->input->get('order_id'), 'type' => $assign_type]);
    	
    	if($list) {
    		$houses_ids = array_column($list, 'houses_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $houses_ids;
    		$hlist = $this->Mhouses->get_lists('id,name,province,city,area', $where);  //楼盘信息
    		
    		$area_ids = array_column($list, 'area_id');
    		$where = [];
    		$where['is_del'] = 0;
    		$where['in']['id'] = $area_ids;
    		$alist = $this->Mhouses_area->get_lists('id,name', $where);  //组团信息
    
    		if($hlist) {
    			foreach ($list as $k => &$v) {
    				foreach ($hlist as $k1 => $v1) {
    					if($v['houses_id'] == $v1['id']) {
    						$v['ad_area'] = $v1['province']."-".$v1['city']."-".$v1['area'];
    						$v['houses_name'] = $v1['name'];
    					}
    				}
    				
    				foreach ($alist as $k2 => $v2) {
    					if($v['area_id'] == $v2['id']) {
    						$v['area_name'] = $v2['name'];
    					}
    				}
    			}
    		}
    
    	}
    	
    	$data['list'] = $list;
    	$this->load->view('housesassign/edit', $data);
    }
    
    /**
     * 派单显示到组团
     */
    public function show_ban() {
    	$data = $this->data;
    	
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	
    	$page =  intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	$where_orders['is_del'] = $where['is_del'] = 0;
    	if ($this->input->get('order_id')) $where_orders['id'] = $data['order_id'] =  $this->input->get('order_id');
    	if($this->input->get('assign_type') == 3) {
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    		$tmp_moudle = $this->Mhouses_orders;
    	}
    	$charge_id_str = $this->input->get('charge_id_str');
    	if($charge_id_str) {
    		$data['charge_id_arr'] = explode(',', $charge_id_str);
    	}
    	
    	$remark_str = $this->input->get('remark_str');
    	if($remark_str) {
    		$data['remark_arr'] = explode(',', $remark_str);
    	}
    	
    	$orders_list = $tmp_moudle->get_one('id,point_ids', $where_orders);
    	if(isset($orders_list['point_ids'])) {
    		$point_ids_arr = explode(',', $orders_list['point_ids']);
    		$where['in']['id'] = $point_ids_arr;
    	}
    	
    	if ($this->input->get('houses_id')) $where['houses_id'] = $data['houses_id'] =  $this->input->get('houses_id');
    	$data['list'] = $this->Mhouses_points->get_lists('houses_id,area_id,ban, count(0) as count', $where, [] , 0, 0, ['houses_id','area_id', 'ban']);  //工程人员信息
    	$data_count = $this->Mhouses_points->count($where);
    	$data['page'] = $page;
    	$data['data_count'] = $data_count;
    	
    	if(count($data['list']) > 0) {
    		$houses_ids = array_column($data['list'], 'houses_id');
    		$area_ids = array_column($data['list'], 'area_id');
    	
    		$whereh['in']['id'] = $houses_ids;
    		$data['houses_list'] = $this->Mhouses->get_lists("id, name", $whereh);
    	
    		$wherea['in']['id'] = $area_ids;
    		$data['area_list'] = $this->Mhouses_area->get_lists("id, name", $wherea);
    	}
    	
    	//获取分页
    	$pageconfig['base_url'] = "/housesassign/show_ban";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    	$orderInfo = $tmp_moudle->get_one('group_id', ['id' => $this->input->get('order_id')]);
    	$where = [];
    	$where['group_id'] = 4;	//工程人员角色
    	if($orderInfo['group_id'] == 0){
    	    $where['group_id'] = C('group.gc');	//工程人员角色
    	}
    	
    	$where['pid'] = $orderInfo['group_id'];
    	$data['user_list'] = $this->Madmins->get_lists('id,name,fullname', $where);  //工程人员信息
    	if($orderInfo['group_id'] != 0){
        	$group = $this->Madmins->get_one('id,name,fullname', ['id' => $orderInfo['group_id']]);
        	$data['user_list'][] = $group;
    	}
    	
    	$this->load->view('housesassign/show_ban', $data);
    }
    
    /**
     * 显示点位列表详情
     */
    public function show_points() {
    	$data = $this->data;
    	 
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	 
    	$page =  intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	$where_orders['is_del'] = $where['is_del'] = 0;
    	if ($this->input->get('order_id')) $where_orders['id'] = $data['order_id'] =  $this->input->get('order_id');
    	
    	if($this->input->get('assign_type') == 3) {
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    		$tmp_moudle = $this->Mhouses_orders;
    	}
    	
    	$orders_list = $tmp_moudle->get_one('id,point_ids', $where_orders);
    	if(isset($orders_list['point_ids'])) {
    		$point_ids_arr = explode(',', $orders_list['point_ids']);
    		$where['in']['id'] = $point_ids_arr;
    	}
    	 
    	if ($this->input->get('houses_id')) $where['houses_id'] = $data['houses_id'] =  $this->input->get('houses_id');
    	if ($this->input->get('ban')) $where['ban'] = $data['ban'] =  $this->input->get('ban');
    	$data['list'] = $this->Mhouses_points->get_lists('id,code,houses_id,area_id,ban,unit,floor,addr,type_id', $where,[],$size,($page-1)*$size);  //工程人员信息
    	$data_count = $this->Mhouses_points->count($where);
    	$data['page'] = $page;
    	$data['data_count'] = $data_count;
    	 
    	if(count($data['list']) > 0) {
    		$houses_ids = array_column($data['list'], 'houses_id');
    		$area_ids = array_column($data['list'], 'area_id');
    
    		$whereh['in']['id'] = $houses_ids;
    		$data['houses_list'] = $this->Mhouses->get_lists("id, name", $whereh);
    
    		$wherea['in']['id'] = $area_ids;
    		$data['area_list'] = $this->Mhouses_area->get_lists("id, name", $wherea);
    	}
    	 
    	//获取分页
    	$pageconfig['base_url'] = "/Housesassign/show_points";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    	 
    	$this->load->view('housesassign/show_points', $data);
    }
    
    
    /**
     * 短信通知
     */
    public function sendMsg($uid) {
    	//根据预定订单获取客户电话
    	$info = $this->Madmins->get_one('tel, fullname', ['id' => $uid]);
    	
    	if(!$info) return ['code' => 0, 'msg' => '工程人员不存在'];
    	if(empty($info['tel'])){
    		return ['code' => 0, 'msg' => '电话不能为空！'];
    	}
    	if(!preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/', $info['tel'])){
    		return ['code' => 0, 'msg' => '手机号格式不正确！'];
    	}
    	//用户姓名
        $name = $info['fullname'];
        // 配置短信信息
        $app = C('sms.app');
        $parems = [
            'PhoneNumbers' => $info['tel'],
            'SignName' => C('sms.sign.lkcb'),
            'TemplateCode' => C('sms.template.paidan'),
            'TemplateParam' => array(
                'name' => $name
            )
        ];
        //发送短信
        set_time_limit(0);
        $sms = new SendSms($app, $parems);
        try {
            $info = (array) $sms->send();
            if(isset($info['Code'])) {
                if(strtolower($info['Code']) == 'ok'){
                    return ['code' => 1, 'msg' => '发送成功'];
                }else{
                    return ['code' => 0, 'msg' => '错误码：'.$info['Code']];
                }
            }
            return ['code' => 0, 'msg' => '请稍后重试'];
        } catch (Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
        
    }

}


<?php 
/**
* 订单管理控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesorders extends MY_Controller{

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
        $this->data['active'] = 'houses_orders_list';

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

        $data['list'] = $this->Mhouses_orders->get_order_lists($where, ['A.id' => 'desc'], ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
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
        
        $this->load->view("housesorders/index", $data);
    }


    /**
     * 修改订单总价
     */
    public function edit_price() {
        $id = $this->input->post('id');
        $total_price = $this->input->post('total_price');
        $order = $this->Morders->get_one('*', array('id' => $id));
        if ($order['order_status'] < C('housesorder.houses_order_status.code.uninstall')) {
            $result = $this->Morders->update_info(array('total_price' => $total_price), array('id' => $id));
            if ($result || $result === 0) {
                $this->return_json(array('flag' => true, 'total_price' => sprintf("%.2f", $total_price), 'msg' => '修改价格成功！'));
            } else {
                $this->return_json(array('flag' => false, 'msg' => '修改价格失败！'));
            }
        } else {
            $this->return_json(array('flag' => false, 'msg' => '已下画订单不能修改价格！'));
        }
    }


    /**
     * 选择订单类型
     */
    public function order_type() {
        $data = $this->data;
        $this->load->view('housesorders/order_type', $data);
    }



    /**
     * 根据客户id查询该客户名下的所有项目option
     */
    public function get_project_lists() {
        $customer_id = $this->input->post('customer_id');
        if (!$customer_id) {
            $this->return_json(array('flag' => false, 'msg' => '非法参数！'));
        }
        $project_lists =  $this->Mcustomer_project->get_lists('id, project_name', array('customer_id' => $customer_id, 'is_del' => 0));
        if ($project_lists) {
            $option = '<option value="">请选择项目</option>';
            foreach ($project_lists as $key => $value) {
                $option .= '<option value="'.$value['id'].'">'.$value['project_name'].'</option>';
            }
        } else {
            $option = '<option value="">该客户没有项目</option>';
        }
        $this->return_json(array('flag' => true, 'option' => $option));
    }



    /**
     * 新建订单
     */
    public function add($order_type, $put_trade=0) {
        $data = $this->data;
        
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['order_code'] = date('YmdHis').$post_data['customer_id']; //订单编号：年月日时分秒+客户id
            
            if (isset($post_data['make_complete_time'])) {
                $post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
            }

            $post_data['creator'] =  $data['userInfo']['id'];
            $post_data['create_time'] =  date('Y-m-d H:i:s');
            unset($post_data['houses_id'], $post_data['area_id'],$post_data['ban'],$post_data['unit'],$post_data['floor'],$post_data['addr'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            $id = $this->Mhouses_orders->create($post_data);
            if ($id) {
                    //如果选择的点位包含预定点位，则把对应的预定订单释放掉
                    $where['is_del'] = 0;
                    $where['lock_customer_id'] = $post_data['customer_id'];
                    $where['order_type'] = $order_type;
                    $where['order_status!='] = C('scheduledorder.order_status.code.done_release');
                    $info = $this->Mhouses_scheduled_orders->get_one("*", $where);
                    if ($info && count(array_intersect(explode(',', $post_data['point_ids']), explode(',', $info['point_ids']))) > 0) {
                        //释放该预定订单的所有点位
                        //$update_data['lock_customer_id'] = $update_data['lock_start_time'] = $update_data['lock_end_time'] = $update_data['expire_time'] = '';
                    	$update_data['lock_customer_id'] = 0;
                    	$update_data['is_lock'] = 0;
                        $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));

                        //更新该订单的状态为“已释放”
                        $this->Mhouses_scheduled_orders->update_info(array('order_status' => C('scheduledorder.order_status.code.done_release')), array('id' => $info['id']));
                    }


                    //下单成功把选择的点位置为占用状态(只针对公交灯箱和户外高杆)
                    $update_data['order_id'] = $id;
                    $update_data['customer_id'] = $post_data['customer_id'];
//                     $update_data['lock_start_time'] = '';
//                     $update_data['lock_end_time'] = '';
//                     $update_data['expire_time'] = '';
                    $update_data['point_status'] = 3;
                    $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

//                 } 

                $this->write_log($data['userInfo']['id'], 1, "社区资源管理新增".$data['order_type_text'][$post_data['order_type']]."订单,订单id【".$id."】");
                $this->success("添加成功！","/housesorders");
            } else {
                $this->success("添加失败！","/housesorders");
            }
        } else {
            $data['order_type'] = $order_type;
            $tmpPoints = $this->Mhouses_points->get_lists("id, houses_id, area_id", ['type_id' => $order_type, 'is_del' => 0]);
            
         	if(count($tmpPoints) > 0) {
         		$housesid = array_column($tmpPoints, 'houses_id');
         		$whereh['in']['id'] = $housesid;
         		$whereh['is_del'] = 0;
         		
         		$houses_list = $this->Mhouses->get_lists("id, name, put_trade", $whereh);
         		
         		//禁投放行业 begin
         		if(count($houses_list) > 0) {
         			foreach ($houses_list as $k => $v) {
         				if(in_array($put_trade, explode(',', $v['put_trade']))) {
         					unset($houses_list[$k]);
         				}
         			}
         		}
         		
         		$data['put_trade'] = $put_trade;
         		$data['housesList'] = $houses_list;
         		//end
         	}
         	
         	//获取楼栋单元楼层列表
         	$data['BUFL'] = $this->get_ban_unit_floor_list();
         	$data['status_text'] = C('housesorder.houses_order_status.text');
            $this->load->view("housesorders/add", $data);
        }
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

    /* 
     * 编辑订单
     */
    public function edit($id) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();

            if (isset($post_data['make_complete_time'])) {
                $post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
            }

            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            if ($post_data['order_type'] == 1 || $post_data['order_type'] == 2) {
                //先把之前所有已选择的点位的状态置为空闲，再把重新选择的点位状态置为占用（只针对公交灯箱和户外高杆）
                $this->Mhouses_points->update_info(array('customer_id' => 0, 'order_id' => 0, 'point_status' => 1), array('in' => array('id' => explode(',', $post_data['point_ids_old']))));
                
                $update_data['order_id'] = $id;
                $update_data['customer_id'] = $post_data['customer_id'];
//                 $update_data['lock_start_time'] = '';
//                 $update_data['lock_end_time'] = '';
//                 $update_data['expire_time'] = '';
                $update_data['point_status'] = 3;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

            }

            unset($post_data['houses_id'], $post_data['area_id'],$post_data['ban'],$post_data['unit'],$post_data['floor'],$post_data['addr'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            unset($post_data['point_ids_old']);
            $result = $this->Mhouses_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "社区编辑".$data['order_type_text'][$post_data['order_type']]."订单,订单id【".$id."】");
                $this->success("修改成功！","/housesorders");
            } else {
                $this->success("修改失败！请重试！","/housesorders");
            }
        } else {
            $data['info'] = $this->Mhouses_orders->get_one("*", array('id' => $id));
            
            $data['order_type'] = $data['info']['order_type'];
            $data['put_trade'] = $data['info']['put_trade'];
            
            $tmpPoints = $this->Mhouses_points->get_lists("id, houses_id, area_id", ['type_id' => $data['order_type'], 'is_del' => 0]);
            
            if(count($tmpPoints) > 0) {
            	$housesid = array_column($tmpPoints, 'houses_id');
            	$whereh['in']['id'] = $housesid;
            	$data['housesList'] = $this->Mhouses->get_lists("id, name", $whereh);
            
            }
            

            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);
            

           
            
            $this->load->view("housesorders/add", $data);
            
        }
    }



    /* 
     * 投放中的订单编辑点位（只有投放中的灯箱才能编辑，高杆和LED不需要）
     */
    public function edit_points($id) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $arr1 = explode(',', $post_data['point_ids_old']);
            $arr2 = explode(',', $post_data['point_ids']);

            if ($arr1 == $arr2) {
                $this->error('您没有修改任何点位哦！');
                exit;
            }

            $remove_points = array_diff($arr1, array_intersect($arr1, $arr2)); //移除的点位
            $add_points = array_diff($arr2, array_intersect($arr1, $arr2));    //新增的点位

            $change_points = $this->Mhouses_change_points_record->get_one_change($id);
            if ($change_points) { //修改换点记录表
                $up_data['remove_points'] = $remove_points ? implode(',', $remove_points): $change_points['remove_points'];
                $up_data['add_points'] = $add_points ? implode(',', $add_points) : $change_points['add_points'];
                $this->Mhouses_change_points_record->update_info($up_data, array('id' => $change_points['id']));
            } else {
                //写入换点记录表
                $change_id = $this->Mhouses_change_points_record->create(array('order_id' => $id, 'remove_points' => implode(',', $remove_points), 'add_points' => implode(',', $add_points), 'operate_time' => date('Y-m-d H:i:s')));
                if ($change_id) {
                    //备份之前的订单信息
                    $order_old_info = $this->Mhouses_orders->get_one('*', array('id' => $id));
                    foreach ($order_old_info as $key => $value) {
                        $log_data[$key] = $value;
                    }
                    $log_data['change_id'] = $change_id;
                    $log_data['log_time'] = date('Y-m-d:H:i:s');
                    unset($log_data['id'], $log_data['is_del'], $log_data['create_user'], $log_data['create_time'], $log_data['update_user'], $log_data['update_time']);
                    $this->Mhouses_orders_log->create($log_data);

                    //备份之前的验收图片
                    $inspect_images_old = $this->Mhouses_order_inspect_images->get_lists('*', array('order_id' => $id, 'type' => 1));
                    foreach ($inspect_images_old as $key => $value) {
                        foreach ($value as $k => $v) {
                            $img_log_data[$k] = $v;
                        }
                        $img_log_data['change_id'] = $change_id;
                        $img_log_data['log_time'] = date('Y-m-d:H:i:s');
                        unset($img_log_data['id'], $img_log_data['type'], $img_log_data['create_user'], $img_log_data['create_time'], $img_log_data['update_user'], $img_log_data['update_time']);
                        $this->Mhouses_order_inspect_images_log->create($img_log_data);
                    }

                }
            }


            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            //先把之前所有已选择的点位的状态置为空闲，再把重新选择的点位状态置为占用
            $this->Mhouses_points->update_info(array('customer_id' => 0, 'order_id' => 0, 'point_status' => 1), array('in' => array('id' => explode(',', $post_data['point_ids_old']))));

            $update_data['order_id'] = $id; 
            $update_data['customer_id'] = $post_data['customer_id'];
//             $update_data['lock_start_time'] = '';
//             $update_data['lock_end_time'] = '';
//             $update_data['expire_time'] = '';
            $update_data['point_status'] = 3;
            $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

            //如果该订单下有换画订单，则同时更新对应的换画点位，并删除对应的验收图片
            $arr = array_merge($remove_points, $add_points);
            if ($arr) {

                //最近一次换画订单
                $change_order = $this->Mhouses_change_pic_orders->get_lists('*', array('order_code' => $post_data['order_code']), array('create_time' => 'DESC'));
                if ($change_order) {
                    $change_order = $change_order[0];
                    $arr3 = explode(',', $change_order['point_ids']);
                    $arr4 = array_merge(array_diff($arr3, array_intersect($arr3, $arr)), array_diff($arr, array_intersect($arr, $arr3)));
                    $point_str = $arr4 ? implode(',', $arr4) : '';
                    $this->Mhouses_change_pic_orders->update_info(array('point_ids' => $point_str), array('id' => $change_order['id']));

                    //删除该订单下最近一次换画订单更换点位的验收图片
                    $this->Mhouses_order_inspect_images->delete(array('order_id' => $change_order['id'], 'in' => array('point_id' => $arr), 'type' => 2));
                }
                //删除该订单更换点位的验收图片
                $this->Mhouses_order_inspect_images->delete(array('order_id' => $id, 'in' => array('point_id' => $arr), 'type' => 1));
            }

            //更新订单信息
            unset($post_data['houses_id'],$post_data['area_id'], $post_data['point_ids_old'], $post_data['make_num']);
            $result = $this->Mhouses_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "社区投放中订单修改点位，订单id【".$id."】");
                $this->success("修改成功！","/housesorders");
            } else {
                $this->error("修改失败！请重试！","/housesorders");
            }
        } else {
            $data['info'] = $this->Mhouses_orders->get_one("*", array('id' => $id));

            $data['order_type'] = $data['info']['order_type'];
			
            $data['order_type'] = $data['info']['order_type'];
            
            $tmpPoints = $this->Mhouses_points->get_lists("id, houses_id, area_id", ['type_id' => $data['order_type'], 'is_del' => 0]);
            
            if(count($tmpPoints) > 0) {
            	$housesid = array_column($tmpPoints, 'houses_id');
            	$whereh['in']['id'] = $housesid;
            	$data['housesList'] = $this->Mhouses->get_lists("id, name", $whereh);
            
            }
            
            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);
			
            //客户
            $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];
            //业务员
            $data['info']['sales'] = $this->Msalesman->get_one('name, phone_number', array('id' => $data['info']['sales_id']));
            //制作公司
            $data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];


            $this->load->view('housesorders/edit_points', $data);
        }
    }



    /**
     * 生成联系单
     */
    public function contact_list($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_orders->get_one("*", array('id' => $id));

        //甲方负责人
        $admin = $this->Madmins->get_one('fullname, tel', array('id' => $data['info']['creator']));
        $data['info']['A_contact_man'] = $admin['fullname'];
        $data['info']['A_contact_mobile'] = $admin['tel'];

        //客户名称
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];
        
        $order_type = $data['info']['order_type'];

        if ($order_type == 1 || $order_type == 2) {
            //制作单位
            $make_company = $this->Mmake_company->get_one('*', array('id' => $data['info']['make_company_id']));
            $data['info']['make_company'] = $make_company['company_name'];
            $data['info']['seal_img'] = $make_company['seal_img'];
            $data['info']['contact_man'] = $make_company['contact_man']; //乙方联系人
            $data['info']['contact_mobile'] = $make_company['contact_mobile']; //乙方电话
            
            //制作数量
            $make_info = $this->get_make_info($data['info']);
            $data['make_num'] = $make_info['make_num'];
            $data['total_counts'] = $make_info['total_counts'];
            $data['high_count'] = $make_info['high_count'];
            $data['total_num'] = $make_info['total_num'];

            if ($order_type == '1') {    //冷光灯箱
                $this->load->view('housesorders/contact_list/light', $data);
            } elseif ($order_type == '2') {   //广告机
            	$this->load->view('housesorders/contact_list/light', $data);
            }
        } 

    }


    /**
     * 生成确认函
     */
    public function confirmation($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_orders->get_one("*", array('id' => $id));

        $images = $this->Mhouses_order_inspect_images->get_lists("*", array('order_id' => $id, 'type' => 1));
        if (!$images) {
            $this->success("请先上传验收图片！");
        }

        //甲方-委托方（客户名称）
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['customer_id']))['name'];
            
        //上画完成时间
        $data['complete_date'] = date('Y年m月d日', strtotime($data['info']['draw_finish_time']));

        /********验收图片**********/
        $data['inspect_images'] = $this->Mhouses_order_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.type' => 1));
        
        //获取点位列表
        $where['in'] = array('A.id' => explode(',', $data['info']['point_ids']));
        $data['points'] = $this->Mhouses_points->get_points_lists($where);
		$data['done_inspect_images'] = array_column($data['inspect_images'], 'front_img', 'point_id');
        

        $this->load->view('housesorders/confirmation/light', $data);
    }


    /**
     *  换点之前验收函
     */
    public function last_confirmation($id) {
        $data = $this->data;
        $data['info'] = $this->Morders_log->get_one("*", array('change_id' => $id));

        //甲方-委托方（客户名称）
        $data['info']['customer_name'] = $this->Mcustomers->get_one('customer_name', array('id' => $data['info']['customer_id']))['customer_name'];
            
        //上画完成时间
        $data['complete_date'] = date('Y年m月d日', strtotime($data['info']['release_start_time']));

        /********验收图片**********/
        $data['inspect_images'] = $this->Morder_inspect_images_log->get_inspect_img(array('A.change_id' => $id));

        //获取点位列表
        $where['in'] = array('B.id' => explode(',', $data['info']['point_ids']));
        $data['points'] = $this->Mpoints->get_confirm_points($where, array('A.sort' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));

        if ($data['info']['order_type'] == '1') {    //灯箱
            //统计大灯箱、中灯箱、小灯箱套数
            $make = $this->get_log_make_info($data['info']);
            $make_info = multi_arr_sort($make['make_num'], 'spec_id');
            $data['number'] = array();
            foreach($make_info as $k=>$v){
                if(!isset($data['number'][$v['spec_name']])){
                    $data['number'][$v['spec_name']] = $v['counts'];
                }else{
                    $data['number'][$v['spec_name']] += $v['counts'];
                }
            }

            //广告总套数
            $data['total_num'] = $make['total_counts'];
            $data['volume'] = array_column($data['points'], 'counts' ,'media_id');

            $this->load->view('orders/confirmation/light', $data);
        }
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
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))), [], 0,0,  $group_by = array('houses_id'));

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

        $data['info']['assign_list'] = $this->Mhouses_assign->get_join_lists(['A.order_id' => $id, 'A.type' => 1]);
        
        //下画派单列表
        $data['info']['assign_down_list'] = $this->Mhouses_assign->get_join_lists(['A.order_id' => $id, 'A.type' => 2]);

        
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
    
    
    /**
     * 点位表按行政区域、楼盘分组详情
     */
    public function points_detail($order_id, $houses_id) {
    	$data = $this->data;
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page =  intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	
    	$order_list = $this->Mhouses_orders->get_one('point_ids', ['id'=>$order_id]);
    	
    	$where['in']['A.id'] = explode(',', $order_list['point_ids']);
    	$where['A.houses_id'] = $houses_id;
    	$data['list'] = $this->Mhouses_points->get_points_lists($where, [],$size,($page-1)*$size);
    	$data_count = $this->Mhouses_points->get_count($where);
    	$data['page'] = $page;
    	$data['data_count'] = $data_count;
    	
    	//获取分页
    	$pageconfig['base_url'] = "/houses";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    	
    	$this->load->view('housesorders/points_detail', $data);
    }
    
    
    /**
     * 删除订单
     */
    function del_order() {
    	$id = $this->input->post('id');
    	if(!empty($id)) {
    		$where['id'] = $id;
    	}
    	if($where) {
    		//$res = $this->Morders->delete($where);
    		$res = $this->Mhouses_orders->update_info(array("is_del"=>1), $where);
    	}
    	
    	if(!empty($res)) {
    		$res1 = $this->Mhouses_points->update_info(array("customer_id"=>0, "order_id"=>0, "status"=>1), array("order_id"=>$id));
    		if($res1) {
    			$this->return_json(['code' => 0, 'msg' => '删除成功！']);
    		}
    		
    		$this->return_json(['code' => 0, 'msg' => '删除订单成功，但由于网络中断点位没有释放！']);
    		
    	}
    	$this->return_json(['code' => 0, 'msg' => '删除失败']);
    }

    /*
     * 更新订单状态
     * 1034487709@qq.com
     */
    public  function ajax_update_status(){
        if ($this->data['pur_code'] == 1) {
            $this->return_failed('您没有更新订单状态的权限！');
        }

        if($this->input->is_ajax_request()){
            $id = $this->input->post("id");
            $status = $this->input->post("status");
            $operate_remark = $this->input->post("remark");
            $order_code = $this->input->post("order_code");
            
            $count = $this->Mhouses_status_operate_time->count(array("order_id" => $id, "value" => $status, "type" => 1));
            if($count){
                $res = $this->Mhouses_status_operate_time->update_info(
                    array('operate_remark' => $operate_remark,"operate_time"=> date("Y-m-d H:i:s")),
                    array('order_id' => $id, "value" => $status, "type" => 1)
                );
                //删除该状态以下的所有状态
                $where['order_id'] = $id;
                $where['value>'] = $status;
                $where['type'] = 1;
                $res = $this->Mhouses_status_operate_time->delete($where);
            }else{
                $post_data['order_id'] = $id;
                $post_data['value'] = $status;
                $post_data['operate_time'] = date("Y-m-d H:i:s");
                $post_data['operate_remark'] = $operate_remark;
                $post_data['type'] = 1;
                $this->Mhouses_status_operate_time->create($post_data);
            }
            
            $data = $this->data;
            $status_text = C('housesorder.houses_order_status.text');

            $this->write_log($data['userInfo']['id'],2,"  更新订单:".$order_code."状态：".$status_text[$status]);
            
            //向工程主管广播

            if($status == 3) {
            	$msg = "你有新的订单需要派单,请到派单列表页面！";
            	$this->send(['group_id' => 5, 'message' => $msg]);
            }

            $update_order['order_status'] = $status;

            if($status == 7) {
            	 $update_order['assign_type'] = 2;
            	 $update_order['assign_status'] = 1;
            }
            //同时更新对应的订单
            $result = $this->Mhouses_orders->update_info($update_order,array("id"=>$id));
            
            if($status == 8){
                if($result){
                    //如果订单已经下画则释放所有点位
                    //$update_data['order_id'] = 0;
                    //$update_data['customer_id'] = 0;
                    $update_data['point_status'] = 1;

                    $this->Mhouses_points->update_info($update_data,array("order_id"=>$id));

                    //更新该订单下所有换画订单的状态为已下画
                    $order_code = $this->Mhouses_orders->get_one('order_code', array('id' => $id))['order_code'];
                    $change_count = $this->Mhouses_change_pic_orders->count(array('order_code' => $order_code));
                    if ($change_count) {
                        $this->Mhouses_change_pic_orders->update_info(array('order_status' => 9), array('order_code' => $order_code));
                    }
                }
            }

             $this->return_success();
        }
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
    public function  upload_adv_img($order_id){
        $data = $this->data;

        if(IS_POST){
            $cover_img = $this->input->post("cover_img");
            $adv_img = implode(",",$cover_img);
            $res = $this->Mhouses_orders->update_info(array("adv_img"=>$adv_img), array("id"=>$order_id));
            if ($res) {
                $this->write_log($data['userInfo']['id'], 2, "社区资源上传订单广告画面，订单id【".$order_id."】");
                $this->success("保存广告画面成功！", "/housesorders/detail/".$order_id);
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
            $this->load->view('housesorders/upload_adv_img', $data);
        }

    }
    
    /**
     * 录入制作信息
     */
    public function insert_make_info($order_id) {
    	$data = $this->data;
    	
    	if(IS_POST) {
    		$post_data = $this->input->post();
    		
    		if (isset($post_data['make_complete_time'])) {
    			$post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
    		}
    		
    		unset($post_data['hour'],$post_data['minute'],$post_data['second']);
    		$post_data['update_user'] = $data['userInfo']['id'];
    		$post_data['update_time'] = date('Y-m-d H:i:s');
    		$post_data['order_status'] = 2;
    		$res = $this->Mhouses_orders->update_info($post_data, ['id' => $order_id]);
    		if($res) {
    			$this->success("录入制作信息成功！", "/housesorders/insert_make_info/".$order_id);
    		}else {
    			$this->error("操作失败！请重试！");
    		}
    		
    	}
    	
    	
    	$data['info'] = $this->Mhouses_orders->get_one('*', ['id' => $order_id]);
    	$this->load->view('housesorders/insert_make_info', $data);
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
    	
    	
    	$assign_id = $this->input->get('assign_id');
    	$order_id = $this->input->get('order_id');
    	$houses_id = $this->input->get('houses_id');
    	$ban = $this->input->get('ban');
    	$assign_type = $this->input->get('assign_type');
    	
    	if($assign_type == 3) {
    		$tmp_moudle = $this->Mhouses_changepicorders;
    	}else {
    		$tmp_moudle = $this->Mhouses_orders;
    	}
    	$order = $tmp_moudle->get_one("*",array("id" => $order_id));
    	
    	if(isset($order['point_ids'])) {
    		$point_ids_arr = explode(',', $order['point_ids']);
    		$where_point['in']['A.id'] = $point_ids_arr;
    	}
    	$where_point['A.houses_id'] = $houses_id;
    	if($ban) {
    		$where_point['A.ban'] = $ban;
    	}
    	$where_point['A.is_del'] = 0;
    	
    	//获取该订单下面的所有楼盘
    	$points = $this->Mhouses_points->get_points_lists($where_point,[],$size,($page-1)*$size);
    	$data_count = $this->Mhouses_points->count(['order_id' => $order_id, 'houses_id' => $houses_id]);
    	$data['page'] = $page;
    	$data['data_count'] = $data_count;
    	
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
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    
    	$this->load->view('housesorders/check_adv_img', $data);
    }
    
    /*
     * 确认上画和下画
     */
    public function confirm_upload() {
    	$assign_id = $this->input->post("assign_id");
    	$order_id = $this->input->post("order_id");
    	$confirm_remark = $this->input->post("confirm_remark");
    	$mark = $this->input->post("mark");	//mark=1通过，mark=2不通过
    	$assign_type = $this->input->post("assign_type");	//assign_type=1上画派单，assign_type=2下画派单，assign_type=3换画派单
    	$houses_id = $this->input->post("houses_id");
    	$ban = $this->input->post("ban");

    	if($mark == 1) {
    		if($assign_type == 2) {
    			$update_data['status'] = 8;
    		}else {
    			$update_data['status'] = 5;
    		}
    	}else {
    		if($assign_type == 2) {
    			$update_data['status'] = 9;
    		}else {
    			$update_data['status'] = 6;
    		}
    	}
    	$update_data['confirm_remark'] = $confirm_remark;
    	

    	$res = $this->Mhouses_assign->update_info($update_data, ['id' => $assign_id]);

    	
    	//上画派单
    	if($res && $assign_type == 1) {
    		
    		$where['order_id'] = $order_id;
    		$where['type'] = $assign_type;
    		$where['status<>'] = 5;
    		$count = $this->Mhouses_assign->count($where);
    		 
    		if($count == 0) {
    			$update_data = [];
    			$update_data['order_status'] = 6;
    			$res1 = $this->Mhouses_orders->update_info($update_data, ['id' => $order_id]);
    			if($res1) {
    				$this->return_json(['code' => 1, 'msg' => "该订单的所有派单审核通过,订单状态更新为上画完成！"]);
    			}
    		}
    		
    		$this->return_json(['code' => 1, 'msg' => "操作成功！"]);
    	}
    	
    	//下画派单
    	if($res && $assign_type == 2) {
    		
    		$points_ids = $this->Mhouses_orders->get_one('*', ['id' => $order_id]);
    		
    		//将占用的点位释放以及将点位的状态改为1（有空闲）
    		if($mark == 1) {
    			$points_ids_arr = explode(',', $points_ids['point_ids']);
    			
    			$where_p['in']['id'] = $points_ids_arr;
    			if($houses_id) $where_p['houses_id'] = $houses_id;
    			if($ban) $where_p['ban'] = $ban;
    			$update_point['point_status'] = 1;
    			$update_point['decr']['ad_use_num'] = 1;//释放点位占用数
    			
    			$this->Mhouses_points->update_info($update_point, $where_p);
    		}
    		
    		$where['order_id'] = $order_id;
    		$where['type'] = $assign_type;
    		$where['status<>'] = 8;
    		$count = $this->Mhouses_assign->count($where);
    		 
    		if($count == 0) {
    			$update_data = [];
    			$update_data['order_status'] = 8;
    			$res1 = $this->Mhouses_orders->update_info($update_data, ['id' => $order_id]);
    			if($res1) {
    				$this->return_json(['code' => 1, 'msg' => "该订单的所有下画派单审核通过,订单状态更新为已下画！"]);
    			}
    		}
    	
    		$this->return_json(['code' => 1, 'msg' => "操作成功！"]);
    	}
    	
    	//换画派单
    	if($res && $assign_type == 3) {
    		$where['order_id'] = $order_id;
    		$where['status<>'] = 5;
    		$count = $this->Mhouses_assign_down->count($where);
    		 
    		if($count == 0) {
    			$update_data = [];
    			$update_data['order_status'] = 6;
    			$res1 = $this->Mhouses_changepicorders->update_info($update_data, ['id' => $order_id]);
    			if($res1) {
    				$this->return_json(['code' => 1, 'msg' => "该订单的所有换画派单审核通过,订单状态更新为已下画！"]);
    			}
    		}
    		 
    		$this->return_json(['code' => 1, 'msg' => "操作成功！"]);
    	}
    	
    	
    	$this->return_json(['code' => 0, 'msg' => "操作失败，请联系管理员！"]);
    	
    }


    /*
     * 订单续期
     * 103487709@qq.com
     */
    public function extend_time(){
        if ($this->data['pur_code'] == 1) {
            $this->return_failed('您没有订单续期的权限！');
        }

        if($this->input->is_ajax_request()){
            $id = intval($this->input->post("id"));
            $order_code = $this->input->post("order_code");
            $release_end_time = $this->input->post("release_end_time");
            $res = $this->Mhouses_orders->update_info(array("release_end_time"=>$release_end_time), array("id"=>$id));
            if($res){
                $data = $this->data;
                $this->write_log($data['userInfo']['id'],2,"社区订单续期，订单编号为".$order_code);
                $this->return_success();
            }else{
                $this->return_failed();
            }
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


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
        	'Model_status_operate_time' => 'Mstatus_operate_time',
        		
        		
//              'Model_medias' => 'Mmedias',
//              'Model_customers' => 'Mcustomers',
//              'Model_customer_project' => 'Mcustomer_project',
//              'Model_admins' => 'Madmins',
//              'Model_points' => 'Mpoints',
//              'Model_make_company' => 'Mmake_company',
//              'Model_status_operate_time' => 'Mstatus_operate_time',
//              'Model_inspect_images' => 'Minspect_images',
//              'Model_salesman' => 'Msalesman',
//              'Model_order_inspect_images' => 'Morder_inspect_images',
//              'Model_change_pic_orders' => 'Mchange_pic_orders',
//              'Model_points_make_num' => 'Mpoints_make_num',
//              'Model_change_points_record' => 'Mchange_points_record',
//              'Model_orders_log' => 'Morders_log',
//              'Model_order_inspect_images_log' => 'Morder_inspect_images_log',
//              'Model_points_make_num_log' => 'Mpoints_make_num_log',
//              'Model_scheduled_orders' => 'Mscheduled_orders'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_orders_list';

//         $this->data['medias'] = $this->Mmedias->get_lists("id, code, name", array('is_del' => 0), array('sort' => 'asc'));  //媒体
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
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
        if ($this->input->get('order_status')) $where['A.order_status'] = $this->input->get('order_status');

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
        $data['order_status'] = $this->input->get('order_status');

        //$data['project'] = array_column($this->Mcustomer_project->get_lists('id, project_name', array('is_del' => 0)), 'project_name', 'id');

        $data['list'] = $this->Mhouses_orders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_orders->get_order_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/orders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $data['status_text'] = C('order.order_status.text');
        
        $this->load->view("housesorders/index", $data);
    }


    /**
     * 修改订单总价
     */
    public function edit_price() {
        $id = $this->input->post('id');
        $total_price = $this->input->post('total_price');
        $order = $this->Morders->get_one('*', array('id' => $id));
        if ($order['order_status'] < C('order.order_status.code.uninstall')) {
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
    public function add($order_type) {
        $data = $this->data;
        
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['order_code'] = date('YmdHis').$post_data['customer_id']; //订单编号：年月日时分秒+客户id
            
            if (isset($post_data['make_complete_time'])) {
                $post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
            }

            $post_data['creator'] =  $data['userInfo']['id'];
            $post_data['create_time'] =  date('Y-m-d H:i:s');
            unset($post_data['houses_id'], $post_data['area_id'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            $id = $this->Mhouses_orders->create($post_data);
            if ($id) {
//                 if ($post_data['order_type'] == 1 || $post_data['order_type'] == 2) {
//                     //如果选择的点位包含预定点位，则把对应的预定订单释放掉
//                     $where['is_del'] = 0;
//                     $where['customer_id'] = $post_data['customer_id'];
//                     $where['order_type'] = $order_type;
//                     $where['order_status!='] = C('scheduledorder.order_status.code.done_release');
//                     $info = $this->Mscheduled_orders->get_one("*", $where);
//                     if ($info && count(array_intersect(explode(',', $post_data['point_ids']), explode(',', $info['point_ids']))) > 0) {
//                         //释放该预定订单的所有点位
//                         $update_data['lock_customer_id'] = $update_data['lock_start_time'] = $update_data['lock_end_time'] = $update_data['expire_time'] = '';
//                         $update_data['is_lock'] = 0;
//                         $this->Mpoints->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));

//                         //更新该订单的状态为“已释放”
//                         $this->Mscheduled_orders->update_info(array('order_status' => C('scheduledorder.order_status.code.done_release')), array('id' => $info['id']));
//                     }


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
         		$data['housesList'] = $this->Mhouses->get_lists("id, name", $whereh);
         		
         	}

            $this->load->view("housesorders/add", $data);
        }
    }
    
    /**
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {
    	$where['is_del'] = 0;
    	$where['is_lock'] = 0;
    	$where['point_status'] = 1;
    	if($this->input->post('order_type')) $where['type_id'] = $this->input->post('order_type');
    	if($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	if($this->input->post('is_lock')) $where['is_lock'] = $this->input->post('is_lock');
    	
    	$points_lists = $this->Mhouses_points->get_lists("id,code,houses_id,area_id", $where);
    	$areaList = [];
    	if(count($points_lists) > 0) {
    		$housesid = array_column($points_lists, 'houses_id');
    		$area_id = array_column($points_lists, 'area_id');
    		
    		$whereh['in']['id'] = $housesid;
    		$housesList = $this->Mhouses->get_lists("id, name", $whereh);
    		
    		$wherea['in']['id'] = $area_id;
    		$areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
    		
    		foreach ($points_lists as $k => &$v) {
    			foreach($housesList as $k1 => $v1) {
    				if($v['houses_id'] == $v1['id']) {
    					$v['houses_name'] = $v1['name'];
    					break;
    				}
    			}
    			
    			foreach($areaList as $k2 => $v2) {
    				if($v['area_id'] == $v2['id']) {
    					$v['area_name'] = $v2['name'];
    					break;
    				}
    			}
    			
    			
    		}
    	}
    	
    	$this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists), 'area_lists' => $areaList));
    	//$this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists), 'area_lists' => $areaList));
    }


//     /**
//      * 获取投放点位列表和数量
//      */
//     public function get_points() {
//         $where['A.point_status'] = 1;
//         if($this->input->post('media_type')) $where['B.type'] = $this->input->post('media_type');
//         if($this->input->post('media_id')) $where['A.media_id'] = $this->input->post('media_id');
//         if($this->input->post('is_lock') != '') $where['A.is_lock'] = $this->input->post('is_lock');
//         if ($this->input->post('lock_customer_id'))  $where['A.lock_customer_id'] = $this->input->post('lock_customer_id');
//         $points_lists = $this->Mpoints->get_points_lists($where);
//         $this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists)));
//     }



//     /**
//      * 根据订单类型获取媒体列表
//      */
//     public function get_media() {
//         $type = $this->input->post("type");
//         $media_list = $this->Mmedias->get_lists("id, code, name", array('type' => $type, 'is_del' => 0), array('sort' => 'asc'));

//         $option = "<option value=''>请选择媒体</option>";
//         if ($media_list) {
//             foreach ($media_list as $key => $value) {
//                 $option .= '<option value="'.$value['id'].'">'.$value['name'].'('.$value['code'].')'.'</option>';
//             }
//         }

//         $this->return_json(array('flag' => true, 'option' => $option));
//     }




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
                $this->Mhouses_points->update_info(array('customer_id' => '', 'order_id' => '', 'point_status' => 1), array('in' => array('id' => explode(',', $post_data['point_ids_old']))));
                
                $update_data['order_id'] = $id;
                $update_data['customer_id'] = $post_data['customer_id'];
                $update_data['lock_start_time'] = '';
                $update_data['lock_end_time'] = '';
                $update_data['expire_time'] = '';
                $update_data['point_status'] = 3;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

                //先清空点位制作张数表t_points_make_num，再添加进去
//                 $this->Mpoints_make_num->delete(array('order_id' => $id, 'type' => 1));
//                 foreach ($post_data['make_num'] as $key => $value) {
//                     $make_num_data['order_id'] = $id;
//                     $make_num_data['point_id'] = $key;
//                     $make_num_data['make_num'] = $value;
//                     $make_num_data['type'] = 1;
//                     $this->Mpoints_make_num->create($make_num_data);
//                 }
            }

            unset($post_data['media_id'], $post_data['point_status'], $post_data['point_ids_old'], $post_data['make_num'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            $result = $this->Mhouses_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "编辑".$data['order_type_text'][$post_data['order_type']]."订单,订单id【".$id."】");
                $this->success("修改成功！","/orders");
            } else {
                $this->success("修改失败！请重试！","/orders");
            }
        } else {
            $data['info'] = $this->Mhouses_orders->get_one("*", array('id' => $id));

            $data['order_type'] = $data['info']['order_type'];

            //项目
            //$data['project'] = $this->Mcustomer_project->get_lists('id, project_name', array('customer_id' => $data['info']['customer_id']));

            //媒体列表
            //$data['media_list'] = $this->Mmedias->get_lists("id, code, name", array('type' => $data['order_type'], 'is_del' => 0), array('sort' => 'asc'));

            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);
            
//             if ($data['order_type'] == 1 || $data['order_type'] == 2) {
//                 //点位制作张数（灯箱和高杆）
//                 $data['points_make_num'] = $this->Mpoints_make_num->get_lists('order_id, point_id, make_num', array('order_id' => $data['info']['id'], 'type' => 1));
//                 foreach ($data['selected_points'] as $key => $value) {
//                     foreach ($data['points_make_num'] as $k => $v) {
//                         if ($value['id'] == $v['point_id']) {
//                             $data['selected_points'][$key]['make_num'] = $data['points_make_num'][$k]['make_num'];
//                         }
//                     }
//                 }
//             }
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

            $change_points = $this->Mchange_points_record->get_one_change($id);
            if ($change_points) { //修改换点记录表
                $up_data['remove_points'] = $remove_points ? implode(',', $remove_points): $change_points['remove_points'];
                $up_data['add_points'] = $add_points ? implode(',', $add_points) : $change_points['add_points'];
                $this->Mchange_points_record->update_info($up_data, array('id' => $change_points['id']));
            } else {
                //写入换点记录表
                $change_id = $this->Mchange_points_record->create(array('order_id' => $id, 'remove_points' => implode(',', $remove_points), 'add_points' => implode(',', $add_points), 'operate_time' => date('Y-m-d H:i:s')));
                if ($change_id) {
                    //备份之前的订单信息
                    $order_old_info = $this->Morders->get_one('*', array('id' => $id));
                    foreach ($order_old_info as $key => $value) {
                        $log_data[$key] = $value;
                    }
                    $log_data['change_id'] = $change_id;
                    $log_data['log_time'] = date('Y-m-d:H:i:s');
                    unset($log_data['id'], $log_data['is_del'], $log_data['create_user'], $log_data['create_time'], $log_data['update_user'], $log_data['update_time']);
                    $this->Morders_log->create($log_data);

                    //备份之前的验收图片
                    $inspect_images_old = $this->Morder_inspect_images->get_lists('*', array('order_id' => $id, 'type' => 1));
                    foreach ($inspect_images_old as $key => $value) {
                        foreach ($value as $k => $v) {
                            $img_log_data[$k] = $v;
                        }
                        $img_log_data['change_id'] = $change_id;
                        $img_log_data['log_time'] = date('Y-m-d:H:i:s');
                        unset($img_log_data['id'], $img_log_data['type'], $img_log_data['create_user'], $img_log_data['create_time'], $img_log_data['update_user'], $img_log_data['update_time']);
                        $this->Morder_inspect_images_log->create($img_log_data);
                    }

                    //备份之前的点位制作数量
                    $point_makenum_old = $this->Mpoints_make_num->get_lists('*', array('order_id' => $id, 'type' => 1));
                    foreach ($point_makenum_old as $key => $value) {
                        foreach ($value as $k => $v) {
                            $num_log_data[$k] = $v;
                        }
                        $num_log_data['change_id'] = $change_id;
                        unset($num_log_data['id'], $num_log_data['type']);
                        $this->Mpoints_make_num_log->create($num_log_data);
                    }
                }
            }


            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            //先把之前所有已选择的点位的状态置为空闲，再把重新选择的点位状态置为占用
            $this->Mpoints->update_info(array('customer_id' => '', 'order_id' => '', 'point_status' => 1), array('in' => array('id' => explode(',', $post_data['point_ids_old']))));

            $update_data['order_id'] = $id; 
            $update_data['customer_id'] = $post_data['customer_id'];
            $update_data['lock_start_time'] = '';
            $update_data['lock_end_time'] = '';
            $update_data['expire_time'] = '';
            $update_data['point_status'] = 3;
            $this->Mpoints->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

            //如果该订单下有换画订单，则同时更新对应的换画点位，并删除对应的验收图片
            $arr = array_merge($remove_points, $add_points);
            if ($arr) {
                $media = $this->Mpoints->get_lists('media_id', array('in' => array('id' => $arr)));
                $media_ids = array_column($media, 'media_id');

                //最近一次换画订单
                $change_order = $this->Mchange_pic_orders->get_lists('*', array('order_code' => $post_data['order_code']), array('create_time' => 'DESC'));
                if ($change_order) {
                    $change_order = $change_order[0];
                    $arr3 = explode(',', $change_order['point_ids']);
                    $arr4 = array_merge(array_diff($arr3, array_intersect($arr3, $arr)), array_diff($arr, array_intersect($arr, $arr3)));
                    $point_str = $arr4 ? implode(',', $arr4) : '';
                    $this->Mchange_pic_orders->update_info(array('point_ids' => $point_str), array('id' => $change_order['id']));

                    //先清空点位制作张数表t_points_make_num，再添加进去
                    $this->Mpoints_make_num->delete(array('order_id' => $change_order['id'], 'type' => 2));
                    foreach ($post_data['make_num'] as $key => $value) {
                        $make_num_data['order_id'] = $change_order['id'];
                        $make_num_data['point_id'] = $key;
                        $make_num_data['make_num'] = $value;
                        $make_num_data['type'] = 2;
                        $this->Mpoints_make_num->create($make_num_data);
                    }

                    //删除该订单下最近一次换画订单更换点位的验收图片
                    $this->Morder_inspect_images->delete(array('order_id' => $change_order['id'], 'in' => array('media_id' => $media_ids), 'type' => 2));
                }
                //删除该订单更换点位的验收图片
                $this->Morder_inspect_images->delete(array('order_id' => $id, 'in' => array('media_id' => $media_ids), 'type' => 1));
            }

            //先清空点位制作张数表t_points_make_num，再添加进去
            if ($post_data['order_type'] == 1 || $post_data['order_type'] == 2) {
                $this->Mpoints_make_num->delete(array('order_id' => $id, 'type' => 1));
                foreach ($post_data['make_num'] as $key => $value) {
                    $make_num_data['order_id'] = $id;
                    $make_num_data['point_id'] = $key;
                    $make_num_data['make_num'] = $value;
                    $make_num_data['type'] = 1;
                    $this->Mpoints_make_num->create($make_num_data);
                }
            }

            //更新订单信息
            unset($post_data['media_id'], $post_data['point_ids_old'], $post_data['make_num']);
            $result = $this->Morders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "投放中订单修改点位，订单id【".$id."】");
                $this->success("修改成功！","/orders");
            } else {
                $this->error("修改失败！请重试！","/orders");
            }
        } else {
            $data['info'] = $this->Morders->get_one("*", array('id' => $id));

            $data['order_type'] = $data['info']['order_type'];

            //媒体列表
            $data['media_list'] = $this->Mmedias->get_lists("id, code, name", array('type' => $data['order_type'], 'is_del' => 0), array('sort' => 'asc'));

            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mpoints->get_points_lists($where);

            //客户
            $data['info']['customer_name'] = $this->Mcustomers->get_one('customer_name', array('id' => $data['info']['customer_id']))['customer_name'];
            //业务员
            $data['info']['sales'] = $this->Msalesman->get_one('name, phone_number', array('id' => $data['info']['sales_id']));
            //制作公司
            $data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];

            if ($data['order_type'] == 1 || $data['order_type'] == 2) {
                //点位制作张数（灯箱和高杆）
                $data['points_make_num'] = $this->Mpoints_make_num->get_lists('order_id, point_id, make_num', array('order_id' => $data['info']['id'], 'type' => 1));
                foreach ($data['selected_points'] as $key => $value) {
                    foreach ($data['points_make_num'] as $k => $v) {
                        if ($value['id'] == $v['point_id']) {
                            $data['selected_points'][$key]['make_num'] = $data['points_make_num'][$k]['make_num'];
                        }
                    }
                }
            }

            $this->load->view('orders/edit_points', $data);
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
                //$data['media_list'] = $this->Mhouses_points->get_make_high(array('in' => array('B.id' => explode(',', $data['info']['point_ids']))));
                //$this->load->view('housesorders/contact_list/high', $data);
            	$this->load->view('housesorders/contact_list/light', $data);
            }
        } 

    }


    /**
     * 生成确认函
     */
    public function confirmation($id) {
        $data = $this->data;
        $data['info'] = $this->Morders->get_one("*", array('id' => $id));

        $images = $this->Morder_inspect_images->get_lists("*", array('order_id' => $id, 'type' => 1));
        if (!$images) {
            $this->success("请先上传验收图片！","/changepicorders");
        }

        //甲方-委托方（客户名称）
        $data['info']['customer_name'] = $this->Mcustomers->get_one('customer_name', array('id' => $data['info']['customer_id']))['customer_name'];
            
        //上画完成时间
        $data['complete_date'] = date('Y年m月d日', strtotime($data['info']['draw_finish_time']));

        /********验收图片**********/
        $data['inspect_images'] = $this->Morder_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.type' => 1));

        //获取点位列表
        $where['in'] = array('B.id' => explode(',', $data['info']['point_ids']));
        $data['points'] = $this->Mpoints->get_confirm_points($where, array('A.sort' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));

        if ($data['info']['order_type'] == '1') {    //灯箱
            //统计大灯箱、中灯箱、小灯箱套数
            $make = $this->get_make_info($data['info']);
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
        } elseif ($data['info']['order_type'] == '2') {  //户外高杆
            //高杆数
            $data['total_num'] = $this->get_make_info($data['info'])['high_count'];

            $this->load->view('orders/confirmation/high', $data);
        } elseif ($data['info']['order_type'] == '3' || $data['info']['order_type'] == '4') {   //led
            $this->load->view('orders/confirmation/led', $data);
        }
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
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))));

        //广告画面
        $data['info']['adv_img'] = $data['info']['adv_img'] ? explode(',', $data['info']['adv_img']) : array();

        //验收图片
        $data['info']['inspect_img'] = $this->Mhouses_order_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.type' => 1));
		
        //每个媒体对应套数
//         $where_point['in'] = array('B.id' => explode(',', $data['info']['point_ids']));
//         $points = $this->Mhouses_points->get_confirm_points($where_point, array('A.sort' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));
//         $data['number'] = array_column($points, 'counts', 'media_id');

        //换画记录
        //$data['info']['change_pic_record'] = $this->Mchange_pic_orders->get_order_lists(array('A.order_code' => $data['info']['order_code']));

        //换点记录
//         $data['info']['change_points_record'] = $this->Mchange_points_record->get_lists('*', array('order_id' => $id), array('operate_time' => 'desc'));
//         foreach ($data['info']['change_points_record'] as $key => $value) {
//             $remove_points = $this->Mpoints->get_lists('points_code', array('in' => array('id' => explode(',', $value['remove_points']))));
//             $data['info']['change_points_record'][$key]['remove_points'] = implode(',', array_column($remove_points, 'points_code'));

//             $add_points= $this->Mpoints->get_lists('points_code', array('in' => array('id' => explode(',', $value['add_points']))));
//             $data['info']['change_points_record'][$key]['add_points'] = implode(',', array_column($add_points, 'points_code'));
//         }

        if($data['info']['order_type'] == 3 || $data['info']['order_type'] == 4){
            $data['status_text'] = C('order.order_status.led_text');
        }else{
            //制作公司
            $data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];
            $data['status_text'] = C('order.order_status.text');
        }

//         //获取对应订单状态的操作信息
//         $operate_time = $this->Mstatus_operate_time->get_lists("value,operate_remark,operate_time",array("order_id" => $id , 'type' => 1));
//         if($operate_time){
//             $data['time'] = array_column($operate_time, "operate_time", "value");
//             $data['operate_remark'] = array_column($operate_time, "operate_remark", "value");
//         }

        $data['id'] = $id;

        $this->load->view('housesorders/detail', $data);
    }
    
    /*
     * 
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
            
            $count = $this->Mstatus_operate_time->count(array("order_id" => $id, "value" => $status, "type" => 1));
            if($count){
                $res = $this->Mstatus_operate_time->update_info(
                    array('operate_remark' => $operate_remark,"operate_time"=> date("Y-m-d H:i:s")),
                    array('order_id' => $id, "value" => $status, "type" => 1)
                );
                //删除该状态以下的所有状态
                $where['order_id'] = $id;
                $where['value>'] = $status;
                $where['type'] = 1;
                $res = $this->Mstatus_operate_time->delete($where);
            }else{
                $post_data['order_id'] = $id;
                $post_data['value'] = $status;
                $post_data['operate_time'] = date("Y-m-d H:i:s");
                $post_data['operate_remark'] = $operate_remark;
                $post_data['type'] = 1;
                $this->Mstatus_operate_time->create($post_data);
            }
            
            $data = $this->data;
            $status_text = C('order.order_status.text');

            $this->write_log($data['userInfo']['id'],2,"  更新订单:".$order_code."状态：".$status_text[$status]);
            //同时更新对应的订单
            $result = $this->Mhouses_orders->update_info(array("order_status"=>$status),array("id"=>$id));
            if($status == 8){
                if($result){
                    //如果订单已经下画则释放所有点位
                    $update_data['order_id'] = "";
                    $update_data['customer_id'] = "";
                    $update_data['point_status'] = 1;
                    $update_data['lock_start_time'] = "";
                    $update_data['lock_end_time'] = "";
                    $update_data['expire_time'] = "";

                    $this->Mpoints->update_info($update_data,array("order_id"=>$id));

                    //更新该订单下所有换画订单的状态为已下画
                    $order_code = $this->Mhouses_orders->get_one('order_code', array('id' => $id))['order_code'];
                    $change_count = $this->Mhouses_change_pic_orders->count(array('order_code' => $order_code));
                    if ($change_count) {
                        $this->Mhouses_change_pic_orders->update_info(array('order_status' => 8), array('order_code' => $order_code));
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


    /*
     * 验收图片
     * 1034487709@qq.com
     */
    public function check_upload_img($order_id){
        $data = $this->data;
        $order = $this->Mhouses_orders->get_one("*",array("id" => $order_id));
        if(IS_POST){
            $post_data = $this->input->post();
            foreach ($post_data as $key => $value) {
                $where = array('order_id' => $order_id, 'point_id' => $key, 'type' => 1);
                $img = $this->Mhouses_order_inspect_images->get_one('*', $where);

                //如果是修改验收图片，则先删除该订单下所有验收图片，再重新添加
                if ($img) {
                    $this->Mhouses_order_inspect_images->delete($where);
                }

                if (isset($value['front_img']) && count($value['front_img']) > 0) {
                    foreach ($value['front_img'] as $k => $v) {
                        $insert_data['order_id'] = $order_id;
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

            //更新订单
            $this->Mhouses_orders->update_info(array('sponsor' => $post_data['sponsor'], 'draw_finish_time' => $post_data['draw_finish_time'], 
                    'release_start_time' => $post_data['release_start_time'], 'release_end_time' => $post_data['release_end_time']), array('id' => $order_id));

            $this->write_log($data['userInfo']['id'], 2, "社区上传订单验收图片，订单id【".$order_id."】");

            $this->success("保存验收图片成功！","/housesorders/detail/".$order_id);
            exit;
        }
        
        //获取该订单下面的所有楼盘
        $points = $this->Mhouses_points->get_points_lists(array('in' => array("A.id" => explode(",",$order['point_ids']))));
        //$houses_id = array_unique(array_column($points, "houses_id"));
        //$area_id = array_unique(array_column($points, "area_id"));
        
        //获取该订单下面的所有站台
        //$points = $this->Mhouses_points->get_lists("media_id", array('in' => array("id" => explode(",",$order['point_ids']))));

        //$media_id = array_unique(array_column($points, "media_id"));

        //$data['media_list'] = $this->Mmedias->get_lists("id,name,code", array('in' => array("id"=>$media_id)), array('sort' => 'asc'));
		
        //根据点位id获取对应的图片
        $data['images'] = "";
        if(count($points) > 0) {
        	$where['in'] = array("point_id"=>array_column($points,"id"));
        	$where['order_id'] = $order_id;
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
        //var_dump($list);
        
        
        //根据媒体ID获取对应的图片
//        $data['images'] = "";
//         if($data['media_list']){
//             $where['in'] = array("media_id"=>array_column($data['media_list'],"id"));
//             $where['order_id'] = $order_id;
//             $where['type'] = 1;
//             $data['images'] = $this->Morder_inspect_images->get_lists("*",$where);
//         }

//         $list = array();
//         foreach($data['media_list'] as $key=>$val){
//             $val['image'] = array();
//             if($data['images']){
//                 foreach($data['images'] as $k=>$v){
//                     if($val['id'] == $v['media_id']){
//                         $val['image'][] = $v;
//                     }
//                 }
//             }
//            $list[] = $val;
//         }

        //$data['list'] = $list;

        //每个媒体对应套数
//         $where_point['in'] = array('B.id' => explode(',', $order['point_ids']));
//         $points = $this->Mpoints->get_confirm_points($where_point, array('A.sort' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));
//         $data['number'] = array_column($points, 'counts', 'media_id');

        $data['order_type'] = $order['order_type'];
        $data['order_info'] = $order;

        $this->load->view('housesorders/check_adv_img', $data);
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
        if ($type == 1) {
            $table_header =  array(
                '点位编号'=>"points_code",
                '站台名称'=>"media_name",
                '规格'=>"spec",
            );
        } else {
            $table_header =  array(
                '点位编号'=>"points_code",
                '高杆名称'=>"media_name",
                '规格'=>"spec",
            );
        }

        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }

        $order = $this->Morders->get_one('*', array('id' => $id));

        $where['in']['A.id'] = explode(',', $order['point_ids']);

        $customers = array_column($this->Mcustomers->get_lists("id,customer_name", array('is_del' => 0)), 'customer_name', 'id'); //客户列表

        $list = $this->Mpoints->lists($where);

        $h = 2;
        foreach($list as $key=>$val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;

                switch ($v) {
                    case 'spec':
                        $value = $type == 1 ? $val['size'].'（'.$val['spec_name'].'）' : $val['size'];
                        break;
                    case 'media_name':
                        $value = $val['media_name'].'（'.$val['media_code'].'）';
                        break;
                    default:
                        $value = $val[$v];
                        break;
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

}


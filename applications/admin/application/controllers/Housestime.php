<?php 
/**
* 定时任务执行控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housestime extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model([
        		'Model_houses_orders' => 'Mhouses_orders',
        		'Model_houses_assign' => 'Mhouses_assign'
        ]);
    }
    
    public function index() {
    	echo "this function is disable";exit;
    }
    
    /*
     * 订单在投放时间结束的前一天转为待下画状态以便进行下画派单
     */
    public function order_time() {
    	$where['order_status'] = 7;
    	$where['release_end_time<='] = date("Y-m-d",strtotime("+1 day"));
    	
    	$order_list = $this->Mhouses_orders->get_lists('id', $where);
    	
    	$update_data['order_status'] = 8;
    	$update_data['assign_type'] = 2;
    	$update_data['assign_status'] = 1;
    	$res = $this->Mhouses_orders->update_info($update_data, $where);
    	
    	if($res && count($order_list) > 0) {
    		$update_data = $where = [];
    		$order_ids = array_column($order_list, 'id');
    		
    		$where['in']['order_id'] = $order_ids;
    		$update_data['is_del'] = 1;
//     		$update_data['assign_type'] = 2;
//     		$update_data['status'] = 1;
//     		$update_data['remark'] = '';
//     		$update_data['assign_user'] = 0;
//     		$update_data['assign_time'] = '';
    		$res = $this->Mhouses_assign->update_info($update_data, $where);
    		
    		echo "转化以下订单为待下画：".implode(',', $order_ids);
    	}
    }

    
}


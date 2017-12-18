<?php 
/**
* 生成验收报告
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Makereport extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network' => 'Mnetwork',
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_apply' => 'Mnetwork_apply',
        ]);
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'make_report';
    }
    

    /**
     * 生成验收报告
     */
    public function index() {
    	$data = $this->data;
    	$this->load->view("makereport/index", $data);
    }
    
    /**
     * 提交
     */
    public function submit() {
    	$data = $this->data;
    	if(IS_POST) {
    		$post_data = $this->input->post();
    		
    		$data['info']['customer_name'] = $post_data['first'];
    		$data['info']['sponsor'] = $post_data['second'];
    		$data['info']['order_type'] = $post_data['media_type'];
    		$data['info']['remark'] = $post_data['remark'];
    		
    		$tmpArr = array();
    		foreach($post_data['name'] as $k => $v) {
    			$tmppos = strpos($v, '(');
    			if($tmppos) {
    				$tmpArr[$k]['media_name'] = substr($v, 0, $tmppos);
    				$tmpArr[$k]['media_code'] = substr($v, $tmppos);
    			}
    			
    			$tmppos1 = strpos($post_data['format'][$k], '（');
    			if($tmppos1) {
    				$tmpArr[$k]['specification_name'] = substr($post_data['format'][$k], 0, $tmppos1);
    				$tmpArr[$k]['size'] = substr($post_data['format'][$k], $tmppos1);
    			}
    			
    			$tmpArr[$k]['counts'] = 1;
    			
    		}
    		
    		$data['points'] = $tmpArr;
    		
    		$tmpArr = array();
    		if(isset($_FILES["fimg"])) {
    			
    			array_map('unlink',glob('uploads/tmp/*'));
    			
    			foreach($_FILES["fimg"]["name"] as $k => $v) {
    				//前
    				if($_FILES["fimg"]["tmp_name"][$k]) {
    					move_uploaded_file($_FILES["fimg"]["tmp_name"][$k], "uploads/tmp/" . $_FILES["fimg"]["name"][$k]);
    				}
    				$tmpArr[$k]['front_img'] = "/uploads/tmp/" . $_FILES["fimg"]["name"][$k];
    				
    				//后
    				if($_FILES["bimg"]["tmp_name"][$k]) {
    					move_uploaded_file($_FILES["bimg"]["tmp_name"][$k], "uploads/tmp/" . $_FILES["bimg"]["name"][$k]);
    				}
    				$tmpArr[$k]['back_img'] = "/uploads/tmp/" . $_FILES["bimg"]["name"][$k];
    				
    				
    				$tmppos = strpos($post_data['name'][$k], '(');
    				if($tmppos) {
    					$tmpArr[$k]['media_name'] = substr($post_data['name'][$k], 0, $tmppos);
    					$tmpArr[$k]['media_code'] = substr($post_data['name'][$k], $tmppos);
    				}
    				$tmpArr[$k]['media_id'] = 1;
    			}
    			
    		}
    		
    		
    		$data['inspect_images'] = $tmpArr;
    		$data['volume'] = array(1=>'1');
    		
    	
    		if ($data['info']['order_type'] == '1') {    //灯箱
    			//统计大灯箱、中灯箱、小灯箱套数
    			//     			$make = $this->get_make_info($data['info']);
    			//     			$make_info = multi_arr_sort($make['make_num'], 'spec_id');
    			//     			$data['number'] = array();
    			//     			foreach($make_info as $k=>$v){
    			//     				if(!isset($data['number'][$v['spec_name']])){
    			//     					$data['number'][$v['spec_name']] = $v['counts'];
    			//     				}else{
    			//     					$data['number'][$v['spec_name']] += $v['counts'];
    			//     				}
    			//     			}
    	
    			//     			//广告总套数
    			//     			$data['total_num'] = $make['total_counts'];
    			//     			$data['volume'] = array_column($data['points'], 'counts' ,'media_id');
    	
    			$this->load->view('makereport/confirmation/light', $data);
    		} elseif ($data['info']['order_type'] == '2') {  //户外高杆
    			//高杆数
    			//     			$data['total_num'] = $this->get_make_info($data['info'])['high_count'];
    	
    			$this->load->view('makereport/confirmation/high', $data);
    		} elseif ($data['info']['order_type'] == '3' || $data['info']['order_type'] == '4') {   //led
    			$this->load->view('makereport/confirmation/led', $data);
    		}
    	
    	}
    }

}


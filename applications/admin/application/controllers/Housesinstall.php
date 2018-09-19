<?php
/**
 * 楼盘管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesinstall extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
            'Model_admins' => 'Madmins',
            'Model_houses_linkman' => 'Mhouses_linkman',
            'Model_houses_area' => 'Mhouses_area'
            
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_install_list';
        
        $this->data['houses_type'] = C("public.houses_type");
        $this->data['put_trade'] = C('housespoint.put_trade'); //禁投放行业
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $install = C('install.install');
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = 0;

        if ($this->input->get('name')) $where['id'] = $this->input->get('name');
        if ($this->input->get('finish_date_start')){
            $finish_date_start = $this->input->get('finish_date_start');
            $where['finish_date >='] = $finish_date_start;
        }
        if ($this->input->get('finish_date_end')){
            $finish_date_end = $this->input->get('finish_date_end');
            $where['finish_date <='] = $finish_date_end;
        }
        if ($this->input->get('check_date_start')){
            $check_date_start = $this->input->get('check_date_start');
            $where['check_date >='] = $check_date_start;
        }
        if ($this->input->get('check_date_end')){
            $check_date_end = $this->input->get('check_date_end');
            $where['check_date <='] = $check_date_end;
        }
        if ($this->input->get('push_date_start')){
            $push_date_start = $this->input->get('push_date_start');
            $where['push_date >='] = $push_date_start;
        }
        if ($this->input->get('push_date_end')){
            $push_date_end = $this->input->get('push_date_end');
            $where['push_date <='] = $push_date_end;
        }
        if ($this->input->get('eg_card_num_start')){
            $eg_card_num_start = $this->input->get('eg_card_num_start');
            $where['eg_card_num >='] = $eg_card_num_start;
        }
        if ($this->input->get('eg_card_num_end')){
            $eg_card_num_end = $this->input->get('eg_card_num_end');
            $where['eg_card_num <='] = $eg_card_num_end;
        }
        if ($this->input->get('install')){
            $install_name = $this->input->get('install');
            $where['install'] = $install_name;
        }
        if ($this->input->get('install_progress_name')) $where['install_progress'] = $this->input->get('install_progress_name');
        if ($this->input->get('install_jointer_name')) $where['install_jointer'] = $this->input->get('install_jointer_name');
        
        $data['name'] = $this->input->get('name');
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['is_check_out'] = $this->input->get('is_check_out');
        $data['finish_date_start'] = $this->input->get('finish_date_start');
        $data['finish_date_end'] = $this->input->get('finish_date_end');
        $data['install_progress_name'] = $this->input->get('install_progress_name');
        $data['check_date_start'] = $this->input->get('check_date_start');
        $data['check_date_end'] = $this->input->get('check_date_end');
        $data['push_date_start'] = $this->input->get('push_date_start');
        $data['push_date_end'] = $this->input->get('push_date_end');
        $data['eg_card_num_start'] = $this->input->get('eg_card_num_start');
        $data['eg_card_num_end'] = $this->input->get('eg_card_num_end');
        $data['install_jointer_name'] = $this->input->get('install_jointer_name');
        $data['install'] = $this->input->get('install');
        
        $list = $this->Mhouses->get_lists('*',$where,[],$size,($page-1)*$size);
        $data['hlist'] = $this->Mhouses->get_lists();
        $data['install_progress'] = $this->Mhouses->get_lists('install_progress',['install_progress !=' => ''],0,0,0,'install_progress');
        $data['install_jointer'] = $this->Mhouses->get_lists('install_jointer',['install_jointer !=' => ''],0,0,0,'install_jointer');
        $admin = $this->Madmins->get_lists();
        foreach ($list as $k => $v){
            $list[$k]['fullname'] = '';
            foreach ($admin as $k2 => $v2){
                if($v['check_user'] == $v2['id']){
                    $list[$k]['fullname'] = $v2['fullname'];
                    break;
                }
            }
            //安装公司
            foreach (C('install')['install'] as $k2 => $v2){
                if($v['install'] == $k2){
                    $list[$k]['install'] = $v2;
                }
            }
        }
        $data['list'] = $list;
        
        $data_count = $this->Mhouses->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/housesinstall";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        $data['houses_grade'] = C("public.houses_grade");
        $this->load->view("housesinstall/index",$data);
    }
    
    /**
     * 楼盘安装详情查看
     */
    public function houses_detail($houses_id) {
        $data = $this->data;
        $where['A.houses_id'] = $houses_id;
        
        //$data['list'] = $this->Mhouses_linkman->get_houses_linkmans($where,[]);
//         $this->Mhouses_linkman->get_lists('*',$where2)
        $data['list'] = $this->Mhouses_linkman->get_houses_linkmans($where,[]);
        $area_ids = array_column($data['list'], 'area_id');
        $area_ids = array_unique($area_ids);
        $temp_area = $this->Mhouses_area->get_lists('*',['in' => ['id' => $area_ids]]);
        foreach ($data['list'] as $k => $v){
            $data['list'][$k]['area_name'] = '';
            foreach ($temp_area as $k1 => $v1){
                if($v['area_id'] == $v1['id']){
                    $data['list'][$k]['area_name'] = $v1['name'];
                }
            }
        }
        $data['data_count'] = count($data['list']);
        
        $this->load->view('housesinstall/houses_detail', $data);
    }
    /**
     * ajax更新数据
     */
    public function ajax_update(){
        $id = $this->input->post('id');
        $data['linkman'] = $this->input->post('name');
        $data['linkman_tel'] = $this->input->post('tel');
        $data['linkman_duty'] = $this->input->post('duty');
        $res = $this->Mhouses_linkman->update_info($data,['id' => $id]);
        if(!$res){
            $this->return_json(['code' => 0,'msg' => '更新失败']);
        }
        $this->return_json(['code' => 1,'msg' => '更新成功']);
    }
    
    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            if(isset($post['cover_img']) && !empty($post['cover_img'])){
                $post['check_img'] = implode(',', $post['cover_img']);
                unset($post['cover_img']);
            }
            unset($post['sub_put_trade']);
            $oldname = $this->Mhouses->get_one('name',['id' => $id]);
            $result = $this->Mhouses->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑安装楼盘：".$post['name']);
                $this->success("编辑成功","/housesinstall");
            }else{
                $this->error("编辑失败",'');
            }

        }

        $info = $this->Mhouses->get_one("*",array("id"=>$id));
        $admin = $this->Madmins->get_lists();
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        $data['admin'] = $admin;
        
        $data['houses_type'] = C("public.houses_type");
        $data['houses_grade'] = C("public.houses_grade");
        $data['install'] = C('install.install');
        $this->load->view("housesinstall/edit",$data);
    }

    public function out_excel(){
    	$data = $this->data;
    	$where['is_del'] = 0;
    	if ($this->input->get('name')) $where['id'] = $this->input->get('name');
    	//加载phpexcel
    	$this->load->library("PHPExcel");
    	$table_header =  array(
    			'楼盘名称'=>"name",
    			'物业联系人'=>"linkman",
    			'联系人职务'=>"linkman_duty",
    			'联系人电话'=>"linkman_tel",
    			'签约数量'=>"sign_num",
    			'完工日期'=>"finish_date",
    			'安装数量'=>"install_num",
    			'安装结算数量'=>"install_account_num",
    			'安装备注'=>"install_remake",
    			'验收人'=>"check_user",
    			'验收日期'=>"check_date",
        	    '是否结算'=>"is_account",
        	    '结算日期'=>"account_date",
        	    '提成数量'=>"push_num",
    	        '提成日期'=>"push_date"
    	);
    	
    	$i = 0;
    	foreach($table_header as  $k=>$v){
    		$cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
    		$this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
    		$i++;
    	}
    	
    	$tmpList = $this->Mhouses->get_lists('*',$where);
    	
    	$h = 2;
    	foreach($tmpList as $key=>$val){
    		
    		$j = 0;
    		foreach($table_header as $k1 => $v1){
    			$cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
    			$value = $val[$v1];
    			$this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value.' ');
    		}
    		$h++;
    	}
    
    	$this->phpexcel->setActiveSheetIndex(0);
    	// 输出
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename=社区楼盘安装表.xls');
    	header('Cache-Control: max-age=0');
    
    	$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
    	$objWriter->save('php://output');
    }

}
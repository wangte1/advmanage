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

        $data['name'] = $this->input->get('name');
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['is_check_out'] = $this->input->get('is_check_out');
        
        $list = $this->Mhouses->get_lists('*',$where,[],$size,($page-1)*$size);
        $data['hlist'] = $this->Mhouses->get_lists();
        $admin = $this->Madmins->get_lists();
        foreach ($list as $k => $v){
            $list[$k]['fullname'] = '';
            foreach ($admin as $k2 => $v2){
                if($v['check_user'] == $v2['id']){
                    $list[$k]['fullname'] = $v2['fullname'];
                    break;
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
                $post['check_img'] = $post['cover_img'][0];
                unset($post['cover_img']);
            }
            unset($post['sub_put_trade']);
            $oldname = $this->Mhouses->get_one('name',['id' => $id]);
            $result = $this->Mhouses->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑安装楼盘：".$post['name']);
                $this->success("编辑成功","/housesinstall");
            }else{
                $this->error("编辑失败");
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
    			'签约室内数量'=>"sign_in_num",
    			'签约室外数量'=>"sign_out_num",
    			'完工日期'=>"finish_date",
    			'安装数量'=>"install_num",
    			'安装室内数量'=>"install_in_num",
    			'安装室外数量'=>"install_out_num",
    			'安装结算数量'=>"install_account_num",
    			'安装备注'=>"install_remake",
    			'是否验收'=>"is_check",
    			'验收人'=>"check_user",
    			'验收日期'=>"check_date",
        	    '是否结算'=>"is_account",
        	    '结算日期'=>"account_date",
        	    '是否提成'=>"is_push",
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
<?php
/**
 * 楼盘组团管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesarea extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses' => 'Mhouses',
            'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_houses_group' => 'Mhouses_group'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_area_lists';
        
        $this->data['area_grade'] = C("public.area_grade");
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = 0;

        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        if ($this->input->get('houses_id') != 'all' && !empty($this->input->get('houses_id'))) {
        	$where['houses_id'] = $this->input->get('houses_id') ? $this->input->get('houses_id') : 1;
        	//$data['group_arr'] = $this->Mhouses_group->get_lists('id,group_name', $where);
        }
        if ($this->input->get('group_id')) $where['group_id'] = $this->input->get('group_id');
        
        if ($this->input->get('grade') != 'all' && !empty($this->input->get('grade'))) {
        	$where['grade'] = $this->input->get('grade') ? $this->input->get('grade') : 1;
        }
        

        $data['name'] = $this->input->get('name');
        $data['houses_id'] = $this->input->get('houses_id');
        $data['group_id'] = $this->input->get('group_id');
        $data['grade'] = $this->input->get('grade');
        
        $tmpList = $this->Mhouses_area->get_lists('*',$where,[],$size,($page-1)*$size);
        
        if(count($tmpList) > 0) {
        	foreach($tmpList as $k => &$v) {
        		$v['count_1'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 1, 'is_del' => 0]);
        		$v['count_2'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 2, 'is_del' => 0]);
        		$v['count_3'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 3, 'is_del' => 0]);
        		$v['count_4'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'is_del' => 0]);
        	}
        }
        
        $data['list'] = $tmpList;
        
        $data_count = $this->Mhouses_area->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/housesarea";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        $data['houses_type'] = C("public.houses_type");
        
        $data['list1'] = $this->Mhouses->get_lists('id,name',['is_del' => 0]);
        
        if(count($data['list']) > 0) {
        	$area_ids = array_column($data['list'], 'id');
        	$where1['in']['A.id'] = $area_ids;
        	$join_arr = $this->Mhouses_area->get_join_info($where1);
        
        	$data['houses_name'] = array_column($join_arr, 'houses_name', 'id');
        }
        
        $this->load->view("housesarea/index",$data);
    }

    /*
     * 添加
     * 867332352@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("楼盘区域管理","添加楼盘区域");

        if(IS_POST){
            $post = $this->input->post();
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");
            //给组团添加临时楼盘字段
            $temp = $this->Mhouses->get_one('name',['id' => $post['houses_id']]);
            $post['houses_name'] = $temp['name'];

            $result = $this->Mhouses_area->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增楼盘区域：".$post['name']);
                $this->success("添加成功","/housesarea");
            }else{
                $this->error("添加失败");
            }

        }
        
        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);

        $this->load->view("housesarea/add",$data);
    }

    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("楼盘区域管理","编辑楼盘区域");

        if(IS_POST){
            $post = $this->input->post();
            $newhouse = $this->Mhouses->get_one('name',['id' => $post['houses_id']]);
            $oldname = $this->Mhouses_area->get_one('name',['id' => $id]);
            $this->Mhouses_area->update_info(['houses_name' => $newhouse['name']],['id' => $id]);
            $this->Mhouses_points->update_info(['area_name' => $post['name'],'houses_name' => $newhouse['name']], ['area_name' => $oldname['name']]);
            
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses_area->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑站台：".$post['name']);
                $this->success("编辑成功","/housesarea");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_area->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
		
        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);


        $this->load->view("housesarea/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mhouses_area->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_area->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除楼盘区域：".$name['name']);
            $this->success("删除成功！","/housesarea");

        }else{
            $this->error("删除失败！");
        }
    }
    
    /*
     * 导出数据
     * 1034487709@qq.com
     */
    public function out_excel(){
    	$data = $this->data;
    	$where['is_del'] = 0;
    	if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        if ($this->input->get('houses_id') != 'all' && !empty($this->input->get('houses_id'))) {
        	$where['houses_id'] = $this->input->get('houses_id') ? $this->input->get('houses_id') : 1;
        	$data['group_arr'] = $this->Mhouses_group->get_lists('id,group_name', $where);
        }
        if ($this->input->get('group_id')) $where['group_id'] = $this->input->get('group_id');
        
        if ($this->input->get('grade') != 'all' && !empty($this->input->get('grade'))) {
        	$where['grade'] = $this->input->get('grade') ? $this->input->get('grade') : 1;
        }
    
    	//加载phpexcel
    	$this->load->library("PHPExcel");
    
    	$table_header =  array(
    			'点位编号'=>"code",
    			'组团名称'=>"name",
    			'所属楼盘'=>"houses_name",
    			'等级'=>"grade",
    			'门禁点位数'=>"count1",
    			'地面电梯前室点位数'=>"count2",
    			'地下电梯前室点位数'=>"count3",
    			'合计点位数'=>"count",
    	);
    	 
    
    	$i = 0;
    	foreach($table_header as  $k=>$v){
    		$cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
    		$this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
    		$i++;
    	}
    	 
    	$tmpList = $this->Mhouses_area->get_lists('*', $where);
    	
    	$housesList = $this->Mhouses->get_lists('id,name',['is_del' => 0]);
    	
    	if(count($tmpList) > 0) {
    		$area_ids = array_column($tmpList, 'id');
    		$where1['in']['A.id'] = $area_ids;
    		$join_arr = $this->Mhouses_area->get_join_info($where1);
    	
    		$houses_name = array_column($join_arr, 'houses_name', 'id');
    	}
        
        if(count($tmpList) > 0) {
        	foreach($tmpList as $k => &$v) {
        		$v['count_1'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 1, 'is_del' => 0]);
        		$v['count_2'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 2, 'is_del' => 0]);
        		$v['count_3'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'addr' => 3, 'is_del' => 0]);
        		$v['count_4'] = $this->Mhouses_points->get_one('count(0) as count', ['area_id' => $v['id'], 'is_del' => 0]);
        	}
        }
    
    	$h = 2;
    	foreach($tmpList as $key=>$val){
    		$j = 0;
    		foreach($table_header as $k1 => $v1){
    			$cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
    
    			if($v1 == "code") {
    				$value = $key + 1;
    			}
    
    			if($v1 == "name") {
    				$value = $val['name'];
    			}
    
    			if($v1 == "houses_name") {
    				$value = $houses_name[$val['id']];
    			}
    
    			if($v1 == "grade") {
    				if(isset($data['area_grade'][$val['grade']])) {
    					$value = $data['area_grade'][$val['grade']];
    				}
    				
    			}
    			
    			if($v1 == "count1") {
    				$value = $val['count_1']['count'];
    			}
    
    			if($v1 == "count2") {
    				$value = $val['count_2']['count'];
    			}
    			
    			if($v1 == "count3") {
    				$value = $val['count_3']['count'];
    			}
    			
    			if($v1 == "count") {
    				$value = $val['count_4']['count'];
    			}
    			
    			$this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value.' ');
    		}
    		$h++;
    	}
    
    	$this->phpexcel->setActiveSheetIndex(0);
    	// 输出
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename=社区组团表.xls');
    	header('Cache-Control: max-age=0');
    
    	$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
    	$objWriter->save('php://output');
    }
    


}
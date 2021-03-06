<?php
/**
 * 楼盘管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesquery extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
        	'Model_area' => 'Marea',
            'Model_houses_area' => 'Harea',
        	'Model_houses_points' => 'Mhouses_points',
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_query_lists';
        
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
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = 0;
        $houses_id = $this->input->get('houses_id');
        if($houses_id){
            $where['id'] = $houses_id;
            $data['houses_id'] = $houses_id;
            $data['list']['db'] = $this->Mhouses->get_one('*', $where);
            $data['count']['count_1'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $houses_id, 'addr' => 1, 'is_del' => 0])['count'];
            $data['count']['count_2'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $houses_id, 'addr' => 2, 'is_del' => 0])['count'];
            $data['count']['count_3'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $houses_id, 'addr' => 3, 'is_del' => 0])['count'];
            $data['count']['count_4'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $houses_id, 'is_del' => 0])['count'];
            
            $temp= C('housespoint.put_trade'); //禁投放行业
            $arr = explode(',',$data['list']['db']['put_trade']);
            $data['list']['db']['put_trade'] = '';
            foreach ($arr as $k => $v){
                if($v != ''){
                   $data['list']['db']['put_trade'] .= $temp[$v].',';
                }
            }
            $temp = C("public.houses_grade");//等级
            $data['list']['db']['grade'] = $temp[$data['list']['db']['grade']];
            
            $data_count = $this->Mhouses->count($where);
            $data['page'] = $page;
            $data['data_count'] = $data_count;
        }
        
        //获取分页
        $pageconfig['base_url'] = "/houses";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        //$data['houses_type'] = C("public.houses_type");
        
        $data['houses_grade'] = C("public.houses_grade");
        
        $this->load->view("housesquery/index",$data);
    }
    /**
     * @desc ajax获取楼盘信息
     * @author admin@ttitt.net
     */
    public function get_houses() {
        $list = $this->Mhouses->get_lists('id,name');
        $this->return_json(['code' => 1, 'list' => $list]);
    }

    public function out_excel(){
    	$data = $this->data;
    	$where['is_del'] = 0;
    	if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        if ($this->input->get('province')) $where['province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['area'] = $this->input->get('area');

        if ($this->input->get('type') != 'all' && !empty($this->input->get('type'))) {
            $where['type'] = $this->input->get('type') ? $this->input->get('type') : 1;
        }
        
        if ($this->input->get('grade') != 'all' && !empty($this->input->get('grade'))) {
        	$where['grade'] = $this->input->get('grade') ? $this->input->get('grade') : 1;
        }
    
    	//加载phpexcel
    	$this->load->library("PHPExcel");
    
    	$table_header =  array(
    			'序号'=>"code",
    			'楼盘名称'=>"name",
    			'地区'=>"m_area",
    			'具体位置'=>"position",
    			'规划入住户数'=>"households",
    			'层数'=>"floor_num",
    			'入住率'=>"occ_rate",
    			'单元数'=>"unit_rate",
    			'禁投放行业'=>"put_trade",
    			'类型'=>"type",
    			'等级'=>"grade",
    			'交房年份'=>"deliver_year",
    			'发送物业审核'=>"is_check_out",
    			'门禁点位数'=>"count1",
    			'地面电梯前室点位数'=>"count2",
    			'地下电梯前室点位数'=>"count3",
    			'合计点位数'=>"count"
    	);
    	 
    
    	$i = 0;
    	foreach($table_header as  $k=>$v){
    		$cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
    		$this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
    		$i++;
    	}
    	
    	$tmpList = $this->Mhouses->get_lists('*',$where);
    	
    	if(count($tmpList) > 0) {
    		$houses_id_arr = array_column($tmpList, 'id');
    		$where_houses['in']['houses_id'] = $houses_id_arr;
    		 
    		$houses_dis_list = $this->Mhouses_points->get_distinct_lists($where_houses);
    		$houses_unit_list = $this->Mhouses_points->get_lists('houses_id,area_id,ban,unit', $where_houses, array(), 0,0,  ['houses_id','area_id','ban','unit']);
    		 
    		foreach($tmpList as $k => &$v) {
    			$v['type'] = '';
    			$v['floor_num'] = '';
    			$v['unit_rate'] = 0;
    			foreach ($houses_dis_list as $k1 => $v1) {
    				if($v['id'] == $v1['houses_id']) {
    					if(isset($data['houses_type'][$v1['houses_type']])) {
    						$v['type'] .= ",".$data['houses_type'][$v1['houses_type']];
    						$v['floor_num'] .= ",".$v1['floor_num'];
    					}
    				}
    			}
    	
    			foreach ($houses_unit_list as $k2 => $v2) {
    				if($v['id'] == $v2['houses_id']) {
    					$v['unit_rate'] += 1;
    				}
    			}
    	
    	
    			$v['count_1'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 1, 'is_del' => 0]);
    			$v['count_2'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 2, 'is_del' => 0]);
    			$v['count_3'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 3, 'is_del' => 0]);
    			$v['count_4'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'is_del' => 0]);
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
    
    			if($v1 == "m_area") {
    				$value = $val['province'].'-'.$val['city'].'-'.$val['area'];
    			}
    
    			if($v1 == "position") {
    				$value = $val['position'];
    			}
    
    			if($v1 == "households") {
    				$value = $val['households'];
    			}
    
    			if($v1 == "floor_num") {
    				$value = $val['floor_num'];
    			}
    
    			if($v1 == "occ_rate") {
    				$value = $val['occ_rate'];
    			}
    
    			if($v1 == "unit_rate") {
    				$value = $val['unit_rate'];
    			}
    
    			if($v1 == "put_trade") {
    				if(isset($val['put_trade'])) {
    					$put_trade_arr = explode(',', $val['put_trade']);
    					$put_trade_str = '';
    					foreach ($put_trade_arr as $k2 => $v2) {
    						if(isset($data['put_trade'][$v2])) {
    							$put_trade_str .= $data['put_trade'][$v2].',';
    						}
    					}
    					$value = $put_trade_str;
    				}
    			}
    			
    			if($v1 == "type") {
    				$value = $val['type'];
    			}
    			
    			if($v1 == "grade") {
    			    $houses_grade = C('public.houses_grade');
    				if(isset($houses_grade[$val['grade']])) {
    					$value = $houses_grade[$val['grade']];
    				}
    			}
    			
    			if($v1 == "deliver_year") {
    				if($val['deliver_year'] == '0000') {
    					$value = '';
    				}else {
    					$value = $val['deliver_year'];
    				}
    			}
    			
    			if($v1 == "is_check_out") {
    				$value = $val['is_check_out'];
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
    	header('Content-Disposition: attachment;filename=社区楼盘表.xls');
    	header('Cache-Control: max-age=0');
    
    	$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
    	$objWriter->save('php://output');
    }

}
<?php
/**
 * 楼盘管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Houses extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
        	'Model_area' => 'Marea',
            'Model_houses_area' => 'Harea',
        	'Model_houses_points' => 'Mhouses_points',
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_list';
        
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
        if ($this->input->get('province')) $where['province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['area'] = $this->input->get('area');

        if ($this->input->get('type') != 'all' && !empty($this->input->get('type'))) {
            $where['type'] = $this->input->get('type') ? $this->input->get('type') : 1;
        }
        
        if ($this->input->get('grade') != 'all' && !empty($this->input->get('grade'))) {
        	$where['grade'] = $this->input->get('grade') ? $this->input->get('grade') : 1;
        }

        $data['name'] = $this->input->get('name');
        $data['type'] = $this->input->get('type');
        $data['grade'] = $this->input->get('grade');
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['is_check_out'] = $this->input->get('is_check_out');
        
        $tmpList = $this->Mhouses->get_lists('*',$where,[],$size,($page-1)*$size);
        
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
        		
        		foreach ($install as $k3 => $v3){
        		    if($v['install'] == $k3){
        		        $v['install'] = $v3;
        		    }
        		}
        		
        		$v['count_1'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 1, 'is_del' => 0]);
        		$v['count_2'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 2, 'is_del' => 0]);
        		$v['count_3'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'addr' => 3, 'is_del' => 0]);
        		$v['count_4'] = $this->Mhouses_points->get_one('count(0) as count', ['houses_id' => $v['id'], 'is_del' => 0]);
        	}
        	
        	
        }
        $data['hlist'] = $this->Mhouses->get_lists();
        $data['list'] = $tmpList;
        
        $data_count = $this->Mhouses->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/houses";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        //$data['houses_type'] = C("public.houses_type");
        
        $data['houses_grade'] = C("public.houses_grade");
        
        $this->load->view("houses/index",$data);
    }

    /*
     * 添加
     * 867332352@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("楼盘管理","添加楼盘");

        if(IS_POST){
            $post = $this->input->post();
            unset($post['sub_put_trade']);
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $result = $this->Mhouses->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增楼盘：".$post['name']);
                $this->success("添加成功","/houses");
            }else{
                $this->error("添加失败");
            }

        }

        //获取省级
        //$data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        //城市
        //$data['city'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35560));
        //地区
        //$data['area'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35561));
        
        $data['houses_type'] = C("public.houses_type");
        
        $data['houses_grade'] = C("public.houses_grade");
        
        $data['install'] = C('install.install');

        $this->load->view("houses/add",$data);
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
            unset($post['sub_put_trade']);
            $oldname = $this->Mhouses->get_one('name',['id' => $id]);
            $this->Harea->update_info(['houses_name' => $post['name']], ['houses_name' => $oldname['name']]);
            $this->Mhouses_points->update_info(['houses_name' => $post['name']], ['houses_name' => $oldname['name']]);
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑楼盘：".$post['name']);
                $this->success("编辑成功","/houses");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        //获取省级
        $data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        //城市
        $data['city'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35560));
        //地区
        $data['area'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35561));
        
        $data['houses_type'] = C("public.houses_type");
        
        $data['houses_grade'] = C("public.houses_grade");
        
        $data['install'] = C('install.install');
        
        $this->load->view("houses/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mhouses->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除楼盘：".$name['name']);
            $this->success("删除成功！","/houses");

        }else{
            $this->error("删除失败！");
        }
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
    
    public function load_accept(){
        $houses_id = $this->input->get('houses_id');
        if(!$houses_id){
            $this->error("请选择楼盘");
        }
        $list = $this->Mhouses_points->get_points_lists(['A.houses_id' => $houses_id]);
        $total = count($list);
        $indoor = 0;
        $outdoor = 0;
        foreach ($list as $k => $v){
            $temp = substr($v['code'], 0,1);
            //1,2室内，3，5室外
            switch ($temp){
                case 1:
                    $indoor++;
                    break;
                case 2:
                    $indoor++;
                    break;
                case 3:
                    $outdoor++;
                    break;
                case 5:
                    $outdoor++;
                    break;
            }
        }
        //加载phpexcel
        $this->load->library("PHPExcel");
        
        $table_header =  array(
            '点位编号'=>"code",
            '所属楼盘'=>"houses_name",
            '所属组团'=>"houses_area_name",
            '楼栋'=>"ban",
            '单元'=>"unit",
            "楼层" =>"floor",
            '位置'=>"addr",
            '室内机①室外机②' => "out_in",
            "验收结果√或×" => 'res',
            "验收备注" => 'remark'
        );
        //设置行高
        $this->phpexcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
        
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        
        $housesName = $list[0]['houses_name'];
        //绘制第一行,合并单元格A1-J1
        $this->phpexcel->getActiveSheet(0)->mergeCells("A1:J1"); 
        //设置第一行内容
        $cell = PHPExcel_Cell::stringFromColumnIndex(0).'1';
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $housesName);
        //设置第二行内容， 合并B2:H2,I2:J2
        $cell = PHPExcel_Cell::stringFromColumnIndex(0).'2';
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '工程部');
        $this->phpexcel->getActiveSheet(0)->mergeCells("B2:H2"); 
        $cell = PHPExcel_Cell::stringFromColumnIndex(1).'2';
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '验收人/日期：'); 
        $cell = PHPExcel_Cell::stringFromColumnIndex(8).'2';
        $this->phpexcel->getActiveSheet(0)->mergeCells("I2:J2"); 
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '验收表审核/日期：');
        
        //设置第三行内容， 合并B3:H3,I3:J3
        $cell = PHPExcel_Cell::stringFromColumnIndex(0).'3';
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '客服部');
        $this->phpexcel->getActiveSheet(0)->mergeCells("B3:H3");
        $cell = PHPExcel_Cell::stringFromColumnIndex(1).'3';
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '媒介录入/日期：'); 
        $cell = PHPExcel_Cell::stringFromColumnIndex(8).'3';
        $this->phpexcel->getActiveSheet(0)->mergeCells("I3:J3"); 
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, '客服主任审核/日期：');
        
        $this->phpexcel->getActiveSheet(0)->getRowDimension(4)->setRowHeight(40);
        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'4';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
        $h = 5;
        //填充数据
        foreach ($list as $k => $v){
            $j = 0;
            foreach ($table_header as $key => $val){
                $value = "";
                if(isset($v[$val]) && !empty($v[$val])){
                    $value = $v[$val];
                    if($val == "addr"){
                        switch ($value){
                            case 1:
                                $value = "门禁";
                                break;
                            case 2:
                                $value = "地面电梯前室";
                                break;
                            case 3:
                                $value = "地下电梯前室";
                                break;
                            default:
                                $value = "";
                        }
                    }
                }
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
                $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $value);
            }
            $h++;
        }
        
        //绘制表尾
        //合并单元格A$h:B$h
        $this->phpexcel->getActiveSheet(0)->mergeCells("A{$h}:B{$h}");
        $cell = PHPExcel_Cell::stringFromColumnIndex(0).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "合计");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("C{$h}:E{$h}");
        //填充安装报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(2).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "安装报备总数：{$total}" ."个");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("F{$h}:H{$h}");
        //室内报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(5).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "室内报备总数：".$indoor."个");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("I{$h}:J{$h}");
        //室外报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(8).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "室外报备总数：".$outdoor."个");
        
        $h++;
        $this->phpexcel->getActiveSheet(0)->mergeCells("A{$h}:B{$h}");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("C{$h}:E{$h}");
        //填充安装报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(2).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "实装总数：");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("F{$h}:H{$h}");
        //室内报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(5).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "室内实装总数：");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("I{$h}:J{$h}");
        //室外报备总数：
        $cell = PHPExcel_Cell::stringFromColumnIndex(8).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "室外实装总数：");
        
        $this->phpexcel->getActiveSheet(0)->mergeCells('A'.($h-1).':A'.($h));
        $h++;
        
        $this->phpexcel->getActiveSheet(0)->mergeCells("A{$h}:B{$h}"); 
        $this->phpexcel->getActiveSheet(0)->mergeCells("C{$h}:J{$h}"); 
        $cell = PHPExcel_Cell::stringFromColumnIndex(0).$h;
        $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, "差额说明:");
        $this->phpexcel->getActiveSheet(0)->getRowDimension($h)->setRowHeight(40);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$housesName.'验收表.xls');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
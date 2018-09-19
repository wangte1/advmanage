<?php
/**
 * 点位管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housespoints extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_points' => 'Mhouses_points',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_area' => 'Mhouses_area',
        	'Model_area' => 'Marea',
        	'Model_houses_points_format' => 'Mhouses_points_format',
        	'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_houses_diy_area' => 'Mhouses_diy_area',
            'Model_houses_tour_points' => 'Mhouses_tour_points'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_points_list';
        $this->data['point_addr'] = C('housespoint.point_addr'); //点位位置
        $this->data['order_type_text'] = C('order.houses_order_type'); //点位类型
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
        $where['A.is_del'] = 0;
        $where['A.id>='] = 1;
        if ($this->input->get('type_id')) $where['A.type_id'] = $this->input->get('type_id');
        if ($this->input->get('province')) $where['B.province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['B.city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['B.area'] = $this->input->get('area');
        if ($this->input->get('houses_id')) $where['A.houses_id'] = $this->input->get('houses_id');
        if ($this->input->get('area_id')) $where['A.area_id'] = $this->input->get('area_id');
        if ($this->input->get('ban')) $where['A.ban'] = $this->input->get('ban');
        if ($this->input->get('unit')) $where['A.unit'] = $this->input->get('unit');
        if ($this->input->get('floor')) $where['A.floor'] = $this->input->get('floor');
        if ($this->input->get('addr')) $where['A.addr'] = $this->input->get('addr');
        if ($this->input->get('point_status')) $where['A.point_status'] = $this->input->get('point_status');
        if ($this->input->get('customer_id')) $where['like']['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('code')) $where['like']['A.code'] = $this->input->get('code');
        
        $data['point_status'] = $this->input->get('point_status');
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['area_id'] = $this->input->get('area_id');
        $data['ban'] = $this->input->get('ban');
        $data['unit'] = $this->input->get('unit');
        $data['floor'] = $this->input->get('floor');
        $data['addr'] = $this->input->get('addr');
        $data['type_id'] = $this->input->get('type_id');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['houses_id'] = $this->input->get('houses_id');
        if($data['houses_id']) $data['area_list'] = $this->get_area_info($data['houses_id']);
        $data['point_code'] = $this->input->get('code');
        $data['tab'] = 'basic';//默认显示基本信息tab
        
        $data['list'] = $this->Mhouses_points->get_points_lists($where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_points->get_points_count($where);
        $data_count = $data_count[0]['count'];
        
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        
        //提取本次的点位
        $point_ids = array_column($data['list'], 'id');
        $tour_ids = array_column($data['list'], "tour_id");
        if(!empty($tour_ids)){
            $tourList = $this->Mhouses_tour_points->get_lists("id,img", ['in' => ['id' => $tour_ids]]);
        }
        //查询报修表是否存在改点位的已报损但可以上画的点位
        $report_list = [];
        if($point_ids){
            $report_list = $this->Mhouses_points_report->get_lists('point_id', ['in' => ['point_id' => $point_ids], 'repair_time' => 0]);
            if($data['list']){
                foreach ($data['list'] as $k => $v){
                    $data['list'][$k]['can_report'] = 1;
                }
            }
        }
        
        if($report_list){
            foreach ($data['list'] as $k => $v){
                foreach ($report_list as $key => $val){
                    if($v['id'] == $val['point_id']){
                        $data['list'][$k]['can_report'] = 0;
                    }
                }
            }
        }
        
        if($tourList){
            foreach ($data['list'] as $k => $v){
                $data['list'][$k]['tour_img'] = '';
                foreach ($tourList as $k2 => $v2){
                    if($v['tour_id'] == $v2['id']){
                        $data['list'][$k]['tour_img'] = $v2['img'];
                    }
                }
            }
        }
        //var_dump($report_list, $this->db->last_query());exit;
        $data['hlist'] = $this->Mhouses->get_lists('id,name',['is_del'=>0]);
        $data['alist'] = $this->Mhouses_area->get_lists('id,name',['is_del'=>0]);
        $data['tlist'] = $this->Mhouses_points_format->get_lists('id,type',['is_del'=>0]);
        
        //获取分页
        $pageconfig['base_url'] = "/housespoints";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['customers'] = $this->Mhouses_customers->get_lists("id,name", array('is_del' => 0)); //客户列表
        $data['customer_name'] = array_column($data['customers'], 'name', 'id');
        $data['houses_type'] = C("public.houses_type");
        
        $where1 = [];
        if ($this->input->get('area_id')) $where1['area_id'] = $this->input->get('area_id');
        if ($this->input->get('houses_id')) {
        	$where1['houses_id'] = $this->input->get('houses_id');
        	$data['buf'] = $this->Mhouses_points->get_lists('ban,unit,floor',$where1,$order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array('ban','unit','floor'));
        }
         
        //处理权限
        $data['power'] = explode(',', $data['userInfo']['purview_ids']);
        $this->load->view("housespoints/index",$data);
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
            $count = $this->Mhouses_points->count(['code' => $post['code'], 'is_del' => 0]);
            if($count){
                $this->error("编号已存在，请核实后提交");
            }
            
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");
            if($post['addr'] == 0){
                $this->error("添加失败，请填写点位位置");
            }
            //临时匹配导入数据
            $tmpArr = $this->Mhouses->get_one("name",array("id" =>$post['houses_id']));
            $post['houses_name'] = $tmpArr['name'];
            $tmpArr = $this->Mhouses_area->get_one("name",array("id" => $post['area_id']));
            if($tmpArr) $post['area_name'] = $tmpArr['name'];
            $result = $this->Mhouses_points->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"社区新增点位：".$post['code']);
                $this->success("添加成功","/housespoints");
            }else{
                $this->error("添加失败");
            }

        }

        $data['houses_type'] = C("public.houses_type");
        
       	//$data['hlist'] = $this->get_houses_info("贵州省", "贵阳市", "南明区");
        $data['hlist'] = $this->Mhouses->get_lists('id,name',['is_del'=>0]);
       	
       	$data['tlist'] = $this->Mhouses_points_format->get_lists('id,type',['is_del'=>0]);
       	
        $this->load->view("housespoints/add",$data);
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
            if(isset($post['cover_img'])){
            	$post['images'] = implode(';', $post['cover_img']);
            	unset($post['cover_img']);
            }
            //判断点位编号是否重复
            $info = $this->Mhouses_points->get_one('code,point_status', ['id' => $id]);
            if($info['code'] != $post['code']){
                $count = $this->Mhouses_points->count(['code' => $post['code'], 'is_del' => 0]);
                if($count){
                    $this->error("编号已存在，请核实后提交");
                }
            }
            if($post['addr'] == 0){
                $this->error("添加失败，请填写点位位置");
            }
            $post['update_time'] = date("Y-m-d H:i:s");
            //查询楼盘和组团数据
            $post['houses_name'] = $this->Mhouses->get_one('name',['id' => $post['houses_id']])['name'];
            $post['area_name'] = $this->Mhouses_area->get_one('name',['id' => $post['area_id']])['name'];
            $result = $this->Mhouses_points->update_info($post,array("id"=>$id));
            if($result){
                if($info['point_status'] != 4){
                    //更新点位状态
                    $_where1['id'] = $id;
                    $_where1['field']['`ad_num`'] = '`ad_use_num` + `lock_num`';
                    if($info['point_status'] != 4){
                        $this->Mhouses_points->update_info(['point_status' => 3], $_where1);
                    }
                    $_where2['id'] = $id;
                    $_where2['field']['`ad_num` >'] = '`ad_use_num` + `lock_num`';
                    if($info['point_status'] != 4){
                        $this->Mhouses_points->update_info(['point_status' => 1], $_where2);
                    }
                }
                $this->write_log($data['userInfo']['id'],2,"社区编辑点位：".$post['code']);
                $this->success("编辑成功",$_COOKIE["lasturl"]);
            }else{
                $this->error("编辑失败");
            }

        }else{
            $this->input->set_cookie("lasturl",$_SERVER['HTTP_REFERER'],60);
        }
        
        $info = $this->Mhouses_points->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        
        $tmplist = $this->Mhouses->get_lists('id,name,province,city,area', ['id' => $info['houses_id']]);
        
        if(count($tmplist) > 0 ) {
        	//var_dump($tmplist[0]);
        	$data['d_houses'] = $tmplist[0];
        	$data['hlist'] = $this->get_houses_info($tmplist[0]['province'], $tmplist[0]['city'], $tmplist[0]['area']);
        	$data['alist'] = $this->get_area_info($tmplist[0]['id']);
        }
        
        $data['tlist'] = $this->Mhouses_points_format->get_lists('id,type', ['is_del'=>0]);
        
        if ($info['houses_id']) $where['houses_id'] = $info['houses_id'];
        if ($info['area_id']) $where['area_id'] = $info['area_id'];
         
        $data['buf'] = $this->Mhouses_points->get_lists('ban,unit,floor',$where,$order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array('ban','unit','floor'));

        $this->load->view("housespoints/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $code = $this->Mhouses_points->get_one("code",array("is_del"=>0,"id"=>$id));
        if(!$code){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_points->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 社区删除点位：".$code['code']);
            $this->success("删除成功！","/housespoints");

        }else{
            $this->error("删除失败！");
        }
    }
    
    public function partition(){
        $data = $this->data;
        //提取楼盘、组团 
        $where = ['is_del' => 0];
        $diy_area_id = (int) $this->input->get('diy_area_id');
        if($diy_area_id){
            $where['diy_area_id'] = $diy_area_id;
            $data['diy_area_id'] = $diy_area_id;
        }
        $group_by = ['houses_id', 'area_id'];
        $list = $this->Mhouses_points->get_lists('houses_id, houses_name, area_id, count(id) as num,area_name', $where, ['houses_id' => 'asc'], 0, 0, $group_by);
        //提取楼盘ids
        $listData = [];
        if($list){
            $houses_ids = array_column($list, 'houses_id');
            if($houses_ids){
                $houses_ids = array_unique($houses_ids);
                foreach ($houses_ids as $k => $v){
                    $listData[$k]['houses_id'] = $v;
                    $listData[$k]['areas'] = '';
                    $listData[$k]['houses_name'] = '';
                    $listData[$k]['area'] = [];
                }
            }
            //行政区域
            $areaList = $this->Mhouses->get_lists('id,city,area', ['in' => ['id' => $houses_ids]]);
            //组团
            $housesAreaList = $this->Mhouses_area->get_lists('id,name', ['is_del' => 0]);
            if($areaList){
                foreach ($listData as $k => $v){
                    foreach ($areaList as $key => $val){
                        if($v['houses_id'] == $val['id']){
                            $listData[$k]['areas'] = $val['city'].$val['area'];
                        }
                    }
                }
            }
            foreach ($list as $k => $v){
                foreach ($listData as $key => $val){
                    if($v['houses_id'] == $val['houses_id']){
                        //设置楼盘名称
                        if(empty($val['houses_name'])){
                            $listData[$k]['houses_name'] = $v['houses_name'];
                        }
                        if(!in_array($v['area_id'], $val['area'])){
                            $listData[$key]['area'][$k]['id'] = $v['area_id'];
                            $listData[$key]['area'][$k]['num'] = $v['num'];
                            if(empty($v['area_name'])){
                                $v['area_name'] = '无组团';
                            }
                            $listData[$key]['area'][$k]['area_name'] = $v['area_name'];
                            $listData[$key]['area'][$k]['diy_area_id'] = 0;
                        }
                    }
                }
            }
        }
        unset($list);
        
        //为解决数据不同步问题
        if($housesAreaList){
            foreach ($listData as $k => $v){
                if(count($v['area'])){
                    foreach ($v['area'] as $key => $val){
                        foreach ($housesAreaList as $keys => $vals){
                            if($val['id'] == $vals['id']){
                                $listData[$k]['area'][$key]['area_name'] = $vals['name'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        //查询已分配的情况
        $diyList = $this->Mhouses_diy_area->get_lists('*');
        
        if($diyList){
            foreach ($listData as $k => $v){
                foreach ($diyList as $k0 => $v0){
                    if($v['houses_id'] == $v0['houses_id']){
                        foreach ($v['area'] as $k1 => $v1){
                            if($v1['id'] == $v0['area_id']){
                                $listData[$k]['area'][$k1]['diy_area_id'] = $v0['diy_area_id'];
                            }
                        }
                    }
                }
            }
        }
        $data['list'] = $listData;
        $this->load->view("housespoints/partition", $data);
    }
    
    /**
     * 设定自定义区域
     */    
    public function set_diy_area(){
        if(IS_POST){
            $diy_area_id = $this->input->post('diy_area_id');
            $houses_id = $this->input->post('houses_id');
            $area_id = $this->input->post('area_id');
            $count = $this->Mhouses_diy_area->count(['houses_id' => $houses_id, 'area_id' => $area_id]);
            if($count){
                $res = $this->Mhouses_diy_area->update_info(['diy_area_id' => $diy_area_id], ['houses_id' => $houses_id, 'area_id' => $area_id]);
            }else{
                $add = [
                    'houses_id' => $houses_id,
                    'area_id' => $area_id,
                    'diy_area_id' => $diy_area_id,
                ];
                $res = $this->Mhouses_diy_area->create($add);
            }
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败']);
            }
            //批量更新点位
            $res = $this->Mhouses_points->update_info(['diy_area_id' => $diy_area_id], ['houses_id' => $houses_id, 'area_id' => $area_id]);
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '点位更新失败']);
            }
            $this->return_json(['code' => 1, 'msg' => '编辑成功']);
        }
    }
    
    
    /**
     * 点位报修
     */
    public function report(){
        $data = $this->data;
        $data['id'] = $this->input->get('id');
        $this->load->view("housespoints/report",$data);
    }
    
    /**
     * 提交报损
     */
    public function report_add(){
        $data = $this->data;
        if(IS_POST){
            $point_id = $this->input->post('id');
            $count = $this->Mhouses_points_report->count(['point_id' => $point_id, 'repair_time' => 0]);
            if($count) $this->return_json(['code' => 0, 'msg' => '请勿重复提交']);
            $report_img = $this->input->post('report_img');
            if(!$report_img) $report_img = '';
            $report = $this->input->post('report');
            $report_msg = $this->input->post('report_msg');
            $usable = $this->input->post('usable');
            $up = [
                'report_img' => $report_img,
                'point_id' => $point_id,
                'report' => implode(',', $report),
                'create_id' => $data['userInfo']['id'],
                'report_msg' => $report_msg,
                'create_time' => strtotime(date('Y-m-d')),
                'usable' => $usable
            ];
            $res = $this->Mhouses_points_report->create($up);
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败，请重试']);
            }
            if($usable == 0){
                $point_up = [
                    'point_status' => 4,
                    'update_time' => date('Y-m-d H:i:s'),
                    'update_user' =>$data['userInfo']['id']
                ];
                $res = $this->Mhouses_points->update_info($point_up, ['id' => $point_id]);
                if(!$res) $this->write_log($data['userInfo']['id'], 2, "已报损，但无法更新点位数据id:".$point_id."数据：".json_encode($point_up));
                $this->write_log($data['userInfo']['id'], 2, "已报损，并更新点位id:{$point_id}数据状态为4");
            }
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
    }
    
    /**
     * 修复报损
     */
    public function reported(){
        if(IS_POST){
            $id = $this->input->post('id');
            $info = $this->Mhouses_points->get_one('*', ['id' => $id]);
            $post['point_status'] = 3;
            if($info['ad_num'] > $info['ad_use_num'] + $info['lock_num']){
                $post['point_status'] = 1;
            }
            $post['destroy'] = '';
            $post['destroy_img'] = '';
            $res = $this->Mhouses_points->update_info($post, ['id' => $id]);
            if(!$res) $this->return_json(['code' => 0, 'msg' => '操作失败，请重试！']);
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
    }
    
    /*
     * ajax获取楼盘信息
     */
    public function ajax_houses_info() {
    	if ($this->input->post('province')) $where['province'] = $this->input->post('province');
    	if ($this->input->post('city')) $where['city'] = $this->input->post('city');
    	if ($this->input->post('area')) $where['area'] = $this->input->post('area');
    	$list = $this->get_houses_info($where['province'], $where['city'], $where['area']);
    	$this->return_json($list);
    }
    
    /*
     * ajax获取楼盘区域信息
     */
    public function ajax_area_info() {
    	if ($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	$list = $this->get_area_info($where['houses_id']);
    	$this->return_json($list);
    }
    
    /*
     * ajax获取楼栋、单元、楼层信息
     */
    public function get_buf_info() {
    	if ($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	if ($this->input->post('area_id')) $where['area_id'] = $this->input->post('area_id');
    	
    	$list = $this->Mhouses_points->get_lists('ban,unit,floor',$where,$order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array('ban','unit','floor'));
    	
    	$this->return_json(['code' => 1, 'list' => $list]);
    }
    
    /*
     * 获取楼盘信息
     */
    public function get_houses_info($province = '', $city = '', $area = '') {
    	$where['is_del'] = 0;
    	if ($province) $where['province'] = $province;
    	if ($city) $where['city'] = $city;
    	if ($area) $where['area'] = $area;
    	$list = $this->Mhouses->get_lists('id,name,province,city,area',$where);
    	return $list;
    }
    
    /*
     * 获取楼盘区域信息
     */
    public function get_area_info($houses_id = 0) {
    	
    	$where['is_del'] = 0;
    	if ($houses_id) $where['houses_id'] = $houses_id;
    	$list = $this->Mhouses_area->get_lists('id,name',$where);
    	return $list;
    	
    }
    
    /*
     * 获取楼盘区域信息
     * @author yonghua 
     */
    public function get_area() {
        
        $where['is_del'] = 0;
        $houses_id = (int) $this->input->post('houses_id');
        if ($houses_id) $where['houses_id'] = $houses_id;
        $list = $this->Mhouses_area->get_lists('id,name',$where);
        if(!$list) $this->return_json(['code' => 0]);
        $this->return_json(['code' => 1, 'list' => $list]);
        
    }
    
    /*
     * 导出数据
     * 1034487709@qq.com
     */
    public function out_excel(){
        set_time_limit(0);

        if ($this->input->get('type_id')) $where['A.type_id'] = $this->input->get('type_id');
        if ($this->input->get('province')) $where['B.province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['B.city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['B.area'] = $this->input->get('area');
        if ($this->input->get('houses_id')) $where['A.houses_id'] = $this->input->get('houses_id');
        if ($this->input->get('area_id')) $where['A.area_id'] = $this->input->get('area_id');
        if ($this->input->get('ban')) $where['A.ban'] = $this->input->get('ban');
        if ($this->input->get('unit')) $where['A.unit'] = $this->input->get('unit');
        if ($this->input->get('floor')) $where['A.floor'] = $this->input->get('floor');
        if ($this->input->get('addr')) $where['A.addr'] = $this->input->get('addr');
        if ($this->input->get('point_status')) $where['A.point_status'] = $this->input->get('point_status');
        if ($this->input->get('customer_id')) $where['like']['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('code')) $where['like']['A.code'] = $this->input->get('code');
    
    	//加载phpexcel
    	$this->load->library("PHPExcel");
    
    	$table_header =  array(
    		'点位编号'=>"code",
    		'行政区域'=>"admin_area",
    		'所属楼盘'=>"houses_name",
    	    '楼盘入住率' => 'occ_rate',
    	    '楼盘等级' => 'grade',
    	    "楼层高" => "floor_num",
    		'所属组团'=>"houses_area_name",
    		'楼栋'=>"ban",
    		'单元'=>"unit",
    		'楼层'=>"floor",
    		'位置'=>"addr",
    	    '状态'=> 'point_status',
    	    '占用客户' => 'customer_id'
    	);
    	
    
    	$i = 0;
    	foreach($table_header as  $k=>$v){
    		$cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
    		$this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
    		$i++;
    	}
    	
    	$list = $this->Mhouses_points->get_points_lists($where);
    	//提取楼盘ids
    	$houses_ids = array_unique(array_column($list, "houses_id"));
    	//获取楼盘信息
    	$houses_list = $this->Mhouses->get_lists('id, floor_num', ['in' => ['id' =>$houses_ids]]);
    	if($houses_list){
    	    foreach ($list as $k => $v){
    	        $list[$k]['floor_num'] = 0;
    	        foreach ($houses_list as $key => $val){
    	            if($v['houses_id'] == $val['id']){
    	                $list[$k]['floor_num'] = $val['floor_num'];
    	            }
    	        }
    	    }
    	}
    	$customerList = $this->Mhouses_customers->get_lists('id,name', ['is_del' => 0]);
    	$h = 2;
    	$addrList = C('housespoint.point_addr');
    	$grade = C('public.houses_grade');
    	foreach($list as $key=> &$val){
    	    foreach ($addrList as $k => $v){
    	        if($val['addr'] == $k) $val['addr'] = $v;
    	    }
    	    
    	    //拼接占用客户
    	    if(!empty($val['customer_id'] && $val['customer_id'])){
    	        $thisCustomer = explode(',', $val['customer_id']);
    	        $val['customer_id'] = '';
    	        foreach ($thisCustomer as $k => $v){
    	            if($v){
        	            foreach ($customerList as $k1 => $v1){
        	                if($v == $v1['id']){
        	                    $val['customer_id'] .= $v1['name'] .',';
        	                }
        	            }
    	            }
    	        }
    	    }
    	    
    	    if($val['point_status'] == 1){
    	        $val['point_status'] = '空闲';
    	    }else if($val['point_status'] == 3){
    	        $val['point_status'] = '占用';
    	    }
    	    
    		$j = 0;
    		foreach($table_header as $k => $v){
    		$cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
    
    		$value = $val[$v];
    		
    		if($v == "admin_area") {
    			$value = $val['province'].'-'.$val['city'].'-'.$val['area'];
    		}
    		
    		if($v == "grade"){
    		    $value = $grade[$val['grade']];
    		}
    		
    		if($v == "occ_rate"){
    		    $value = sprintf('%.2f', 100*$val['occ_rate']);
    		}
    		
    		if($v == "customer_id") {
    		    $value = '';
    		    if($val['customer_id']){
    		        $value = $val['customer_id'];
    		    }
    		}
    		$this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value.' ');
    		}
    		$h++;
    	}
    	
    	$this->phpexcel->setActiveSheetIndex(0);
    	// 输出
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename=社区点位表.xls');
    	header('Cache-Control: max-age=0');
    
    	$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
    	$objWriter->save('php://output');
    }

}
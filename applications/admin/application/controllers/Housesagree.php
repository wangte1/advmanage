<?php
/**
 * 合同控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesagree extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
        	'Model_houses_agree' => 'Mhouses_agree'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_agree_list';
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $install = C('install.install');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where = ['is_del' => 0];
        if($this->input->get('doc_num')){
            $where['doc_num'] = $this->input->get('doc_num');
            $data['doc_num'] = $this->input->get('doc_num');
        }
        if($this->input->get('pm_company')){
            $where['like']['pm_company'] = $this->input->get('pm_company');
            $data['pm_company'] = $this->input->get('pm_company');
        }
        if($this->input->get('develer')){
            $where['like']['develer'] = $this->input->get('develer');
            $data['develer'] = $this->input->get('develer');
        }
        if($this->input->get('agree_start_date')){
            $where['agree_start_date'] = $this->input->get('agree_start_date');
            $data['agree_start_date'] = $this->input->get('agree_start_date');
        }
        if($this->input->get('agree_end_date')){
            $where['agree_end_date'] = $this->input->get('agree_end_date');
            $data['agree_end_date'] = $this->input->get('agree_end_date');
        }
        $list = $this->Mhouses_agree->get_lists('*',$where,[],$size,($page-1)*$size);
        $data['list'] = [];
        $data['hlist'] = [];
        $data['agree'] = C("agree");
        if($list){
            $data['list'] = $list;
            $admin = $this->Madmins->get_lists('id,fullname',['is_del' => 1]);
            $houses = $this->Mhouses->get_lists('id,name,agree_id', ['is_del' => 0]);
            if($admin){
                foreach ($list as $k => $v){
                    $data['list'][$k]['create_user_name'] = "";
                    foreach ($admin as $key => $val){
                        if($val['id'] == $v['create_user']){
                            $data['list'][$k]['create_user_name'] = $val['fullname'];break;
                        }
                    }
                    foreach ($data['agree']['pay_method'] as $k1 => $v1){
                        if($v['pay_method'] == $k1){
                            $data['list'][$k]['pay_method'] = $v1;
                        }
                    }
                    foreach ($data['agree']['invoice_type'] as $k2 => $v2){
                        if($v['invoice_type'] == $k2){
                            $data['list'][$k]['invoice_type'] = $v2;
                        }
                    }
                }
            }
            if($houses){
                $data['hlist'] = $houses;
                foreach ($list as $k => $v){
                    $data['list'][$k]['house_list'] = "";
                    foreach ($houses as $key => $val){
                        if($v['id'] == $val['agree_id']){
                            $data['list'][$k]['house_list'] .= $val['name']."、";
                        }
                    }
                }
            }
//             foreach ($data['list'] as $k => $v){
//                 foreach ($data['agree']['pay_method'] as $k1 => $v1){
//                     if($v['pay_method'] == $k){
//                         $data['list'][$k]['pay_method'] = $v1;
//                         return ;
//                     }
//                 }
//                 foreach ($)
//             }
            
            $data_count = $this->Mhouses_agree->count($where);
            $data['data_count'] = $data_count;
            //获取分页
            $pageconfig['base_url'] = "/housesagree";
            $pageconfig['total_rows'] = $data_count;
            $this->pagination->initialize($pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        
        $this->load->view('houses/agree', $data);
    }
    
    public function add(){
        $data = $this->data;
        $data['title'] = array("合同管理","添加合同");
        $data['agree'] = C("agree");
        
        $houses = $this->Mhouses->get_lists('id,name,agree_id', ['is_del' => 0]);
        $data['hlist'] = $houses;
        if(IS_POST){
            $post = $this->input->post();
            if(!isset($post['housesarr'])){
                $this->error('添加失败，请选择签约的楼盘');
            }
            $housesarr = $post['housesarr'];
            unset($post['housesarr']);
            $post['create_user'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");
            
            $result = $this->Mhouses_agree->create($post);
            if($result){
                $ret = $this->Mhouses->update_info(['agree_id' => $result], ['in' => ['id'=> $housesarr]]);
                if(!$ret){
                    $this->error("添加失败");
                }
                $this->write_log($data['userInfo']['id'],1,"新增合同：".$post['name']);
                $this->success("添加成功","/housesagree");
            }else{
                $this->error("添加失败");
            }
            
        }
        
        $this->load->view('houses/agreeadd',$data);
    }
    
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("合同管理","编辑合同");
        $data['agree'] = C("agree");
        $houses = $this->Mhouses->get_lists('id,name,agree_id', ['is_del' => 0]);
        $data['hlist'] = $houses;
        if(IS_POST){
            $post = $this->input->post();
            if(!isset($post['housesarr'])){
                $this->error('添加失败，请选择签约的楼盘');
            }
            $housesarr = $post['housesarr'];
            unset($post['housesarr']);
            $post['create_user'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses_agree->update_info($post,['id' => $id]);
            if($result){
                $ret = $this->Mhouses->update_info(['agree_id' => $result],['in' => ['id' => $housesarr]]);
                if(!$ret){
                    $this->error('添加失败');
                }
                $this->write_log($data['userInfo']['id'],2,"编辑合同：".$post['name']);
                $this->success("编辑成功","/housesagree");
            }else{
                $this->error("编辑失败");
            }
            
        }
        $info = $this->Mhouses_agree->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        $this->load->view("houses/agreeedit",$data);
    }
    
    /**
     * 删除合同
     */
    public function del(){
        $id = (int) $this->input->get('id');
        $res = $this->Mhouses_agree->update_info(['is_del' => 1], ['id' => $id]);
        if(!$res) $this->return_json(['code' => 0, 'msg' => "删除失败"]);
        $this->return_json(['code' => 1, 'msg' => "操作成功"]);
    }
    public function out_excel(){
        $data = $this->data;
        $where['is_del'] = 0;
        //if ($this->input->get('name')) $where['id'] = $this->input->get('name');
        //print_r($where);exit;
        //加载phpexcel
        $this->load->library("PHPExcel");
        
        $table_header =  array(
            'ID'=>"id",
            '存档编号'=>"doc_num",
            '物业公司'=>"pm_company",
            '合同开始时间'=>"agree_start_date",
            '合同结束时间'=>"agree_end_date",
            '开发负责人'=>"develer",
            '物业负责人'=>"property_owner",
            '负责人职务'=>"principal_duty",
            '负责人电话'=>"principal_tel",
            '签约日期'=>"sign_date",
            '签约楼盘'=>"house_list",
            '合同金额'=>"agree_price",
            '支付方式'=>"pay_method",
            '已付金额'=>"paid_money",
            '开票类型'=>"invoice_type",
            '已收发票金额'=>"received_invoice",
            '递增方式'=>"incr_type",
            '咨询费'=>"consult_cost",
            '备注'=>"remak",
            '录入人'=>"create_user_name",
            '录入日期'=>"create_time"
        );
        
        
        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
        
        $tmpList = $this->Mhouses_agree->get_lists('*',$where);
        
        if(count($tmpList) > 0) {
            $admin = $this->Madmins->get_lists('id,fullname',['is_del' => 1]);
            $houses = $this->Mhouses->get_lists('id,name,agree_id', ['is_del' => 0]);
            if($admin){
                foreach ($tmpList as $k => $v){
                    $tmpList[$k]['create_user_name'] = "";
                    foreach ($admin as $key => $val){
                        if($val['id'] == $v['create_user']){
                            $tmpList[$k]['create_user_name'] = $val['fullname'];break;
                        }
                    }
                    foreach ($data['agree']['pay_method'] as $k1 => $v1){
                        if($v['pay_method'] == $k1){
                            $tmpList[$k]['pay_method'] = $v1;
                        }
                    }
                    
                    foreach ($data['agree']['invoice_type'] as $k2 => $v2){
                        if($v['invoice_type'] == $k2){
                            $tmpList[$k]['invoice_type'] = $v2;
                        }
                    }
                    
                    $tmpList[$k]['house_list'] = "";
                    foreach ($houses as $key => $val){
                        if($v['id'] == $val['agree_id']){
                            $tmpList[$k]['house_list'] .= $val['name']."、";
                        }
                    }
                }
            }
            
            
        }
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
        header('Content-Disposition: attachment;filename=社区合同表.xls');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
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
        $list = $this->Mhouses_agree->get_lists('*',$where,[],$size,($page-1)*$size);
        $data['list'] = [];
        $data['hlist'] = [];
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
    
    public function  add(){
        $data = $this->data;
        $data['title'] = array("合同管理","添加合同");
        
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
    
    /**
     * 删除合同
     */
    public function del(){
        $id = (int) $this->input->get('id');
        $res = $this->Mhouses_agree->update_info(['is_del' => 1], ['id' => $id]);
        if(!$res) $this->return_json(['code' => 0, 'msg' => "删除失败"]);
        $this->return_json(['code' => 1, 'msg' => "操作成功"]);
    }
}
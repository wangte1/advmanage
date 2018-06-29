<?php
/**
 * 操作日志
 * @author 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Operatelog extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_login_log' => 'Mlogin_log',
            'Model_admins_operate_log' => 'Moperate_log',
            'Model_admins' => 'Madmins'
        ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'operate_log';
    }


    /**
     * 右边内容
     */
    public function index() {
        $data = $this->data;
        $data['title'] = array("登录日志","日志列表");

        $start_time = $this->input->get("start_time");
        $end_time = $this->input->get("end_time");
        $operate_id = $this->input->get('operate_id');
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $where[1] = 1;
        if(!empty($start_time) && empty($end_time)){
            $where['login_time>='] = $start_time;
        }


        if(empty($start_time) && !empty($end_time)){
            $where['login_time<='] = $end_time." 23:59:59";

        }

        if(!empty($start_time) && !empty($end_time)){
            if(strtotime($start_time) > strtotime($end_time)){
                $this->error("开始时间不能大于结束时间");
            }
            $where['login_time>='] = $start_time;
            $where['login_time<='] = $end_time." 23:59:59";
        }
        if($operate_id){
            $where['admin_id'] = $operate_id;
            $data['admin_id2'] = $operate_id;
        }

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $this->pageconfig['per_page'];
        $data['log_list'] = $this->Mlogin_log->get_lists('*',$where,array("id"=>"desc"),$size,($page-1)*$size);
        $data['operate_id'] = $this->Madmins->get_lists();

        $data_count = $this->Mlogin_log->count($where);

        //获取分页
        $data['pagestr']  = "";
        if(! empty($data['log_list'])){
            $this->pageconfig['base_url'] = "/operatelog/index";
            $this->pageconfig['total_rows'] = $data_count;
            $this->pagination->initialize($this->pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        $data['active'] = 'login_log_list';
        $this->load->view("log/index",$data);

    }

    public function log(){
        $data = $this->data;
        $data['title'] = array("操作日志","日志列表");

        //获取当前房开商下面的用户
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $this->pageconfig['per_page'];
        $type = $this->input->get("type");
        $data['type'] = $type;
        $where[1] = 1;
        if($type){
            $where['operate_type'] = $type;

        }
        $start_time = $this->input->get("start_time");
        $end_time = $this->input->get("end_time");
        $operate_id = $this->input->get('operate_id');
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;

        if(!empty($start_time) && empty($end_time)){
            $where['create_time>='] = strtotime($start_time);
         }


        if(empty($start_time) && !empty($end_time)){
            $where['create_time<='] = strtotime($end_time." 23:59:59");

        }

        if(!empty($start_time) && !empty($end_time)){
            if(strtotime($start_time)>strtotime($end_time)){
                $this->error("开始时间不能大于结束时间");
            }
            $where['create_time>='] = strtotime($start_time);
            $where['create_time<='] = strtotime($end_time." 23:59:59");
        }
        if($operate_id) {
            $data['admin_id'] = $operate_id;
            $where['operate_id'] = $operate_id;
        }

        $data['log_list'] = $this->Moperate_log->get_lists('*',$where,array("id"=>"desc"),$size,($page-1)*$size);
        $data['operate_id'] = $this->Madmins->get_lists();
//        echo $this->db->last_query();
        $data_count = $this->Moperate_log->count($where);
      //  print_r($where);
        //获取分页
        $data['pagestr'] = "";
        if(! empty($data['log_list'])){
            $this->pageconfig['base_url'] = "/operatelog/log?type=".$type."&start_time=".$start_time."&end_time=".$end_time;
            $this->pageconfig['total_rows'] = $data_count;
            $this->pagination->initialize($this->pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        $data['log_type'] = C("public.log_type");
        $data['active'] = 'login_operate_list';

        $data["admin"] = array_column($this->Madmins->get_lists("id,name",[]),"name","id");
        
        $data["adminfullname"] = array_column($this->Madmins->get_lists("id,fullname",array()),"fullname","id");
        
        $this->load->view("log/operate",$data);
    }



}
?>

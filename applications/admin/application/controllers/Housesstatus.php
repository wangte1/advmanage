<?php
/**
 * 点位状态管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesstatus extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_points' => 'Mhouses_points',
         ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_status_list';
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $data['title'] = array("点位状态","饼形图");
        $data['count1'] = (int) $this->Mhouses_points->count(['point_status' => 1, 'is_del' =>0]);
        $data['count3'] = (int) $this->Mhouses_points->count(['point_status' => 3, 'is_del' =>0]);
        $data['count4'] = (int) $this->Mhouses_points->count(['point_status' => 4, 'is_del' =>0]);
        $data['sum'] = $data['count1'] + $data['count3'] + $data['count4'];
        //数量÷总数×100=百分比
        $data['count1'] = $data['count1'] / $data['sum'] * 100;
        $data['count3'] = $data['count3'] / $data['sum'] * 100;
        $data['count4'] = $data['count4'] / $data['sum'] * 100;

//         $page =  intval($this->input->get("per_page", true)) ?  : 1;
//         $size = $this->pageconfig['per_page'];
//         $where['is_del'] = 0;
        
//         $data['list'] = $this->Mhouses_app->get_lists('*',$where, array("id"=>"desc"), $size, ($page-1)*$size);
//         $data_count = $this->Mhouses_app->count($where);

//         //获取分页
//         $data['pagestr'] = "";
//         if(! empty($data['list'])){
//             $this->pageconfig['base_url'] = '/housesapp/index';
//             $this->pageconfig['total_rows'] = $data_count;
//             $this->pagination->initialize($this->pageconfig);
//             $data['pagestr'] = $this->pagination->create_links(); // 分页信息
//         }
//         $data['page'] = $page;
//         $data['data_count'] = $data_count;


        $this->load->view("housesstatus/index",$data);
    }

    /*
     * 添加
     * 1034487709@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("app管理","添加版本号");

        if(IS_POST){
            $post = $this->input->post();
            $post['create_time'] = date("Y-m-d H:i:s");

            $id = $this->Mhouses_app->create($post);
            if($id){
                $this->write_log($data['userInfo']['id'],1,"新增App版本号：".$post['version']);
                $this->success("添加成功","/housesapp");
            }else{
                $this->error("添加失败");
            }

        }

        $this->load->view("housesapp/add", $data);
    }

    /*
     * 编辑
     * 1034487709@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mhouses_app->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'], 2, "编辑社区客户：$id");
                $this->success("编辑成功","/housesapp");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_app->get_one("*", array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }

        $data['info'] = $info;

        $this->load->view("housesapp/add",$data);
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_app->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'], 3, " 删除app版本号：$id");
            $this->success("删除成功!","/housesapp");

        }else{
            $this->error("删除失败!");
        }
    }

}
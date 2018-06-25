<?php 
/**
* 个人设置控制器
* @author jianming@gz-zc.cn
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_admins_group' => 'Madmins_group',
            'Model_admins_purview' => 'Madmins_purview',
            "Model_allowance_place" =>"Mallowance_place",
            'Model_houses_user_diy_area' => 'Mhouses_user_diy_area'
        ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');

        $this->data['code'] = 'admin_user_manage';
        $this->data['active'] = 'admin_list';
    }
    

    /**
     * 管理员列表
     * 1034487709@qq.com
     */
    public function index() {
        $data = $this->data;
        $data['title'] = array("管理员列表","管理员列表");
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $this->pageconfig['per_page'];
        $where['is_del'] = 1;
        if ($this->input->get('name')) {
            $where['name'] = $this->input->get('name');
        }

        if ($this->input->get('fullname')) {
            $where['like']['fullname'] = $this->input->get('fullname');
        }
        
        if ($this->input->get('group_id')) {
            $where['group_id'] = $this->input->get('group_id');
        }
        $data['name'] = $this->input->get('name');
        $data['fullname'] = $this->input->get('fullname');
        $data['group_id'] = $this->input->get('group_id');

        $data['admin_list'] = $this->Madmins->get_lists('*',$where,array("id"=>"asc"),$size,($page-1)*$size);
        $data['admin_group_id'] = $this->Madmins_group->get_lists();

        $data_count = $this->Madmins->count($where);

        //获取分页
        if(! empty($data['admin_list'])){
            $this->pageconfig['base_url'] = "/admin/index";
            $this->pageconfig['total_rows'] = $data_count;
            $this->pagination->initialize($this->pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        $groups = $this->Madmins_group->get_lists("id,name",array('is_del'=>1));
        $data['groups'] = array_column($groups , 'name','id');

        $admins = $this->Madmins->get_admin_list();
        $data['admins'] = array_column($admins , 'fullname','id');
        $this->load->view("admin/index",$data);
    }

    /**
     * 添加管理员
     * 1034487709@qq.com
     */
    public function add(){
        $data = $this->data;
        if(IS_POST)
        {
            $count = $this->Madmins->count(array('is_del'=>1,'name'=>$this->input->post("name",true)));
            if($count){
                   $this->error("管理员已经成功存在");
            }
            
            $da = $this->input->post();
            $da['is_del'] = 1;
            $da['create_admin'] =$this->data['userInfo']['id'] ;
            $da['create_time'] = date("Y-m-d H:i:s");
            $da['password'] = md5(trim($this->input->post("password",true)));

            if(trim($this->input->post("password",true)) != trim($this->input->post("confirpassword",true))){
                $this->error("两次密码不一致");
            }
            unset($da['confirpassword']);
            
            $result_id =  $this->Madmins->create($da);
          
            if($result_id){
                $group = $this->Madmins_group->get_one('name',array('id'=>$_POST['group_id']));
                $this->write_log($data['userInfo']['id'],1,"添加管理员：".$_POST['name']."，权限：".$group['name']);
                $this->success("添加成功","/admin");
            }else{
               $this->error("添加管理员失败");
            }
        }

        $data = $this->data;
        $data['title'] = array("添加管理员","添加管理员");
       //获取角色
        $data['admin_group'] =  $this->Madmins_group->get_lists("id,name",array("is_del"=>1));

       
        $this->load->view("admin/add",$data);
    }
    

    

    /**
     * 删除管理员
     * 1034487709@qq.com
     */
    public function del($id = 0 )
    {
        $data = $this->data;
        #不能删除管理员
        if($id==1)
        {
            $this->return_json(array("code"=>1,"msg"=>"不能删除超级管理员。"));
        }
        $tmp = $this->Madmins->get_one('name',array('id'=>$id));
        #标记删除
        $this->write_log($data['userInfo']['id'],3,"删除用户：".$tmp['name']);
        $this->Madmins->update_info(array("is_del"=>2),array("id"=>$id)) ;
        //更新用户所关联的自定义区域
        $this->Mhouses_user_diy_area->update_info(['diy_area_id' => 0], ['user_id' => $id]);
        $this->success("操作成功","/admin");
    }

    /**
     * 编辑管理员
     * 1034487709@qq.com
     */
     public function edit($id = 0 )
    {

        if(IS_POST){
            
            $_POST['id'] = $id;
            //获取原来的group_id
            $old_group_id = $this->Madmins->get_one("group_id,password",array('id'=>$id));
            if($_POST['password']!='' && md5($_POST['password']) != $old_group_id['password'])
            {

                $_POST['password'] = md5($this->input->post("password",'trim'));
            }
            else
            {
                $_POST['password'] = $old_group_id['password'];
            }

            // 修改权限
            if($old_group_id['group_id'] != $_POST['group_id']){
                #获得用户权限
                $purview_ids = $this->Madmins->get_one('purview_ids',array('id'=>$id));#查询某个字段
                $_POST['purview_ids'] = $purview_ids['purview_ids'];

                #获得旧组权限
                $old_group_purview = $this->Madmins_group->get_one('purview_ids',array('id'=>$old_group_id['group_id']));

                #获得新组权限
                $new_group_purview = $this->Madmins_group->get_one('purview_ids',array('id'=>$_POST['group_id']));

                #删除旧组权限
                $_POST['purview_ids'] = $this->Madmins->del_purview($_POST['purview_ids'], $old_group_purview['purview_ids']);

                #添加新组权限
                if($new_group_purview['purview_ids'])
                {
                    $_POST['purview_ids'] .= ','.$new_group_purview['purview_ids'];
                }

            }
            unset($_POST['id']);
            $res = $this->Madmins->update_info($_POST, ['id' => $id]);
            if($res){
                $this->success("修改成功","/admin");
            }else{
                $this->error("编辑失败,请重新编辑");
            }


        }

        $data = $this->data;
        $data['title'] = array("管理员","编辑管理员");
        //获取角色
        $data['admin_group'] =  $this->Madmins_group->get_lists("id,name",array("is_del"=>1));
        //管理员信息
        $data['info'] = $this->Madmins->get_one("*",array("id"=>$id));

        $data['pid_list'] = $this->Madmins->get_lists('id,pid,group_id,fullname', ['is_del' => 1]);

        $this->load->view("admin/edit",$data);
    }

    /**
     * 校验管理员是否存在
     * 1034487709@qq.com
     */
    public function  check_admin(){
        if($this->input->is_ajax_request())
        {
            $name =  $this->input->post("name",true);
            $count = $this->Madmins->count(array('is_del'=>1,'name'=>$name));

            if($count){
                $this->return_json(array("code"=>0));
            }else{
                $this->return_json(array("code"=>1));
            }

        }
    }

    /**
     * 查看管理员
     * 1034487709@qq.com
     */
    public function read($id){
        $data = $this->data;
        $data['info'] = $this->Madmins->get_one("*",array("id"=>$id));
        $data['title'] = array("管理员列表",$data['info']['fullname']);

        $groups = $this->Madmins_group->get_lists("id,name",array('is_del'=>1));
        $data['groups'] = array_column($groups , 'name','id');

        $this->load->view("admin/info",$data);
    }

    /**
     * 管理员权限分配
     * 1034487709@qq.com
     */
    public  function purview($id){

        if(IS_POST)
        {
            $this->Madmins->update_info(array("purview_ids"=>implode(',',$_POST['purview'])),array("id"=>$id));
            $this->success("操作成功","/admin");
        }



        $data = $this->data;
        #用户信息
        $data['info'] = $this->Madmins->get_one("*",array("id"=>$id));
        $data['title'] = array("管理员列表",$data['info']['fullname']);

        #用户组已有权限
        $data['purview_ids'] = explode(',',$data['info']['purview_ids']);

        #获取当前用户所在的组的拥有权限
        $data['group_purview_ids'] = $this->Madmins_group->get_group_info($data['info']['group_id']);

        #所有权限
        $list = $this->Madmins_purview->get_group_purview(explode(",",$data['group_purview_ids']['purview_ids']));

        $data['list'] = class_loop($list);

        $this->load->view("admin/purview",$data);
    }

    /**
     * 个人设置
     * 1034487709@qq.com
     */
    public function set_admin(){
        $data = $this->data;
        $data['info'] = $this->Madmins->get_one("password,fullname,email,tel,describe",array("id"=>$this->data["userInfo"]['id']));
        if(IS_POST){
            $post_data = $_POST;
            if($_POST['password']!='' && md5($_POST['password']) != $data['info']['password'])
            {
                $post_data['password'] = md5($this->input->post("password",'trim'));
            }
            else
            {
                $post_data['password'] = $data['info']['password'];
            }

            $res = $this->Madmins->update_info($post_data,array("id"=>$this->data["userInfo"]['id']));
            if($res){
                $this->success("修改成功","/admin/set_admin");
            }
            else{
                $this->error("操作失败");
            }
        }
        $data['title'] = array("管理员修改信息","个人设置");
        $this->load->view("admin/usercenter/edit",$data);
    }
    
    public function partition(){
        $data = $this->data;
        $this->Mhouses_user_diy_area->get_lists('*');
        //获取工程人员
        $userList = $this->Madmins->get_lists('id,pid,fullname,diy_area_id',['in' => ['group_id' => [4,6]], 'is_del' => 1]);
        $data['userList'] = $userList;
        unset($userList);
        $this->load->view("admin/partition", $data);
    }
    
    /**
     * 设定自定义区域
     */
    public function set_diy_area(){
        if(IS_POST){
            $diy_area_id = $this->input->post('diy_area_id');
            $user_id = $this->input->post('user_id');
            //判断这个人是否有记录
            $count = $this->Mhouses_user_diy_area->count(['user_id' => $user_id]);
            if($count){
                if($diy_area_id == 0){
                    //取消的情况
                    $res = $this->Mhouses_user_diy_area->update_info(['diy_area_id' => 0], ['user_id' => $user_id]);
                    if(!$res){
                        $this->return_json(['code' => 0, 'msg' => '更新区域表失败']);
                    }
                    //更新用户区域
                    $res = $this->Madmins->update_info(['diy_area_id' => $diy_area_id], ['id' => $user_id]);
                    if(!$res){
                        $this->return_json(['code' => 0, 'msg' => '更新用户数据失败']);
                    }
                    $this->return_json(['code' => 1, 'msg' => '编辑成功']);
                }else{
                    //选择的情况
                    //判断区域是否已经被选中了
                    $count = $this->Mhouses_user_diy_area->count(['diy_area_id' => $diy_area_id]);
                    if($count) $this->return_json(['code' => 1, 'msg' => '该区域已分配']);
                    $res = $this->Mhouses_user_diy_area->update_info(['diy_area_id' => $diy_area_id], ['user_id' => $user_id]);
                    if(!$res){
                        $this->return_json(['code' => 0, 'msg' => '操作失败']);
                    }
                    //更新用户区域
                    $res = $this->Madmins->update_info(['diy_area_id' => $diy_area_id], ['id' => $user_id]);
                    if(!$res){
                        $this->return_json(['code' => 0, 'msg' => '更新失败']);
                    }
                    $this->return_json(['code' => 1, 'msg' => '编辑成功']);
                }
            }else{
                //判断区域是否已经被选中了
                $count = $this->Mhouses_user_diy_area->count(['diy_area_id' => $diy_area_id]);
                if($count) $this->return_json(['code' => 1, 'msg' => '该区域已分配']);
                $add = [
                    'user_id' => $user_id,
                    'diy_area_id' => $diy_area_id,
                ];
                $res = $this->Mhouses_user_diy_area->create($add);
                if(!$res){
                    $this->return_json(['code' => 0, 'msg' => '操作失败']);
                }
                //更新用户区域
                $res = $this->Madmins->update_info(['diy_area_id' => $diy_area_id], ['id' => $user_id]);
                if(!$res){
                    $this->return_json(['code' => 0, 'msg' => '更新失败']);
                }
                $this->return_json(['code' => 1, 'msg' => '编辑成功']);
            }
        }
    }
    
    public function seeWorker(){
        $data = $this->data;
        $sql = "select * from t_app_location where user_id > 1 and date = '".date('Y-m-d')."' order by create_time desc";
        $query = $this->db->query($sql);
        $list = $query->result_array();
        $tmp = [];
        if($list){
            foreach ($list as $k => &$v){
                $v['create_time'] = date("y-m-d H:s:i", $v['create_time']);
            }
            foreach ($list as $k => $v){
                if(in_array($v['user_id'], $tmp)){
                    unset($list[$k]);
                }else{
                    array_push($tmp, $v['user_id']);
                }
            }
        }
        //admin
        $admin = $this->Madmins->get_lists('id,fullname', ['in' => ['group_id' => [4,6]]]);
        foreach ($list as $k => $v){
            foreach ($admin as $key => $val){
                $list[$k]['fullname'] = '';
                if($val['id'] == $v['user_id']){
                    $list[$k]['fullname'] = $val['fullname'];
                }
            }
        }
        $data['list'] = $list;
        $this->load->view('admin/seeworker', $data);
    }

}


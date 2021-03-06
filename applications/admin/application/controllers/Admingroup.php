<?php 
/**
* 管理员角色控制器
* @author 1034487709@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Admingroup extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_admins_group' => 'Madmins_group',
            'Model_admins_purview' => 'Madmins_purview'
        ]);
        $this->data['code'] = 'admin_user_manage';
        $this->data['active'] = 'group_list';

    }

    /**
     * 用户角色管理
     * 1034487709@qq.com
     */

    public  function index(){
        $data = $this->data;
        $data['title'] = array("管理员管理","角色列表");

        //角色
        $list = $this->Madmins_group->get_lists("id,name,describe,purview_ids,type",array('is_del'=>1));

        if($list)
        {
            foreach($list as $key=>$v){
               $list[$key]['admin_count'] = $this->Madmins->get_admin_count($v['id']);
             }
        }
        $data['list'] = $list;

        $this->load->view("group/index",$data);
    }

    /**
     * 添加用户角色
     * 1034487709@qq.com
     */
    public function add(){
        $da = $this->data;
        if(IS_POST)
        {
            $data['name'] =  $this->input->post("name",true);
            $data['describe'] =  $this->input->post("describe",true);
            $result_id =  $this->Madmins_group->create($data);
            if($result_id){
                $this->write_log($da['userInfo']['id'],1,"添加角色：".$data['name']);
                $this->success("","/admingroup");
            }else{
                $this->error("添加失败,请重新添加");
            }
        }

        $da['title'] = array("角色管理","添加角色");
        $this->load->view("group/add",$da);
    }

    /**
     * 校验角色名是否存在
     * 1034487709@qq.com
     */
    public function  check_name(){
        if($this->input->is_ajax_request())
        {
            if($this->data['pur_code']){
                $this->return_json(array("code"=>2));
            }
           $name =  $this->input->post("name",true);
           $count = $this->Madmins_group->count(array('is_del'=>1,'name'=>$name));
           if($count){
               $this->return_json(array("code"=>0));
           }else{
               $this->return_json(array("code"=>1));
           }

        }
    }

    /**
     * 编辑角色
     * 1034487709@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        if(IS_POST){
            $_POST['id'] = $id ;
            $old = $this->Madmins_group->get_one('name',['id' => $id]);
            $res = $this->Madmins_group->replace_into($_POST);
            $new = $this->Madmins_group->get_one('name',['id' => $id]);
            if($res){
                $this->write_log($data['userInfo']['id'],2,"编辑角色：".$old['name'] ."，修改为：" .$new['name']);
                $this->success("操作成功","/admingroup");
            }else{
                $this->error("编辑失败,请重新编辑");
            }
        }

        $data['info'] = $this->Madmins_group->get_one("*",array("id"=>$id));
        $data['title'] = array("管理角色","编辑".$data['info']['name'] );

        $this->load->view("group/edit",$data);
    }

    /**
     * 删除角色
     * 1034487709@qq.com
     */
    public function del($id = 0){
        $data = $this->data;
        #不能删除管理员
        $admin_count = $this->Madmins->get_admin_count($id);
        if($admin_count)
        {
            $this->success('该角色下存在管理员,请先删除管理员('.$admin_count.'位)','/admingroup');

        }
        #标记删除
        $res = $this->Madmins_group->update_info(array('is_del'=>2),array('id'=>$id));
        if($res){
            $tmp = $this->Madmins_group->get_one('name',array('id'=>$id));
            $this->write_log($data['userInfo']['id'],3,"删除角色：".$tmp['name']);
            $this->success("操作成功","/admingroup");
        }else{
            $this->error("删除失败");
        }

    }

   /**
     * 分配权限
     * 1034487709@qq.com
     */
    public function purview($id){

        $data = $this->data;


        #用户组信息
        $group_info = $this->Madmins_group->get_one("*",array("id"=>$id));

        $data['group_info'] = $group_info;
        $data['title'] = array("权限分配",$data['group_info']['name'] );

        #用户组已有权限
        $purview_ids = explode(',',$group_info['purview_ids']);

        $data['purview_ids'] = $purview_ids;


        if(IS_POST)
        {

            #同步权限给成员
            $del_diff = array_diff($purview_ids,$_POST['purview']); //再原来的基础去掉不要权限


            $add_diff = array_diff($_POST['purview'],$purview_ids); //再原来的基础上增加权限

            $this->Madmins->setDiffPurview($id, $del_diff, $add_diff );

            #保存权限
            $this->Madmins_group->update_info(array("purview_ids"=>implode(',',$_POST['purview'])),array("id"=>$id));

            $this->success("操作成功","/admingroup",0);

        }

        #所有权限
        $data['list'] = class_loop($this->Madmins_purview->get_all());

        $this->load->view("group/purview",$data);
    }

    
}
?>

<?php
/**
 * 权限管理控制器
 * @author 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Adminspurview extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_admins_purview' => 'Madmins_purview',
          ]);

        $this->data['code'] = 'admin_user_manage';
        $this->data['active'] = 'purview';

    }


    /**
     * 权限列表
     * 1034487709@qq.com
     */
    public function index() {
        $data = $this->data;
        $data['title'] = array("添加管理员","添加管理员");
        $data['list'] = class_loop( $this->Madmins_purview->get_all());

        $this->load->view("adminpurview/index",$data);
    }

    /**
     * 编辑
     * 1034487709@qq.com
     */
    public function edit($id = 0)
    {

        if(IS_POST)
        {
            $_POST['id'] = $id;
            $_POST['url'] = strtolower(trim(trim($_POST['url']),'/'));
            $res = $this->Madmins_purview->replace_into($_POST);
            if($res){
                $this->success("","/adminspurview");
            }else{
                $this->error("编辑失败,请重新编辑");
            }
        }

        $data = $this->data;
        //分类信息
        $data['info'] = $this->Madmins_purview->get_one("*",array("id"=>$id));
        $data['title'] = array("权限管理",$data['info']['name']);
        #获得一级分类
        $data['parent_purviews']  =  class_loop_list(class_loop($this->Madmins_purview->get_all()));

        $this->load->view("adminpurview/edit",$data);

    }


    /**
     * 删除
     * 1034487709@qq.com
     */
    public function del($id)
    {

        $info = $this->Madmins_purview->get_child($id);
        if($info)
        {
            $this->success("此权限下存在子权限，请先删除子权限！","/adminspurview");
         }
        else
        {
            $res = $this->Madmins_purview->delete(array('id'=>$id));
             if($res){
                 $this->success("","/adminspurview");
             }else{
                 $this->error("删除失败,请重新删除");
             }
        }
    }

    /**
     * 添加
     * 1034487709@qq.com
     */
    public function add($parent_id='')
    {

        $data = $this->data;
        if(IS_POST)
        {

            $_POST['url'] = strtolower(trim(trim($_POST['url']),'/'));
            $insert_id = $this->Madmins_purview->create($_POST);
            if($insert_id){
                $this->success("","/adminspurview");
            }else{
                $this->error("添加失败,请重新添加");
            }
        }


        //分类信息
        #父分类
        $data['parent_id'] = $parent_id;
        $data['title'] = array("权限管理","添加");

        #获得一级分类
         $data['parent_purviews'] = class_loop_list(class_loop($this->Madmins_purview->get_all()));

        $this->load->view("adminpurview/add",$data);
    }

}
?>

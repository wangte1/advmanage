<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_admins extends MY_Model {

    private $_table = 't_admins';

    public function __construct() {
        parent::__construct($this->_table);
    }

    #获得用户右侧菜单
    public function getMenus(){
        $menu = C('menu');
        $menu = $menu['menu'];


        if($_SESSION['USER']['id']==1) //超级管理员
        {
            return $menu;
        }
        if($menu)
        {
            foreach($menu as $key=>$child)
            {

                if(in_array($child['code'], $_SESSION['USER']['purview_url'])==false)
                {
                    //过滤一级目录
                    unset($menu[$key]);
                }
                else
                {
                    if($child['list'])
                    {
                        foreach($child['list'] as $key2=>$v)
                        {
                            $url = strtolower(trim(trim($v['url']),'/'));
                            if(in_array($url,$_SESSION['USER']['purview_url'])==false)
                            {
                                //过滤二级目录
                                unset($menu[$key]['list'][$key2]);
                            }
                        }
                    }
                }
            }
        }
        return $menu;
    }


    /*
     * 根据组id 获取对应名称
     * 1034487709@qq.com
     */
    public function get_admin_list( $group_id = '' ){
         $where['is_del'] = 1;
        if($group_id){
            $where['group_id'] = $group_id;
        }
        return $this->get_lists("id,fullname",$where);
    }



    /*
     * 获得组管理员数量
     * 1034487709@qq.com
     */
    public function get_admin_count($group_id=''){
        $where['is_del'] = 1;
        if($group_id !='')
        {
            $where['group_id'] = $group_id;
        }
        return $this->count($where);
    }

    /*
    * 判断用户名是否存在
    * 1034487709@qq.com
    */
     public  function is_exist_adminname($name = ""){
         if(empty($name)) return '';
         $where['is_del'] = 1;
         $where['name'] = $name;
         return  $count = $this->count($where);
     }


    #删除权限
    public function del_purview( $purviews, $purview_del ){

        #如果组没有权限，直接返回
        if(empty($purview_del))
        {
            return $purviews;
        }
        else
        {
            $purviews = explode(',',$purviews);
            $purview_del = explode(',',$purview_del);
            foreach($purviews as $key=>$id)
            {
                #逐个删除
                if(in_array($id,$purview_del))
                {
                    unset($purviews[$key]);
                }
            }
        }
        return implode(',',$purviews);
    }


    #同步到相关人员权限
    public function setDiffPurview( $group_id, $del_diff, $add_diff ){

        #管理员列表
        $Adminss = $this->get_lists("id,purview_ids",array("is_del"=>1,"group_id"=>$group_id));
//        echo $this->db->last_query();
//        print_r($Adminss);
        if($Adminss)
        {
            foreach($Adminss as $v)
            {
                $purview_ids = array();
                if($v['purview_ids'])
                {
                    $purview_ids = explode(',',$v['purview_ids']);
                    #删除旧权限
                    if($del_diff)
                    {
                        foreach($del_diff as $val)
                        {
                            $exist = array_search($val, $purview_ids);
                            if($exist!==false)
                            {
                                unset($purview_ids[$exist]);
                            }
                        }
                    }
                }

                #添加新权限
                $purview_ids = array_merge($purview_ids,$add_diff);
                $purview_ids = implode(',',$purview_ids);

                $this->update_info(array("purview_ids"=>$purview_ids),array('id'=>$v['id']));
            }
        }
    }


}
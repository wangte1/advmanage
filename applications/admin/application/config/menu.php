<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(
    'menu' => array(

        '资源管理' => array(
            'code' => 'resources_manage',
            'icon' => 'icon-asterisk  menu-i',
            'list' => array(
                array(
                    'url'=>'/points',
                   'name'=> '点位管理',
                   'active'=> 'points_list'
                ),
                array(
                    'url'=>'/mediamanage',
                    'name'=> '媒体管理',
                    'active'=> 'media_lists'
                ),
                array(
                    'url'=>'/specification',
                    'name'=> '规格管理',
                    'active'=> 'specification_list'
                ),
                array(
                    'url'=>'/customers',
                    'name'=> '客户管理',
                    'active'=> 'customers_list'
                ),
                array(
                    'url'=>'/customerprojects',
                    'name'=> '客户项目管理',
                    'active'=> 'customer_projects_list'
                ),
                array(
                    'url'=>'/makecompany',
                    'name'=> '制作公司管理',
                    'active'=> 'make_company_list'
                ),

                array(
                    'url'=>'/salesman',
                    'name'=> '业务员管理',
                    'active'=> 'salesman_list'
                ),

            )
        ),
        '订单管理' => array(
            'code' => 'orders_manage',
            'icon' => 'icon-book  menu-i',
            'list' => array(
                array(
                    'url'=>'/scheduledorders',
                    'name'=> '预定订单',
                    'active'=> 'scheduled_order_list'
                ),
                array(
                    'url'=>'/orders',
                    'name'=> '订单列表',
                    'active'=> 'order_list'
                ),
                array(
                    'url'=>'/changepicorders',
                    'name'=> '换画订单',
                    'active'=> 'change_pic_order_list'
                ),
            )
        ),
        '数据统计' => array(
            'code' => 'statistics_manage',
            'icon' => 'icon-bar-chart menu-i',
            'list' => array(
                array(
                    'url'=>'/statistics',
                    'name'=> '订单统计',
                    'active'=> 'statistics_order_list'
                ),
                /*array(
                    'url'=>'/makecompanyorder',
                    'name'=> '制作公司订单统计',
                    'active'=> 'make_order_list'
                ),*/
            )
        ),
        '管理员管理' => array(
            'code' => 'admin_user_manage',
            'icon' => 'glyphicon glyphicon-user menu-i',
            'list' => array(
                array(
                    'url' => '/admin',
                    'name' => '管理员列表',
                    'active' => 'admin_list'
                ),
                array(
                    'url' => '/admingroup',
                    'name' => '角色管理',
                    'active' => 'group_list'
                ),
                array(
                    'url' => '/adminspurview',
                    'name' => '权限管理',
                    'active' => 'purview'
                ) 
            ) 
        ),
        '日志管理' => array(
            'code' => 'operate_log',
            'icon' => 'icon-calendar menu-i',
            'list' => array(
                array(
                    'url' => '/operatelog',
                    'name' => '登录日志',
                    'active' => 'login_log_list'
                ),
                array(
                    'url' => '/operatelog/log',
                    'name' => '操作日志',
                    'active' => 'login_operate_list'
                ),
            ) 
        )

    ) 
);
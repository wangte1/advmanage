<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(
    'menu' => array(

        '户外资源管理' => array(
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
            	array(
            		'url'=>'/makereport',
            		'name'=> '生成验收报告',
            		'active'=> 'make_report'
            	),

            )
        ),
    		
    	'户外订单管理' => array(
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
    		
    	'网络资源管理' => array(
    		'code' => 'net_manage',
    		'icon' => 'icon-globe  menu-i',

    		'list' => array(

    			array(
    				'url'=>'/networkmanage/goindex2', //mod=2是凤凰
    				'name'=> '凤凰网络排班',
    				'active'=> 'network_manage_fh_list'
    			),
    			array(
    				'url'=>'/networkapply',
    				'name'=> '排班申请',
    				'active'=> 'network_apply_list'
    			),
    			array(
    				'url'=>'/networklog',
    				'name'=> '排班日志',
    				'active'=> 'network_log_list'
    			),
    			array(
    				'url'=>'/networkset',
    				'name'=> '排班设置',
    				'active'=> 'network_set_list'
    			),
    			array(
    				'url'=>'/networktype',
    				'name'=> 'tab设置',
    				'active'=> 'network_type_list'
    			),
    		)
    	),


    	'社区资源管理' => array(
    		'code' => 'community_manage',
    		'icon' => 'icon-globe  menu-i',
    		'list' => array(
    			array(
    				'url'=>'/housespoints',
    				'name'=> '点位管理',
    				'active'=> 'houses_points_list'
    			),
    			
    			array(
    				'url'=>'/housescustomers',
    				'name'=> '客户管理',
    				'active'=> 'houses_customers_list'
    			),
    		    
    			array(
    				'url'=>'/housesformat',
    				'name'=> '点位规格管理',
    				'active'=> 'points_type_list'
    			),
    			array(
	    			'url'=>'/houses',
	    			'name'=> '楼盘管理',
	    			'active'=> 'houses_list'
    			),
    			array(
    				'url'=>'/housesarea',
    				'name'=> '组团管理',
    				'active'=> 'houses_area_lists'
    			),
    		)
    	),
    	
        
    		
    	'社区订单管理' => array(
    		'code' => 'horders_manage',
    		'icon' => 'icon-book  menu-i',
    		'list' => array(
    			array(
    				'url'=>'/houseswantorders',
    				'name'=> '意向订单（业务）',
    				'active'=> 'houseswantorders_list'
    			),
    		    array(
    		        'url'=>'/confirm_reserve',
    		        'name'=> '预定订单确定',
    		        'active'=> 'confirm_reserve_list'
    		    ),
    			array(
    				'url'=>'/housesscheduledorders',
    				'name'=> '预定订单',
    				'active'=> 'housesscheduledorders_list'
    			),
    			array(
			    	'url'=>'/housesorders',
			    	'name'=> '订单列表',
			    	'active'=> 'houses_orders_list'
			    ),
    			array(
    				'url'=>'/houseschangepicorders',
    				'name'=> '换画订单',
    				'active'=> 'houses_change_pic_order_list'
    			),
    			array(
    				'url'=>'/housesassign',
    				'name'=> '派单列表',
    				'active'=> 'houses_assign_list'
    			),
    			array(
    				'url'=>'/housesconfirm',
    				'name'=> '确认派单',
    				'active'=> 'houses_confirm_list'
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
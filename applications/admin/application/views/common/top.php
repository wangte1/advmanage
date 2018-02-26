<div class="navbar navbar-default" id="navbar">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>
    
   

    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="/home" class="navbar-brand">
                <small>
                    <i class="icon-leaf"></i>
                    媒介管理系统
                </small>
            </a>
        </div>

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav" style="text-align: right;">
            	<li  id="m-menu-button" class="green" style="position:absolute;left:0;display:none;">
                    <a style="padding:0;" onclick="change_menu();" href="javascript:void(0);"  >
                        <i class="fa fa-list-ul"></i>
                    </a>
                </li>
            
                <li class="green" id="quick_menu">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon-bell-alt icon-animated-bell"></i>
                        <span class="badge badge-important"><?php echo $expire_scheduleorder_nums + $expire_orders_nums + $overdue_orders_nums;?></span>
                    </a>

                  <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="icon-warning-sign"></i>
                            消息通知
                        </li>
                        <li>
                            <a href="/scheduledorders?order_status=2">
                                <div class="clearfix">
                                    <span class="pull-left">
                                        <i class="btn btn-xs no-hover btn-info icon-twitter"></i>
                                        即将到期预定订单
                                    </span>
                                    <span class="pull-right badge badge-info">+<?php echo $expire_scheduleorder_nums;?></span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/orders?expire_time=1">
                                <div class="clearfix">
									<span class="pull-left">
										<i class="btn btn-xs no-hover btn-pink icon-comment"></i>
										即将到期订单
									</span>
                                    <span class="pull-right badge badge-info">+<?php echo $expire_orders_nums;?></span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/orders?overdue=1">
                                <div class="clearfix">
                                    <span class="pull-left">
                                        <i class="btn btn-xs no-hover btn-success icon-shopping-cart"></i>
                                        已到期未下画订单
                                    </span>
                                    <span class="pull-right badge badge-success">+<?php echo $overdue_orders_nums;?></span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?php echo $domain['static']['url'];?>/admin/images/default.png" alt="Jason's Photo" />
						<span class="user-info">
							<small>欢迎,</small>
							<?php echo $userInfo['name'];?>
						</span>
                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="/admin/set_admin">
                                <i class="icon-cog"></i>
                                个人设置
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/login/out">
                                <i class="icon-off"></i>
                                退出
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
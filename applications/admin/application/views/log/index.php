<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<!-- 头部 -->
<?php $this->load->view('common/top');?>

<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <!-- 左边导航菜单 -->
        <?php $this->load->view('common/left');?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">操作日志</a>
                    </li>
                    <li class="active">登录</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
						<span class="input-icon">
							<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
							<i class="icon-search nav-search-icon"></i>
						</span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <!-- <div class="widget-box-suaxuan">
                    <form action="#" method="get">

                        <div class="col-sm-4">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 开始时间： </label>
                            <div class="col-sm-9">
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker" value="<?php echo $start_time;?>" name="start_time" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                                            <span class="input-group-addon">
                                                <i class="icon-calendar bigger-110"></i>
                                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 结束时间： </label>
                            <div class="col-sm-9">
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker"  value="<?php echo $end_time;?>" name="end_time" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 widget-box-suaxuan-btm">
                            <button class="btn btn-sm btn-primary" type="submit" id="sub_serach" style=" line-height: 50%">搜索</button>
                            <a href="javacript:;" class="btn btn-sm btn-primary" id="cancel" style="text-decoration: none; margin-left: 20px; line-height: 50%">取消</a>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 header-zdy header-zdy-bg">
                    <a href="javascript:;">删除</a>
                    <a href="javascript:;" id="serach_shuaixuan">筛选</a>
                </div> -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>筛选条件</h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="icon-chevron-up"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 开始时间 </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker" value="<?php echo $start_time;?>" name="start_time" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 结束时间 </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker"  value="<?php echo $end_time;?>" name="end_time" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
                                                <button class="btn" type="reset">
                                                    <i class="icon-undo bigger-110"></i>
                                                    重置
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                 <div class="table-responsive">
                                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>

                                            <th>编号</th>
                                            <th>登录账号</th>
                                            <th>登录时间</th>
                                            <th class="hidden-480">登录IP</th>

                                            <th>
                                                <i class="icon-time bigger-110 hidden-480"></i>
                                                登录状态
                                            </th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        if($log_list){
                                            foreach($log_list as $key=>$val){
                                        ?>
                                        <tr>
                                           <td>
                                                <a href="#"><?php echo $val['id'];?></a>
                                            </td>
                                            <td><?php echo $val['login_name'];?></td>
                                            <td><?php echo $val['login_time'];?></td>
                                            <td><?php echo $val['login_ip'];?></td>
                                            <td class="hidden-480">
                                               <?php 
                                                    switch ($val['login_status']){
                                                        case 1:
                                                            echo '成功';
                                                            break;
                                                        case 2:
                                                            echo '失败，密码错误';
                                                            break;
                                                        case 3:
                                                            echo '失败，用户名不存在';
                                                            break;
                                                    }
                                                ?>
                                            </td>

                                        </tr>
                                        <?php } }?>

										</tbody>
                                    </table>
									<!--分页start-->
									<div class="row">
									  <div class="col-sm-6">
										<div class="dataTables_info" id="sample-table-2_info">
                                            共<a class="blue"><?php echo $data_count;?></a>条记录，当前显示第&nbsp;<a class="blue"><?php echo $page;?>&nbsp;</a>页
                                        </div>
                                      </div>
									  <div class="col-sm-6">
										<div class="dataTables_paginate paging_bootstrap">

										  <ul class="pagination">
											    <?php echo $pagestr;?>
										  </ul>

										</div>
									  </div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
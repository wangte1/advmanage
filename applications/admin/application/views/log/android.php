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
                    <li class="active">安卓日志</li>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 操作人 </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="operate_id" id="form-field-select-1">
                                                        <option value="">全部</option>
                                                        <?php foreach($admin as $k => $v){ ?>
                                                            <option <?php if($operate_id == $v['id']){echo "selected";}?>  value="<?php echo $v['id'];?>"><?php echo $v['fullname'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
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
                                         <div class="form-group">
                                         	<div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> token </label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" name="token" value="<?php echo $token;?>" class="col-xs-10 col-sm-12">
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
                                    <table style="word-break: break-all;" id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th width="5%">编号</th>
                                            <th width="5%">操作人</th>
                                            <th width="20%">token</th>
                                            <th width="20%">url</th>
                                            <th width="40%">内容</th>
                                            <th width="10%">时间</th>
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
                                            <td><?php echo $val['fullname'];?></td>
                                            <td><?php echo $val['token'];?></td>
                                            <td><?php echo $val['url'];?></td>
                                            <td><?php echo $val['content'];?></td>
											<td><?php echo date("Y-m-d H:i:s",$val['create_time']);?></td>
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
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script>
$(".select2").css('width','230px').select2({allowClear:true});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
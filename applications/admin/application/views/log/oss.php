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
                        <a href="#">日志管理</a>
                    </li>
                    <li class="active">oss对象存储列表</li>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 本地地址 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" name="local" value="<?php if(isset($local)){echo $local;}?>" class="col-xs-10 col-sm-12">
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
                                            <th width="30%">编号</th>
                                            <th width="70%">本地地址</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                        ?>
                                        <tr>
                                           <td>
                                                <a href="#"><?php echo $val['id'];?></a>
                                            </td>
                                            <td><?php echo substr($val['local'],38);;?></td>
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
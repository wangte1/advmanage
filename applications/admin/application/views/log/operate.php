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
                        <a href="#">首页</a>
                    </li>
                    <li>
                        <a href="#">日志管理</a>
                    </li>
                    <li class="active">操作日志</li>
                </ul>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 类型 </label>
                                                <div class="col-sm-9">
                                                    <select class="col-xs-8" name="type" id="form-field-select-1">
                                                        <option value="">全部</option>
                                                        <?php foreach($log_type as $key=>$val){ ?>
                                                            <option <?php if($type == $key){echo "selected";}?>  value="<?php echo $key;?>"><?php echo $val;?></option>
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
                                                <th class="center">
                                                    <label>
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </th>
                                                <th>编号</th>
                                                <th>操作人</th>
                                                <th>操作类型</th>
                                                <th class="hidden-480">操作内容</th>
                                                <th>
                                                    <i class="icon-time bigger-110 hidden-480"></i>
                                                   操作时间
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($log_list){
                                            foreach($log_list as $key=>$val){
                                        ?>
                                        <tr>
                                            <td class="center">
                                                <label>
                                                    <input type="checkbox" name="checkbox" class="ace checkbox" value="<?php echo $val['id'];?>"/>
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                           <td>
                                                <a href="#"><?php echo $val['id'];?></a>
                                            </td>
                                            <td><?php echo $admin[$val['operate_id']]?></td>
                                            <td><?php echo $log_type[$val['operate_type']];?></td>
                                            <td><?php echo $val['operate_content'];?></td>
                                            <td class="hidden-480">
                                                <?php echo date("Y-m-d H:i:s",$val['create_time']);?>
                                            </td>

                                        </tr>
                                        <?php } }?>

										</tbody>
                                    </table>
                                    <!-- 分页 -->
                                    <?php $this->load->view('common/page');?>
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
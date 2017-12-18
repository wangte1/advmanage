<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('bootstrap-timepicker.css', 'admin');?>" rel="stylesheet" />
<style type="text/css">
    #scrollTable table {
      margin-bottom: 0;
    }
    #scrollTable .div-thead {
    }
    #scrollTable .div-tbody{
      width:100%;
      height:450px;
      overflow:auto;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        padding: 4px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
        text-align: center;
    }
</style>
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
                        <a href="/orders">订单管理</a>
                    </li>
                    <li class="active">新建预定订单</li>
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
                <div class="page-header">
                    <h1>
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?><?php echo $order_type_text[$order_type];?>预定订单
                        <a href="/orders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                    <span class="text-warning bigger-110 orange">
                        <i class="icon-warning-sign"></i>
                        注：您可以预定空闲点位，也可以预定那些投放中的即将到期的点位，如果新建的预定订单中含有投放中的点位，则需等待这些点位到期并下画之后才能下单。
                    </span>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>基本信息</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告客户： </label>
                                            <div class="col-sm-10">
                                                <?php if(isset($info['customer_id'])): ?>
                                                   <span class="middle"><?php echo $customer['customer_name'];?></span>
                                                   <input type="hidden" name="customer_id" value="<?php echo $info['customer_id'];?>" />
                                                <?php else:?>
                                                <select name="customer_id" class="select2" required>
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <option value="<?php echo $val['id'];?>"><?php echo $val['customer_name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                                <?php endif;?>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 锁定开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="lock_start_time" value="<?php if(isset($info['lock_start_time'])){ echo $info['lock_start_time'];} else { echo date('Y-m-d'); }?>" readonly>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 锁定结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="lock_end_time" value="<?php if(isset($info['lock_end_time'])){ echo $info['lock_end_time'];} else { echo date("Y-m-d",strtotime("+7 day"));} ?>" readonly>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4" style="padding-top: 6px">
                                                <a href="javascript:;" data-rel="popover" title="说明" data-trigger="hover" data-content="锁定起止时间默认是从新建预定订单之日起一个星期之内，过了锁定结束时间，如果客户还没确认下单，系统将自动释放出该订单所有锁定点位。"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 锁定点位： </label>
                                            <div class="col-sm-10">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>请选择需要锁定的点位</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:580px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label" for="form-field-1" style="padding-left: 2px"> 选择媒体： </label>
                                                                <div class="col-sm-10" style="padding:0">
                                                                    <select id="media_id" class="select2 media-sel">
                                                                        <option value="">全部</option>
                                                                        <?php foreach($media_list as $val):?>
                                                                        <option value="<?php echo $val['id'];?>" <?php if(isset($info['media_id']) && $val['id'] == $info['media_id']){ echo "selected"; }?>><?php echo $val['name'].'('.$val['code'].')';?></option>
                                                                        <?php endforeach;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-sm-2 center">点位编号</th>
                                                                                <th class="col-sm-3 center">媒体名称</th>
                                                                                <th class="col-sm-3 center">规格</th>
                                                                                <th class="col-sm-2 center">状态</th>
                                                                                <th class="col-sm-2 center"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                <div class="div-tbody">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <tbody id="points_lists">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <input type="hidden" name="point_ids_old" value="<?php echo $info['point_ids'];?>" />
                                                <?php endif;?>

                                                <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
                                                <input type="hidden" name="point_ids" value="<?php if(isset($info['point_ids'])) { echo $info['point_ids']; } ?>" />
                                                
                                                <button class="btn btn-info btn-save" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    保 存
                                                </button>

                                                &nbsp; &nbsp; &nbsp;
                                                <button class="btn" type="button" onclick="javascript:history.go(-1)">
                                                    <i class="fa fa-reply bigger-110"></i>
                                                    返 回
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-5">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>已选择点位</h4>
                                <span class="widget-toolbar">
                                    已选择<span id="selected_points_num"><?php if(isset($selected_points)) { echo count($selected_points);} else { echo 0; }?></span>个点位
                                </span>
                            </div>

                            <div class="widget-body" style="height:1538px">
                                <div class="widget-main">
                                    <div id="scrollTable">
                                        <div class="div-thead">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-sm-2">点位编号</th>
                                                        <th class="col-sm-3">媒体名称</th>
                                                        <th class="col-sm-3">规格</th>
                                                        <th class="col-sm-2">状态</th>
                                                        <th class="col-sm-2"><button class="btn btn-xs btn-info remove-all" type="button">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="div-tbody" style="height: 1466px">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <tbody id="selected_points">
                                                    <?php if(isset($selected_points)):?>
                                                        <?php foreach($selected_points as $value):?>
                                                        <tr point-id="<?php echo $value['id'];?>">
                                                            <td class="col-sm-2"><?php echo $value['points_code'];?></td>
                                                            <td class="col-sm-3"><?php echo $value['media_name'].'('.$value['media_code'].')';?></td>
                                                            <td class="col-sm-3"><?php echo $value['size'];?></td>
                                                            <td class="col-sm-2">
                                                                <?php 
                                                                    switch ($value['point_status']) {
                                                                        case '1':
                                                                            $class = 'badge-success';
                                                                            break;
                                                                        case '3':
                                                                            $class = 'badge-danger';
                                                                            break;
                                                                    }
                                                                ?>
                                                                <span class="badge <?php echo $class; ?>">
                                                                    <?php echo C('public.points_status')[$value['point_status']];?>
                                                                </span>
                                                            </td>
                                                            <td class="col-sm-2"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除<i class="fa fa-remove" aria-hidden="true"></i></button></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </tbody>
                                            </table>
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('scheduledorders.js','admin');?>"></script>
<script type="text/javascript">
    $('[data-rel=popover]').popover({html:true});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

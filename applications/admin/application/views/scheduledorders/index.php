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
                        <a href="#">订单管理</a>
                    </li>
                    <li class="active">预定订单</li>
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
                    <a href="/scheduledorders/order_type" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新建预定订单</a>
                </div> 

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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单类型 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_type" class="select2">
                                                        <option value="">全部</option>
                                                        <option value="1" <?php if($order_type == 1){ echo "selected"; }?>>公交灯箱</option>
                                                        <option value="2" <?php if($order_type == 2){ echo "selected"; }?>>户外高杆</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户 </label>
                                                <div class="col-sm-9">
                                                    <select name="customer_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($customers as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['customer_name'];?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 锁定人 </label>
                                                <div class="col-sm-9">
                                                    <select name="admin_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($admins as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $admin_id){ echo "selected"; }?>><?php echo $val['fullname'];?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单状态 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_status" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach(C('scheduledorder.order_status.text') as $key => $value): ?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $order_status){ echo "selected"; }?>><?php echo $value;?></option>
                                                        <?php endforeach;?>
                                                    </select>
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
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>订单类型</th>
                                                <th>客户</th>
                                                <th>锁定点位</th>
                                                <th>锁定时间</th>
                                                <th>锁定人</th>
                                                <th>订单日期</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list as $key => $value) : ?>
                                            <tr>
                                                <td><?php echo $order_type_text[$value['order_type']];?></td>
                                                <td><?php echo $value['customer_name'];?></td>
                                                <td><?php echo $value['point_ids'] ? count(explode(',', $value['point_ids'])) : 0;?>个点位</td>
                                                <td>
                                                    <?php echo $value['lock_start_time'].'至'.$value['lock_end_time'];?>
                                                </td>
                                                <td><?php echo $value['admin_name'];?></td>
                                                <td><?php echo $value['create_time'];?></td>
                                                <td>
                                                    <?php 
                                                        switch ($value['order_status']) {
                                                            case '1':
                                                                $class = 'badge-yellow';
                                                                break;
                                                            case '2':
                                                                $class = 'badge-warning';
                                                                break;
                                                            case '3':
                                                                $class = 'badge-grey';
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge <?php echo $class; ?>">
                                                        <?php echo $status_text[$value['order_status']];?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a class="green tooltip-info" href="/scheduledorders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="详情">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a> 
                                                        <?php if($value['order_status'] < C('scheduledorder.order_status.code.done_release')):?>
                                                        <a class="green tooltip-info" href="/scheduledorders/edit/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>
                                                        <a class="grey tooltip-info release-points" href="javascript:;" data-id="<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="解除锁定">
                                                            <i class="icon-unlock bigger-130" aria-hidden="true"></i>
                                                        </a>
                                                        <?php endif;?>
                                                        
                                                        <?php if($value['order_status'] == 2):?>
                                                        <a class="grey tooltip-info update" href="javascript:;" data-id="<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="续期">
                                                            <i class="ace-icon glyphicon glyphicon-upload bigger-130" aria-hidden="true"></i>
                                                        </a>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script type="text/javascript">
    $(".select2").css('width','240px').select2({allowClear:true});

    $('.release-points').click(function(){
        var _self = $(this);
        var d = dialog({
            title: "提示",
            content: '解锁之后需等待当前订单锁定结束时间到期后才能再次给该客户新建预定订单，请谨慎操作！确定要解锁吗？',
            okValue: '确定',
            ok: function () {
                window.location.href = '/scheduledorders/release_points/' + _self.attr('data-id');
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(420);
        d.showModal();
    });

    $('.update').click(function(){
        var _self = $(this);
        var d = dialog({
            title: "提示",
            content: '请谨慎操作！确定要续期吗？',
            okValue: '确定',
            ok: function () {
                window.location.href = '/scheduledorders/update_points/' + _self.attr('data-id');
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(420);
        d.showModal();
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

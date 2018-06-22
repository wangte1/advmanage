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
                    <li class="active">预定订单待确认列表</li>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单类型 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_type" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach (C('order.houses_order_type') as $k => $v):?>
                                                        <option value="<?php echo $k;?>" <?php if($order_type == $k){ echo "selected"; }?>><?php echo $v;?></option>
                                                       	<?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户 </label>
                                                <div class="col-sm-9">
                                                    <select name="customer_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($customer_list as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['name'];?></option>
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
                                                        <?php foreach(C('housesscheduledorder.order_status.text')as $key => $value): ?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $order_status){ echo "selected"; }?>><?php echo $value;?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        	
            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 业务员： </label>
                                                <div class="col-sm-9">
                                                    <select name="sales_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($salesman as $key => $value): ?>
                                                        <option value="<?php echo $value['id'];?>" <?php if(isset($sales_id) && $sales_id == $value['id']){ echo "selected"; }?>><?php echo $value['name'];?></option>
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
                                                <th>排期时间</th>
                                                <th>锁定人</th>
                                                <th>业务员</th>
                                                <th>订单创建日期</th>
                                                <th>状态</th>
                                                <th>客户确认状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($list) && $list):?>
                                            <?php foreach ($list as $key => $value) : ?>
                                            <tr>
                                                <td><?php echo $order_type_text[$value['order_type']];?></td>
                                                <td id="order_<?php echo $value['id']?>">
                                                	<?php if($customer_list):?>
                                                	<?php foreach ($customer_list as $key => $val):?>
                                                		<?php if($val['id'] == $value['lock_customer_id']):?>
                                                		<?php echo $val['name'];break;?>
                                                		<?php endif;?>
                                                	<?php endforeach;?>
                                                	<?php endif;?>
                                                </td>
                                                <td><?php echo $value['point_ids'] ? count(explode(',', $value['point_ids'])) : 0;?>个点位</td>
                                                <td>
                                                    <?php echo $value['lock_start_time'].'至'.$value['lock_end_time'];?>
                                                </td>
                                                <td>
                                                    <?php echo $value['schedule_start'].'至'.$value['schedule_end'];?>
                                                </td>
                                                <td><?php echo $value['admin_name'];?></td>
                                                <td>
                                                	<?php foreach ($salesman as $k => $v):?>
                                                	<?php if($value['sales_id'] == $v['id']):?>
                                                	<?php echo $v['name'];break;?>
                                                	<?php endif;?>
                                                	<?php endforeach;?>
                                                </td>
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
                                                                $class = 'badge-success';
                                                                break;
                                                            case '4':
                                                                $class = 'badge-grey';
                                                                break;
                                                            case '5':
                                                                $class = 'badge-success';
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge <?php echo $class; ?>">
                                                        <?php echo $status_text[$value['order_status']];?>
                                                    </span>
                                                </td>
                                                <td>
                                                	<?php 
                                                        switch ($value['is_confirm']) {
                                                            case '0':
                                                                $class = 'badge-grey';
                                                                break;
                                                            case '1':
                                                                $class = 'badge-success';
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge <?php echo $class; ?>">
                                                        <?php echo $confirm_text[$value['is_confirm']];?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a class="green tooltip-info" href="/confirm_reserve/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="详情">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a> 
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif;?>
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
                window.location.href = '/housesscheduledorders/release_points/' + _self.attr('data-id');
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
                window.location.href = '/housesscheduledorders/update_points/' + _self.attr('data-id');
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(420);
        d.showModal();
    });

    //给客户发送短信
    $('.sendsms').on('click', function(){
    	var _obj = $(this);
    	var id = _obj.attr('data-id');
    	var salesName = _obj.attr('data-salesname');

        var d = dialog({
            title: "提示",
            content: '请谨慎操作！确定要给 '+ salesName +' 业务员发送短信通知吗？',
            okValue: '确定',
            ok: function () {
                var postData = {'sales_id':id};
                $.post('/housesscheduledorders/sendMsg', postData, function(data){
					if(data){
						layer.alert(data.msg);
					}
                });
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(420);
        d.showModal();
    });


    //预定订单转订单
    $('.checkout').click(function(){
        var id = $(this).attr('data-id');
		location.href = "/confirm_reserve/checkout/"+id;
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

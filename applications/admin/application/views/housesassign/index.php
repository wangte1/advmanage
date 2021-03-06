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
                    <li class="active">派单列表</li>
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
                                    <form id="form1" class="form-horizontal" role="form">
                                    	<input type="hidden" name="assign_type" id="assign_type" value="<?php echo $assign_type;?>">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单编号 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="order_code" class="form-control input-sm" value="<?php echo $order_code;?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单类型 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_type" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($order_type_text as $key => $value):?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $order_type){ echo "selected"; }?>><?php echo $value;?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户 </label>
                                                <div class="col-sm-9">
                                                    <select name="customer_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($customers as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 派单状态 </label>
                                                <div class="col-sm-9">
                                                    <select name="assign_status" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($houses_assign_status as $key => $val):?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $assign_status){ echo "selected"; }?>><?php echo $val;?></option>
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
                                
                                <div class="tabbable" id="tabs-260319">
										<ul class="nav nav-tabs">
											<li <?php if($assign_type == 1){?>class="active"<?php }?>>
												<a href="#panel-1" data-toggle="tab">上画派单&nbsp;<span class="badge badge-important"><?php echo $no_confirm_count1;?></span></a>
											</li>
											<li <?php if($assign_type == 2){?>class="active"<?php }?>>
												<a href="#panel-2" data-toggle="tab">下画派单&nbsp;<span class="badge badge-important"><?php echo $no_confirm_count2;?></span></a>
											</li>
											<li <?php if($assign_type == 3){?>class="active"<?php }?>>
												<a href="#panel-3" data-toggle="tab">换画派单&nbsp;<span class="badge badge-important"><?php echo $no_confirm_count3;?></span></a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="panel-1">
			                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
			                                        <thead>
			                                            <tr>
			                                                <th class="phone-hide">订单编号</th>
			                                                <th nowrap>订单类型</th>
			                                                <th nowrap>投放点位</th>
			                                                <th nowrap>客户</th>
			                                                <th class="phone-hide">投放时间</th>
			                                                <th class="phone-hide">下单日期</th>
			                                                <th class="phone-hide">派单状态</th>
			                                                <th nowrap class="phone-show">派单状态</th>
			                                                <th>订单改派</th>
			                                                <th class="phone-hide">创建人</th>
			                                                <th nowrap>操作</th>
			                                            </tr>
			                                        </thead>
			                                        <tbody>
			                                            <?php foreach ($list as $key => $value) : ?>
			                                            <tr>
			                                                <td class="phone-hide">
			                                                    <a href="/housesassign/order_detail/<?php echo $value['id'];?>/<?php echo $assign_type;?>/<?php echo $value['assign_status'];?>">
			                                                    <?php echo $value['order_code'];?>
			                                                    <?php if($groupList && $value['group_id']):?>
			                                                    	<?php foreach ($groupList as $k => $v):?>
			                                                    		<?php if($v['id'] == $value['group_id']):?>
			                                                    		(组长<?php echo $v['fullname']?>派单)
			                                                    		<?php endif;?>
			                                                    	<?php endforeach;?>
			                                                    <?php endif;?>
			                                                    </a>
			                                                </td>
			                                                <td><?php echo $order_type_text[$value['order_type']];?></td>
			                                                <td><?php echo $value['point_ids'] ? count(array_unique(explode(',', $value['point_ids']))) : 0;?>个点位</td>
			                                                <td>
			                                                	<?php foreach ($customers as $k => $v) {?>
			                                                		<?php if($v['id'] == $value['customer_id']) {?>
			                                                			<?php echo $v['name'];?>
			                                                		<?php }?>
			                                                	<?php }?>
			                                                	
			                                                </td>
			                                                
			                                                <td class="phone-hide">
			                                                    <?php echo $value['release_start_time'].'至'.$value['release_end_time'];?>
			                                                    <?php
			                                                            $release_end_time =  strtotime($value['release_end_time']);
			                                                            $today_time = strtotime(date("Y-m-d"));
			                                                            $between_time =  60*60*24*7;
			                                                    ?>
			                                                </td>
			                                                <td class="phone-hide"><?php echo $value['create_time'];?></td>
			                                                <td class="phone-hide">
			                                                    <?php 
			                                                        switch ($value['assign_status']) {
			                                                            case '1':
			                                                                $class = 'badge-yellow';
			                                                                break;
			                                                            case '2':
			                                                                $class = 'badge-pink';
			                                                                break;
			                                                            case '3':
			                                                                $class = 'badge-success';
			                                                                break;
			                                                            case '4':
			                                                                $class = 'badge-warning';
			                                                                break;
			                                                            case '5':
			                                                                $class = 'badge-danger';
			                                                                break;
			                                                            case '6':
			                                                                $class = 'badge-info';
			                                                                break;
			                                                            case '7':
			                                                                $class = 'badge-purple';
			                                                                break;
			                                                            case '8':
			                                                                $class = 'badge-grey';
			                                                                break;
			                                                        }
			                                                    ?>
			                                                    <span class="badge <?php echo $class; ?>">
			                                                        <?php echo $houses_assign_status[$value['assign_status']];?>
			                                                    </span>
			
			                                                </td>
			                                                <td>
			                                                	<?php if(in_array($userInfo['group_id'], [C('group.gc'), 1]) && $value['assign_status'] == 1):?>
			                                                	<select class="new-user" data-id="<?php echo $value['id']?>">
			                                                		<option>请选择</option>
			                                                		<?php foreach ($groupList as $k => $v):?>
			                                                		<option value="<?php echo $v['id']?>"><?php echo $v['fullname']?></option>
			                                                		<?php endforeach;?>
			                                                	</select>
			                                                	<?php endif;?>
			                                                </td>
			                                                <td class="phone-show"><?php echo $houses_assign_status[$value['assign_status']];?></td>
			                                                <td class="phone-hide"><?php echo $admins[$value['creator']];?></td>
			                                                <td nowrap>
			                                                    <div class="">
			                                                    	<!-- <a class="green tooltip-info m-detail" data-id="<?php echo $value['id'];?>" assign_type="<?php echo $value['assign_type'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
			                                                            <i class="icon-eye-open bigger-130"></i>
			                                                        </a>-->
			                                                        

			                                                        <a class="green tooltip-info" href="/housesassign/order_detail/<?php echo $value['id'];?>/<?php echo $assign_type;?>/<?php echo $value['assign_status'];?>"   data-rel="tooltip" data-placement="top" title="" data-original-title="详情">

			                                                            <i class="icon-eye-open bigger-130"></i>
			                                                        </a>
			                                                    	<?php if($value['assign_status'] == 1) {?>
				                                                        <a class="green tooltip-info m-assign" data-pid="<?php echo $value['pid']?>" data-id="<?php echo $value['id'];?>" assign_type="<?php echo $value['assign_type'];?>" data-rel="tooltip" data-placement="top" title="" data-original-title="派单">
				                                                            <i class="icon-hand-right bigger-130"></i>
				                                                        </a> 
			                                                        <?php }else if($value['assign_status'] == 2) {?>
			                                                        	<?php if($value['pid'] == 0){?>
			                                                        	<a class="green tooltip-info m-edit" data-id="<?php echo $value['id'];?>" assign_type="<?php echo $value['assign_type'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="改派">
			                                                                <i class="icon-pencil bigger-130"></i>
			                                                            </a>
			                                                            <?php }?>
			                                                        <?php }?>
			                                                    </div>
			                                                </td>
			                                            </tr>
			                                            <?php endforeach; ?>
			                                        </tbody>
			                                    </table>
			                           		</div>
			                           		
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
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 150px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">投放结束时间</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group" style="margin-bottom: 20px">
                        <label for="message-text" class="control-label" style="float: left; padding-top: 8px">请选择投放结束时间:</label>
                        <div class="input-group date datepicker" style="width: 280px; float: left; padding-left: 5px">
                            <input class="form-control date-picker release_end_time" value="<?php echo date("Y-m-d",strtotime("+15 day"))?>" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                            <span class="input-group-addon">
                                <i class="icon-calendar bigger-110"></i>
                            </span>
                        </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer" id="info-msg" style="background: #fff; text-align: center;font-size: 13px; color: #428bca"></div>
            <div class="modal-footer">
                <span class="error_msg" style="color: red"></span>
                <input type="hidden" value="" id="point-id">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="expire-add">保存</button>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script type="text/javascript">
    $('[data-rel=popover]').popover({html:true});
    $(".select2").css('width','240px').select2({allowClear:true});

	$(function(){
		$('.nav-tabs').find('a').click(function(){
			if($(this).attr('href') == '#panel-1') {
				$('#assign_type').val('1');
			}

			if($(this).attr('href') == '#panel-2') {
				$('#assign_type').val('2');
			}

			if($(this).attr('href') == '#panel-3') {
				$('#assign_type').val('3');
			}

			$('#form1').submit();
		});
		
		$('.m-detail').click(function(){
			var order_id = $(this).attr('data-id');
			var assign_type = $(this).attr('assign_type');
			layer.open({
				  type: 2,
				  title: '详情',
				  shadeClose: true,
				  shade: 0.6,
				  area: ['70%', '70%'],
				  content: 'housesassign/detail?order_id='+order_id+'&assign_type='+assign_type //iframe的url
				}); 
		});

		$('.new-user').change(function(){
			var userid = $(this).val();
			var id = $(this).attr("data-id");
			$.post('housesassign/changeGroup', {'id':id, "userid":userid}, function(data){
				layer.msg(data.msg);
			});
		});
	
		$('.m-assign').click(function(){
			var order_id = $(this).attr('data-id');
			var assign_type = $(this).attr('assign_type');
			var pid = $(this).attr('data-pid');
			url = 'housesassign/assign?order_id='+order_id+'&assign_type='+assign_type; //iframe的url
			if(pid == "0"){
				url = 'housesassign/new_assign?order_id='+order_id+'&assign_type='+assign_type; //iframe的url
			}
			
			layer.open({
				  type: 2,
				  title: '派单',
				  shadeClose: true,
				  shade: 0.6,
				  area: ['70%', '72%'],
				  content: url
				}); 
		});

		$('.m-edit').click(function(){
			var order_id = $(this).attr('data-id');
			var assign_type = $(this).attr('assign_type');
			var index = layer.alert("改派后，原先的派单将被删除", function(){
				layer.close(index);
				layer.open({
					  type: 2,
					  title: '改派',
					  shadeClose: true,
					  shade: 0.6,
					  area: ['70%', '70%'],
					  content: 'housesassign/new_assign?order_id='+order_id+'&assign_type='+assign_type //iframe的url
				});
			});
			
		});
	});

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

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
                        <a href="#">社区资源管理</a>
                    </li>
                    
                    <li>
                        <span>派单确认</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
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
                                    	<input type="hidden" name="assign_type" id="assign_type" value="<?php echo $assign_type;?>">
                                        
                                        <div class="form-group">
                                            <div class="col-sm-6 col-xs-12">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户名称</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="customer_name" value="<?php if(isset($customer_name)){echo $customer_name;}?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6 col-xs-12">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 负责人</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="charge_name" value="<?php if(isset($charge_name)){echo $charge_name;}?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group">
                                        
                                            <div class="col-sm-6">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态</label>
                                                <div class="col-sm-9">
                                                    <select name="status">
                                                    	<option value=""></option>
                                                    	<?php foreach ($houses_assign_status as $k => $v) {?>
                                                    		<?php if($k != 1) {?>
                                                    		<option value="<?php echo $k;?>" <?php if($k == $status) {?>selected="selected"<?php }?>><?php echo $v?></option>
                                                    		<?php }?>
                                                    	<?php }?>
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

                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-xs-12">
                                 <div class="table-responsive">
                                 	<div class="tabbable" id="tabs-260319">
										<ul class="nav nav-tabs">
											<li <?php if($assign_type == 1){?>class="active"<?php }?>>
												<a href="#panel-1" data-toggle="tab">上画派单&nbsp;<span class="badge badge-important">0</span></a>
											</li>
											<li <?php if($assign_type == 2){?>class="active"<?php }?>>
												<a href="#panel-2" data-toggle="tab">下画派单&nbsp;<span class="badge badge-important">0</span></a>
											</li>
											<li <?php if($assign_type == 3){?>class="active"<?php }?>>
												<a href="#panel-3" data-toggle="tab">换画派单&nbsp;<span class="badge badge-important">0</span></a>
											</li>
										</ul>
										<div class="tab-content table-responsive">
											<div class="tab-pane active" id="panel-1">
												<table id="sample-table-2" class="table table-striped table-bordered table-hover">
			                                        <thead>
			                                            <tr>
			                                                <th class="phone-hide">序号</th>
			                                                <th class="phone-hide">订单编号</th>
			                                                <th nowrap class="phone-hide">点位类型</th>
			                                                <th class="phone-hide">客户名称</th>
			                                                <th>总计</th>
			                                                <th>已完成</th>
			                                                <th class="phone-hide">负责人</th>
			                                                <th class="phone-hide">派单时间</th>
			                                                <th nowrap>操作</th>
			                                            </tr>
			                                        </thead>
			                                        <tbody>
			                                        <?php
			                                        if($list){
			                                            foreach($list as $key=>$val){
			                                                ?>
			                                                <tr>
			                                                    <td class="phone-hide"><a href=""><?php echo $key+1;?></a></td>
			                                                    <td class="phone-hide">
			                                                    	<?php echo $val['order_code'];?>
			                                                    </td>
			                                                    
			                                                   
			                                                    <td class="phone-hide">
			                                                    	<?php if(isset($order_type_text[$val['order_type']])) echo $order_type_text[$val['order_type']];?>
			                                                    </td>
			                                                     <td class="phone-hide">
			                                                    	<?php echo $val['customer_name'];?>
			                                                    </td>
																<td>
																	<?php echo $val['total'];?>
																</td>
																<td>
																	<?php echo $val['finish'];?>
																</td>
																
			                                                    <td class="phone-hide">
			                                                    	<?php if(isset($user_list[$val['assign_user']])) echo $user_list[$val['assign_user']];?>
			                                                    </td>
			                                                    
			                                                    <td class="phone-hide"><?php echo $val['create_time'];?></td>
			                                                    
			                                                   	<td class="phone-show">
			                                                   		<?php echo $houses_assign_status[$val['status']];?>
			                                                   	</td>
			                                                   	
			                                                    <td nowrap>
			                                                        <div class="">
			                                                            <a class="green tooltip-info" href="/housesconfirm/order_detail/<?php echo $val['id']?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
				                                                            <i class="icon-eye-open bigger-130"></i>
				                                                        </a> 
				                                                        <?php if($val['status'] == 2) {?>
					                                                        <a class="green tooltip-info m-confirm" data-id="<?php echo $val['id'];?>" order-id="<?php echo $val['order_id'];?>"  order-charge_user="<?php echo $val['charge_user'];?>"   data-rel="tooltip" data-placement="top" title="" data-original-title="确认">
					                                                            <i class="icon-check bigger-130"></i>
					                                                        </a> 
				                                                        <?php }?>
				                                                        

				                                                        <?php if($val['status'] == 4 || $val['status'] == 5 || $val['status'] == 7) {?>

				                                                        	 
				                                                        <?php }?>

				                                                        <?php if($val['status'] == 3 ||  $val['status'] == 6) {?>

				                                                        	<a class="green tooltip-info m-upload" data-id="<?php echo $val['id'];?>" order-id="<?php echo $val['order_id'];?>" houses-id="<?php echo $val['houses_id'];?>" area_id="<?php echo $val['area_id']?>" ban="<?php echo $val['ban'];?>" num="<?php echo $val['points_count'];?>" data-rel="tooltip" data-placement="top" title="" data-original-title="验收图片">
					                                                            <i class="fa fa-picture-o bigger-130"></i>
					                                                        </a>
					                                                        <br/>
					                                                        <a class="green tooltip-info m-submit" data-id="<?php echo $val['id'];?>" order-id="<?php echo $val['order_id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="提交<?php if($assign_type == 2) {?>下画<?php }else {?>上画<?php }?>">
					                                                            <i class="fa fa-send-o bigger-130"></i>
					                                                        </a>  
				                                                        <?php }?>
				                                                        
				                                                        	<a class="green tooltip-info out" data-id="<?php echo $val['id'];?>" order-id="<?php echo $val['order_id'];?>" charge_user="<?php echo $val['charge_user']?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="导出点位">
					                                                            <i class="ace-icon glyphicon glyphicon-print bigger-130"></i>
					                                                        </a>
			                                                        </div>
			                                                    </td>
			                                                </tr>
			                                            <?php } }?>
													</tbody>
			                                    </table>
												
												
												
											</div>
											
											<!--分页start-->
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

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>

<script>
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php if(isset($province)) { echo $province;}else{?>贵州省<?php }?>",
		city: "<?php if(isset($city)) { echo $city;}else{?>贵阳市<?php }?>",
		district : "<?php if(isset($area)) { echo $area;}?>",
	});

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

			$('form').submit();
		});
		
		$('.m-detail').click(function(){
			var order_id = $(this).attr('order-id');
			var houses_id = $(this).attr('houses-id');
			var assign_type = '<?php echo $assign_type;?>';
			
			layer.open({
				  type: 2,
				  title: '包含点位',
				  shadeClose: true,
				  shade: 0.6,
				  area: ['60%', '60%'],
				  content: '/housesassign/show_points?order_id='+order_id+'&houses_id='+houses_id+"&assign_type="+assign_type //iframe的url
				}); 
		});

		$('.m-detail2').click(function(){
			var id = $(this).attr('data-id');
			var order_id = $(this).attr('order-id');
			var houses_id = $(this).attr('houses-id');
			var ban = $(this).attr('ban');
			var assign_type = '<?php echo $assign_type;?>';
			var area_id = $(this).attr('area_id');
			location.href='/housesconfirm/upload_detail?order_id='+order_id+'&assign_id='+id+'&houses_id='+houses_id+ '&area_id='+ area_id +'&ban='+ban+'&assign_type='+assign_type //iframe的url
			return;
		});

		//确认派单
		$('.m-confirm').click(function(){
			var id = $(this).attr('data-id');
			var order_id = $(this).attr('order-id');
			var assign_type = '<?php echo $assign_type;?>';
			var charge_user = $(this).attr('order-charge_user');
			layer.confirm('确认该派单？', {
				  btn: ['确定','取消'] //按钮
				}, function(){
				 	$.post('/housesconfirm/do_confirm', {id:id, order_id:order_id, assign_type:assign_type, charge_user:charge_user}, function(data){
						if(data.code == 1) {
							layer.alert(data.msg,function(){
								location.reload();
							});
						}else {
							layer.alert(data.msg);
						}
					});
				});
		});

		//上传验收图片
		$('.m-upload').click(function(){
			var id = $(this).attr('data-id');
			var order_id = $(this).attr('order-id');
			var houses_id = $(this).attr('houses-id');
			var ban = $(this).attr('ban');
			var area_id = $(this).attr('area_id');
 			var assign_type = '<?php echo $assign_type;?>';
			var num = $(this).attr('num');

			location.href='/housesconfirm/check_upload_img?order_id='+order_id+'&assign_id='+id+'&houses_id='+houses_id+'&area_id='+area_id+'&ban='+ban+'&assign_type='+assign_type+'&num='+num //iframe的url
			return;
			
		});

		//上传验收图片
		$('.out').click(function(){
			var id = $(this).attr('data-id');
			var order_id = $(this).attr('order-id');
			var houses_id = $(this).attr('houses-id');
			var ban = $(this).attr('ban');
			var charge_user= $(this).attr('charge_user');
			var area_id = $(this).attr('area_id');
 			var assign_type = '<?php echo $assign_type;?>';
			var num = $(this).attr('num');

			location.href='/housesconfirm/user_all_task_export?order_id='+order_id+'&assign_id='+id+'&houses_id='+houses_id+'&area_id='+area_id+'&ban='+ban+'&assign_type='+assign_type+'&charge_user='+charge_user //iframe的url
			return;
			
		});

		//提交上画
		$('.m-submit').click(function(){
			var assign_id = $(this).attr('data-id');
			var assign_type = '<?php echo $assign_type;?>';
			
			layer.confirm('您确认提交上画至媒介管理员审核吗？', {
					btn: ['确认','取消'] //按钮
				}, function(){
					$.post('/housesconfirm/submit_upload', {assign_id : assign_id, assign_type : assign_type}, function(data){
						if(data) {
							layer.alert(data.msg, function(){
								location.reload();
							});
						}
					});
				});
			
			
			
		});

		
	});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
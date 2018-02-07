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
                    <li>
                        <a href="/housesassign">派单列表</a>
                    </li>
                    <li class="active">订单详情</li>
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
                    <h1>订单详情</h1>
                </div>

                <div class="row">
                   <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                        <li class="active">
                                            <a data-toggle="tab" href="#basic">基本信息</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#adv_img">广告画面</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#point_info">点位信息</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="basic" class="tab-pane in active">
                                            <div class="profile-user-info profile-user-info-striped">
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单编号 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click" id="order_code"><?php echo $info['order_code'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单总价 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['total_price'];?> 元</span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 客户 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['customer_name'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 业务员 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['salesman']['name'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 业务员手机号 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['salesman']['phone_number'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 投放时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['release_start_time'].'至'.$info['release_end_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 广告性质 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['adv_nature'];?></span>
                                                    </div>
                                                </div>

                                                <?php if($info['order_type'] == 3 || $info['order_type'] == 4): ?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 广告频次 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['adv_frequency']) { echo $info['adv_frequency']; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <?php if($info['order_type'] == 1 || $info['order_type'] == 2): ?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 制作公司 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['make_company']) { echo $info['make_company']; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 制作完成时间 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['make_complete_time']) { echo date('Y年m月d日H时' , strtotime($info['make_complete_time'])); } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 广告小样 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['is_sample'] == 1) { echo '是('.$info['sample_color'].')'; } else { echo '否'; } ?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 制作要求 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['make_requirement']) { echo $info['make_requirement']; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>

                                                <?php if($info['order_type'] == 1 || $info['order_type'] == 2):?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 委托内容 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['leave_content']) { echo C('order.leave_content')[$info['leave_content']]; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <?php if($info['order_type'] == 2):?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 安装类型 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['install_type']) { echo C('order.install_type')[$info['install_type']]; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单日期 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['create_time'];?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单状态</div>

                                                    <div class="profile-info-value">
                                                        <?php 
                                                            switch ($info['order_status']) {
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
                                                                case '9':
                                                                    $class = 'badge-grey';
                                                                    break;
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $class; ?>">
                                                            <?php echo $status_text[$info['order_status']];?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 联系单与确认函</div>

                                                    <div class="profile-info-value">
                                                        <a href="/housesorders/contact_list/<?php echo $info['id'];?>" target="_blank">查看联系单</a>

                                                        <?php if($info['order_status'] > 6):?>
                                                        <a href="/housesorders/confirmation/<?php echo $info['id'];?>" target="_blank">查看确认函</a>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="adv_img" class="tab-pane">
                                            <?php if(count($info['adv_img']) > 0):?>
                                                <?php foreach ($info['adv_img'] as $value) : ?>
                                                    <a href="<?php echo $value;?>" target="_blank">
                                                        <img src="<?php echo $value;?>" style="width:300px; height:200px" />
                                                    </a>
                                                <?php endforeach;?>
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 还未上传广告画面！
                                                    </strong>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        
                                    	<!-- 点位信息 begin -->
                                    	<div id="point_info" class="tab-pane">
                                    		<?php if(isset($houses_id)) {?>
                                    			<iframe frameborder="no" border="0" src="/housesassign/show_points?order_id=<?php echo $id;?>&houses_id=<?php echo $houses_id;?>&assign_type=<?php echo $assign_type?>" width="100%" height="650px;"></iframe>
                                    		<?php }else {?>

                                    			<?php if($assign_status == 1) {?>
                                    				<table class="table table-striped table-bordered">
		                                                <thead>
		                                                    <tr>
		                                                        <th class="center">行政区域</th>
		                                                        <th class="center">楼盘</th>
		                                                        <th class="center">点位数</th>
		                                                        <th class="center">操作</th>
		                                                    </tr>
		                                                </thead>
		                                                <tbody>
		                                                    <?php foreach($info['selected_points'] as $value):?>
		                                                    <tr>
		                                                        <td class="center"><?php echo $value['province']."-".$value['city']."-".$value['area'];?></td>
		                                                        <td class="center"><?php echo $value['houses_name'];?></td>
		                                                        <td class="center"><?php echo $value['count'];?></td>
		                                                        <td class="center">
		                                                        	<a class="green tooltip-info" onclick="show_points_detail(<?php echo $id;?>,<?php echo $value['houses_id'];?>);" href="#" data-rel="tooltip" data-placement="top" data-original-title="详情">
			                                                            <i class="icon-eye-open bigger-130"></i>
			                                                        </a>
		                                                        </td>
		                                                    </tr>
		                                                    <?php endforeach;?>
		                                                </tbody>
		                                            </table>
	                                    			<script>
		                                            	function show_points_detail(order_id, houses_id) {
		                                            		layer.open({
		                                            			  type: 2,
		                                            			  title: '点位详情',
		                                            			  shadeClose: true,
		                                            			  shade: 0.8,
		                                            			  area: ['70%', '70%'],
		                                            			  content: '/housesorders/points_detail/'+order_id+'/'+houses_id //iframe的url
		                                            			}); 
		                                                }
		                                            </script>
                                    			<?php }else {?>
	                                    			<iframe frameborder="no" border="0" src="/housesassign/detail?order_id=<?php echo $id;?>&assign_type=<?php echo $assign_type?>" width="100%" height="650px;"></iframe>
	                                            <?php }?>
                                    			
                                    		

                                    		<?php }?>
                                    	</div>
                                    	<!-- end -->
                                    
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

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 220px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">上画审核</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                    <label for="message-text" class="control-label">说明:</label>
                    <textarea style="width: 90%; height: 80px" id="confirm-remark"></textarea>
               </div>
            </div>
            <div class="modal-footer">
                <span class="error_msg" style="color: red"></span>
                <button type="button" class="btn btn-default"  id="sub-not-confirm">不通过</button>
                <button type="button" class="btn btn-primary" id="sub-confirm">通过</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 220px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">更新状态</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                    <label for="message-text" class="control-label">备注:</label>
                    <textarea style="width: 90%; height: 80px" id="remark"></textarea>
               </div>
            </div>
            <div class="modal-footer">
                <span class="error_msg" style="color: red"></span>
                <input type="hidden" value="" id="point-id">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="lock-add">更新</button>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script>
    var adv_img_count = "<?php echo count($info['adv_img']); ?>";
    var inspect_img_count = "<?php echo count($info['inspect_img']); ?>";
    $('[data-rel=tooltip]').tooltip();

    //导出投放点位
    $(".btn-export").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        window.location.href = '/housesorders/export/' + id + '/' + type;
    });


    $('.m-detail').click(function(){
        var order_id = '<?php echo $id;?>';
		var houses_id = $(this).attr('data-id');
		
		layer.open({
			  type: 2,
			  title: '包含点位',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['60%', '60%'],
			  content: '/housesassign/show_points?order_id='+order_id+'&houses_id='+houses_id //iframe的url
			}); 
	});

	$('.m-confirm').click(function(){
		var assign_id = $(this).attr('data-id');
		var order_id = $(this).attr('order-id');
		var houses_id = $(this).attr('houses-id');
		var assign_type = $(this).attr('assign_type');

		$("#confirmModal").modal('show');

		//通过
		$('#sub-confirm').click(function(){
			var confirm_remark  = $('#confirm-remark').val();
			$.post('/housesorders/confirm_upload', {assign_id:assign_id, order_id:order_id, confirm_remark:confirm_remark, mark:1, assign_type:assign_type, houses_id:houses_id}, function(data){
				if(data) {
					layer.alert(data.msg, function(){
						location.reload();
					});
				}
			});
		});

		//不通过
		$('#sub-not-confirm').click(function(){
			var confirm_remark  = $('#confirm-remark').val();
			
			$.post('/housesorders/confirm_upload', {assign_id:assign_id, order_id:order_id, confirm_remark:confirm_remark, mark:2, assign_type:assign_type}, function(data){
				if(data) {
					layer.alert(data.msg, function(){
						location.reload();
					});
				}
			});
		});
		
	});

    $('.m-upload').click(function(){
		var id = $(this).attr('data-id');
		var order_id = $(this).attr('order-id');
		var houses_id = $(this).attr('houses-id');

		layer.open({
			  type: 2,
			  title: '查看验收图片',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['80%', '80%'],
			  content: '/housesorders/check_upload_img?order_id='+order_id+'&assign_id='+id+'&houses_id='+houses_id //iframe的url
			}); 
	});

    //更新订单状态
    $(".status-step").click(function(){

        
    });

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

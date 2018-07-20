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
                        <a href="/housesconfirm">确认派单</a>
                    </li>
                    <li class="active">派单详情</li>
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
                    <h1>派单详情</h1>
                    <br>
                    <p>
                    	<a onclick="history.back();">
	                    	<button class="btn btn-warning btn-xs">
								<i class="ace-icon fa fa-reply icon-only"></i>返回
							</button>
						</a>
					</p>
                </div>

                <div class="row">
                   <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                        <li class="active">
                                            <a data-toggle="tab" href="#adv_img">广告画面</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#point_info">点位信息</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="adv_img" class="tab-pane active">
                                            <?php if(count($info['adv_img']) > 0):?>
                                                <?php foreach ($info['adv_img'] as $value) : ?>
                                                    <a href="<?php echo $value;?>" target="_blank">
                                                        <img src="<?php echo $value;?>" style="width:300px; height:200px" />
                                                    </a>
                                                <?php endforeach;?>
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 您还未上传广告画面！
                                                    </strong>
                                                    <a class="btn btn-xs btn-info" href="/housesorders/upload_adv_img/<?php echo $info['id'];?>">
                                                        立即上传
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </a>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    	
                                    	<!-- 点位信息 begin -->
                                    	<div id="point_info" class="tab-pane">
                                    		<table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class=" center">点位编号</th>
                                                            <th class=" center">楼盘</th>
                                                            <th class=" center">组团</th>
                                                            <th class=" center">楼栋</th>
                                                            <th class=" center">单元</th>
                                                            <th class=" center">楼层</th>
                                                            <th class=" center">点位位置</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($info['selected_points'] as $key => $value) :?>
                                                        <tr>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['code'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                              </table>
                                    	</div>
                                    	<!-- end -->
                                    
                                    </div>
                                </div>

                                <!--<?php if($info['order_status'] < 9):?>
                                <div class="table-responsive">
                                    <div class="page-header" style="margin-top: 50px">
                                        <h1>订单状态跟踪</h1>
                                    </div>
                                    <div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
                                        <ul class="wizard-steps">

                                            <?php
                                            $n = 1;
                                            foreach($status_text as $key=>$val){

                                            ?>
                                            <li data-target="#step1" class="<?php if($key <= $info['order_status']){ echo "active";}?>">

                                                <?php
                                                    $order_status = $info['order_status'];


                                                    ?>
                                                   <span data-status="<?php echo $key;?>" class="step <?php if($key <= $info['order_status']+1){ echo "status-step";}?>" style="cursor: pointer"><?php echo $n;;?></span>

                                                <span class="title"><?php echo $val;?></span>
                                                <?php
                                                    if(isset($operate_remark)){
                                                ?>
                                                <span class="title" style="color:#CACACA; font-size: 12px ">操作时间:<?php echo @$time[$key];?></span>
                                                <span class="title" style="color:#CACACA; font-size: 12px ">操作备注:<?php echo @$operate_remark[$key];?></span>
                                                <?php }?>
                                            </li>
                                            <?php $n++; } ?>
                                        </ul>
                                    </div>
                                    <input type="hidden" value="<?php echo $id;?>" id="order_id">
                                </div>
                                <?php endif;?>-->
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

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
                        <a href="/housesscheduledorders">预定订单列表</a>
                    </li>
                    <li class="active">预定订单详情</li>
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
                    <h1>预定订单详情</h1>
                </div>

                <div class="row">
                   <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                        <li <?php if($tab == 'basic'){echo 'class="active"';}?>>
                                            <a data-toggle="tab" href="#basic">基本信息</a>
                                        </li>
                                        <li <?php if($tab == 'point'){echo 'class="active"';}?>>
                                            <a data-toggle="tab" href="#points">预选点位</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="basic" class="tab-pane <?php if($tab == 'basic'){echo 'in active';}?>">
                                            <div class="profile-user-info profile-user-info-striped">
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单类型 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $order_type_text[$info['order_type']];?></span>
                                                    </div>
                                                </div> 

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 预定客户 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['customer_name'];?></span>
                                                    </div>
                                                </div> 

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 锁定开始时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['lock_start_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 锁定结束时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['lock_end_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 备注 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php if($info['remarks']) { echo $info['remarks']; } else { echo '无'; };?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单日期 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['create_time'];?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单状态 </div>

                                                    <div class="profile-info-value">
                                                        <?php 
                                                            switch ($info['order_status']) {
                                                                case '1':
                                                                    $class = 'badge-yellow';
                                                                    break;
                                                                case '2':
                                                                    $class = 'badge-warning';
                                                                    break;
                                                                case '3':
                                                                    $class = 'badge-grey';
                                                                    break;
                                                                case '4':
                                                                    $class = 'badge-grey';
                                                                    break;
                                                                case '5':
                                                                   	$class = 'badge-grey';
                                                                   	break;
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $class; ?>">
                                                            <?php echo $status_text[$info['order_status']];?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div style="height:auto;min-height:500px;" class="profile-info-row">
                                                    <div class="profile-info-name"> 点位签字合同： </div>

                                                    <div class="profile-info-value">
                                                        <ul class="ace-thumbnails" id="uploader_cover_img" data='0'>
                                                    	<?php if($info['is_confirm']):?>
                                                        	<?php if(isset($info['confirm_img']) && !empty($info['confirm_img'])):?>
                                                            <?php foreach (explode(';', $info['confirm_img']) as $k => $v):?>
                                                            <li id="uploader_cover_img_<?php echo $k;?>" style="float: left;width: 150px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                                                <a style="height: 100%;" href="<?php echo $v;?>" target="_blank" class="up-img">
                                                                	<img style="height:100%;width:100%" src="<?php echo $v;?>">
                                                                </a>
                                                        	</li>
                                                            <?php endforeach;?>
                                                            <?php endif;?>
                                                    	<?php else:?>
                                                    	</ul>
                                                    	<span class="editable editable-click">
                                                    	未签字
                                                    	</span>
                                                    	<?php endif;?>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="points" class="tab-pane <?php if($tab == 'point'){echo 'in active';}?>">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">点位编号</th>
                                                        <th>楼盘</th>
                                                        <th>楼盘区域</th>
                                                        <th>详细地址</th>
                                                        <th class="hidden-xs">价格</th>
                                                        <th class="hidden-xs">规格</th>
                                                        <th class="hidden-xs">点位状态</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['selected_points'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['code'];?></td>
                                                        <td><?php echo $value['houses_name']?></td>
                                                        <td><?php echo $value['houses_area_name']?></td>
                                                        <?php if($value['addr'] == 1):?>
                                                        <td>门禁</td>
                                                        <?php else:?>
                                                        <td>电梯前室</td>
                                                        <?php endif;?>
                                                        <td><?php echo $value['price'];?></td>
                                                        <td><?php echo $value['size'];?></td>
                                                        <td>
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
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
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

<script type="text/javascript">
    //导出预定点位
    $(".btn-export").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        window.location.href = '/housesscheduledorders/export/' + id + '/' + type;
    });

    $('.sign').on('click', function(){
    	var index = layer.confirm('客户是否已经签字确认？', {
  		  btn: ['确定','取消'] //按钮
  		}, function(){
  			var order_id = '<?php echo $info["id"];?>';
  	    	$.post('/housesscheduledorders/sign', {'order_id':order_id}, function(data){
  				if(data.code = 1){
  					layer.msg(data.msg, {icon: 1});
  	  			}else{
  	  	  			layer.msg(data.msg, {icon: 2});
  	  	  		}
  	        });
  		}, function(){
  		  	layer.close(index);
  		});
    	
    });

    $('.show_detial').on('click', function(){
	    var order_id = '<?php echo $info["id"];?>';
	    var houses_id = $(this).attr('data');
	    var houses_name = $(this).attr('data-name');
 	    var index = layer.open({
            type: 2,
            title: houses_name,
            shade: 0.5,
            area: ['80%', '80%'],
            content: '/housesscheduledorders/houses_detail?order_id='+order_id+'&houses_id='+houses_id, //iframe的url
            cancel:function(){
            	var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            	parent.window.location.href = '/housesscheduledorders/detail/'+order_id+'/confirm' // 父页面刷新
            	parent.layer.close(index); //再执行关闭
            }
	    });
    });

    $('.all').on('click', function(e){
        
		var order_id = '<?php echo $info["id"];?>';
		var houses_id = $(this).attr('data-houses_id');
		var status = 0;
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		var obj = $(this);
		var tmp;
		$.post('/housesscheduledorders/select_all', {'order_id':order_id, 'houses_id':houses_id, 'status':status}, function(data){
			if(data.code == 1){
				window.location.href = '/housesscheduledorders/detail/'+order_id+'/confirm'
			}else{
				if(status){
					tmp = false;
				}else{
					tmp = true;
				}
				obj.prop('checked', tmp);
			}
		});
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

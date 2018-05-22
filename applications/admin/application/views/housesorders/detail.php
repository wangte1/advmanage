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
                        <a href="/orders">订单列表</a>
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
                                            <a data-toggle="tab" href="#points">投放点位</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#adv_img">广告画面</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#make_info">制作信息</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#inspect_img">验收图片</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#change_pic_record">换画记录</a>
                                        </li>
                                        <?php if($info['order_type'] == 1):?>
                                        <li>
                                            <a data-toggle="tab" href="#change_points_record">换点记录</a>
                                        </li>
                                        <?php endif;?>
                                        <li>
                                        	<a data-toggle="tab" href="#assign_list">上画派单列表</a>
                                        </li>
                                        <li>
                                        	<a data-toggle="tab" href="#assign_down_list">下画派单列表</a>
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
                                                        <span class="editable editable-click"><?php if(isset($info['salesman']) && isset($info['salesman']['fullname'])){echo $info['salesman']['fullname'];}else{echo "&nbsp;";}?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 业务员手机号 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php if(isset($info['salesman']) && isset($info['salesman']['tel'])){ echo $info['salesman']['tel'];}else{echo "&nbsp;";}?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 投放时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['release_start_time'].'至'.$info['release_end_time'];?></span>
                                                    </div>
                                                </div>

                                                <!-- <div class="profile-info-row">
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
                                                <?php endif;?> -->

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
	                                                    	<?php 
		                                                    	if($info['order_status'] == 1) {
		                                                    		if($info['adv_img'] != '') {
		                                                    				echo "已上传广告画面";
		                                                    		}else {
		                                                    			echo $status_text[$info['order_status']];
		                                                    		}
		                                                    	}else {
		                                                    		echo $status_text[$info['order_status']];
		                                                    	}
	                                                    	?>
	                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 联系单与确认函</div>

                                                    <div class="profile-info-value" style="height: 40px;">
                                                    	<?php if($info['order_status'] > 1):?>
                                                        <a href="/housesorders/contact_list/<?php echo $info['id'];?>" target="_blank">查看联系单</a>
														<?php endif;?>
														
                                                        <?php if($info['order_status'] > 6):?>
                                                        <a href="/housesorders/confirmation/<?php echo $info['id'];?>" target="_blank">查看确认函</a>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="points" class="tab-pane">
                                            <?php if($info['order_status'] != 8 && ($info['order_type'] == '1' || $info['order_type'] == '2')):?>
                                            <a href="javascript:;" class="btn btn-xs btn-info btn-export" data-id="<?php echo $info['id'];?>" data-type="<?php echo $info['order_type'];?>" style="margin-bottom:10px">
                                                <i class="fa fa-download out_excel" aria-hidden="true"></i> 导出投放点位
                                            </a>
                                            <?php endif;?>
                                            
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">行政区域</th>
                                                        <th class="center">楼盘</th>
                                                        <th class="center">点位数</th>
                                                        <th class="center">是否需要社区审核</th>
                                                        <th class="center">操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['selected_points'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['province']."-".$value['city']."-".$value['area'];?></td>
                                                        <td class="center"><?php echo $value['houses_name'];?></td>
                                                        <td class="center"><?php echo $value['count'];?></td>
                                                        <td class="center"><?php if($value['is_check_out'] == 1) {echo "<font style='color:red;'>是</font>";}else{echo "否";}?></td>
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
                                            
                                            <!-- <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">点位编号</th>
                                                        <th>楼盘</th>
                                                        <th>组团</th>
                                                        <th>楼栋</th>
                                                        <th>单元</th>
                                                        <th>楼层</th>
                                                        <th>点位位置</th>
                                                        <th class="hidden-xs">规格</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['selected_points'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['code'];?></td>
                                                        <td><?php echo $value['houses_name'];?></td>
                                                        <td><?php echo $value['houses_area_name'];?></td>
                                                        <td><?php echo $value['ban'];?></td>
                                                        <td><?php echo $value['unit'];?></td>
                                                        <td><?php echo $value['floor'];?></td>
                                                        <td><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                                        <td><?php echo $value['size'];?></td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table> -->
                                            
                                        </div>
                                        <div id="adv_img" class="tab-pane">
                                        	<?php if(count($info['adv_img']) > 0) {?>
	                                        	<div class="row" style="margin-top: 20px;">
	                                        	
		                                        	<div class="col-xs-12">
									                    <label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 是否打小样： </label>
									                    <div class="col-sm-10">
									                    	<?php if($info['is_sample'] == 1) {echo "是";}else{echo "否";}?>
									                    </div>
								                   	</div>
		                                        	
		                                        	<div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 广告画面： </label>
									                    <div class="col-sm-10">
									                    	<?php if(count($info['adv_img']) > 0):?>
				                                                <?php foreach ($info['adv_img'] as $value) : ?>
				                                                    <a href="<?php echo $value;?>" target="_blank">
				                                                        <img src="<?php echo $value;?>" style="width:300px; height:200px" />
				                                                    </a>
				                                                <?php endforeach;?>
				                                            <?php else:?>
				                                            <?php endif;?>
									                    </div>
		                                            </div>
	                                            
	                                            </div>
                                            <?php }else{?>
                                            	<div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 业务人员还未上传广告画面
                                                    </strong>
                                                </div>
                                            <?php }?>
                                            
                                        </div>
                                        <div id="make_info" class="tab-pane">
                                        	<?php if($info['order_status'] > 1):?>
                                                <div class="row" style="margin-top: 20px;">
                                        	
		                                        	<div class="col-xs-12">
									                    <label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 制作公司： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"><?php echo $info['make_company'];?></span>
									                    </div>
								                   	</div>
		                                        	
		                                        	<div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 制作要求： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"> <?php if($info['make_requirement']) { echo $info['make_requirement']; } else { echo '无'; } ?></span>
									                    </div>
		                                            </div>
		                                            
		                                            <div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 制作费用： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"> <?php if($info['make_fee']) { echo $info['make_fee']; } else { echo '无'; } ?></span>
									                    </div>
		                                            </div>
		                                            
		                                            <div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 制作完成时间： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"> <?php if($info['make_complete_time']) { echo $info['make_complete_time']; } else { echo '无'; } ?></span>
									                    </div>
		                                            </div>
		                                            
		                                            <div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 是否打小样： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"> <?php if($info['is_sample'] == 1) { echo '是('.$info['sample_color'].')'; } else { echo '否'; } ?></span>
									                    </div>
		                                            </div>
		                                            
		                                            <div class="col-xs-12" style="margin-top:20px;">
		                                        		<label class="col-sm-2 control-label no-padding-right" style="text-align:right;" for="form-field-2"> 备注： </label>
									                    <div class="col-sm-10">
									                    	<span class="editable editable-click"> <?php if($info['remark']) { echo $info['remark']; } else { echo '无'; } ?></span>
									                    </div>
		                                            </div>
	                                            
	                                            </div> 
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 您还没有录入制作信息！
                                                    </strong>
                                                </div>
                                            <?php endif;?>
                                        
                                             
                                        </div>
                                        <div id="inspect_img" class="tab-pane">
                                            <?php if(count($info['inspect_img']) > 0):?>
                                                <?php if($info['order_status'] != 8):?>
                                                <!-- <a class="btn btn-xs btn-info" href="/housesorders/check_upload_img/<?php echo $info['id'];?>" style="margin-bottom:10px">
                                                    修改验收图片
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </a> -->
                                                <?php endif;?>
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
                                                            <th class=" center">图片</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($info['inspect_img'] as $key => $value) :?>
                                                        <tr>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['point_code'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                                            <td style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                                            <td class="center">
                                                                <a href="<?php echo $value['front_img'];?>" target="_blank" title="点击查看原图">
                                                                    <img style="width: 215px; height: 150px" src="<?php echo $value['front_img'];?>">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 您还未上传验收图片！
                                                    </strong>
                                                    <?php if($info['order_status'] == 6):?>
                                                    <a class="btn btn-xs btn-info" href="/housesorders/check_upload_img/<?php echo $info['id'];?>">
                                                        立即上传
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </a>
                                                    <?php endif;?>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        <div id="change_pic_record" class="tab-pane">
                                            <?php if($info['change_pic_record']):?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">换画日期</th>
                                                        <th class="center">换画点位</th>
                                                        <th class="center">订单状态</th>
                                                        <th class="center">操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['change_pic_record'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo date('Y-m-d', strtotime($value['create_time']));?></td>
                                                        <td class="center"><?php echo count(array_unique(explode(',', $value['point_ids'])));?>个点位</td>
                                                        <td class="center">
                                                            <?php 
                                                                switch ($value['order_status']) {
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
                                                                <?php echo $status_text[$value['order_status']];?>
                                                            </span>
                                                        </td>
                                                        <td class="center">
                                                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                                <a class="green tooltip-info" href="/houseschangepicorders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                                                                    <i class="icon-eye-open bigger-130"></i>
                                                                </a> 
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="alert alert-warning center" style="width:400px">
                                                <strong>
                                                    <i class="icon-warning-sign bigger-120"></i> 该订单没有换画记录！
                                                </strong>
                                                <?php if($info['order_status'] == 7):?>
                                                <a class="btn btn-xs btn-info" href="/changepicorders/add/<?php echo $info['order_type'];?>/<?php echo $info['order_code'];?>">
                                                    立即添加
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </a>
                                                <?php endif;?>
                                            </div>
                                            <?php endif;?>
                                        </div>

                                        <?php if($info['order_type'] == 1):?>
                                        <div id="change_points_record" class="tab-pane">
                                            <?php if($info['change_points_record']):?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">换点日期</th>
                                                        <th class="center">换下点位</th>
                                                        <th class="center">换上点位</th>
                                                        <th class="center">换点之前验收函</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['change_points_record'] as $key => $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['operate_time'];?></td>
                                                        <td class="center"><?php echo $value['remove_points'];?></td>
                                                        <td class="center"><?php echo $value['add_points'];?></td>
                                                        <td class="center">
                                                            <a href="/orders/last_confirmation/<?php echo $value['id'];?>" target="_blank">查看</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="alert alert-warning center" style="width:400px">
                                                <strong>
                                                    <i class="icon-warning-sign bigger-120"></i> 该订单没有换点记录！
                                                </strong>
                                            </div>
                                            <?php endif;?>
                                        </div>
                                        <?php endif;?>
                                        
                                        <!-- 上画派单 begin -->
                                        <div id="assign_list" class="tab-pane">
                                        	<?php if($info['assign_list']) {?>
                                        	<table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">上画负责人</th>
                                                        <th class="center">总共个数</th>
                                                        <th class="center">完成个数</th>
                                                        <th class="center">状态</th>
                                                        <th class="center">验收结果</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<?php foreach($info['assign_list'] as $k => $v) {?>
                                            			<tr>
                                            				
                                            				<td class="center">
                                            					<?php foreach ($user_list as $key => $val):?>
                                            						<?php if($v['charge_user'] == $key):?>
                                            						<?php echo $val;?>
                                            						<?php endif;?>
                                            					<?php endforeach;?>
                                            				</td>
                                            				<td class="center">
                                            					<?php echo $v['total'];?>
                                            				</td>
                                            				<td class="center">
                                            					<?php echo $v['finish'];?>
                                            				</td>
                                            				<td class="center">
                                            					<?php if($v['status'] == 1){echo "已确认";}else{echo "未确认";}?>
                                            				</td>
                                            				
                                            				<td class="center">
																<?php if($v['total'] == $v['finish']){echo "系统验收通过";}else{echo "未全部通过验收";}?>
															</td>
                                            			</tr>
                                            		<?php }?>
                                        		</tbody>
                                        	</table>
                                            <?php }else {?>
                                            	<div class="alert alert-warning center" style="width:400px">
	                                                <strong>
	                                                    <i class="icon-warning-sign bigger-120"></i> 工程主管还在派单中
	                                                </strong>
	                                            </div>
                                            <?php }?>
                                            	
                                            
                                        </div>
                                        
                                        <!-- 上画派单 end-->
                                        
                                        <!-- 下画派单 begin-->
                                        <div id="assign_down_list" class="tab-pane">
                                        	<?php if($info['assign_down_list']) {?>
                                        	<table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">上画负责人</th>
                                                        <th class="center">总共个数</th>
                                                        <th class="center">完成个数</th>
                                                        <th class="center">状态</th>
                                                        <th class="center">操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<?php foreach($info['assign_down_list'] as $k => $v) {?>
                                            			<tr>
                                            				
                                            				<td class="center">
                                            					<?php foreach ($user_list as $key => $val):?>
                                            						<?php if($v['charge_user'] == $key):?>
                                            						<?php echo $val;?>
                                            						<?php endif;?>
                                            					<?php endforeach;?>
                                            				</td>
                                            				<td class="center">
                                            					<?php echo $v['total'];?>
                                            				</td>
                                            				<td class="center">
                                            					<?php echo $v['finish'];?>
                                            				</td>
                                            				<td class="center">
                                            					<?php if($v['status'] == 1){echo "已确认";}else{echo "未确认";}?>
                                            				</td>
                                            				
                                            				<td class="center">
																<?php if($v['total'] == $v['finish']){echo "系统验收通过";}else{echo "未全部通过验收";}?>
															</td>
                                            			</tr>
                                            		<?php }?>
                                        		</tbody>
                                        	</table>
                                            <?php }else {?>
                                            	<div class="alert alert-warning center" style="width:400px">
	                                                <strong>
	                                                    <i class="icon-warning-sign bigger-120"></i> 工程主管还在派单中
	                                                </strong>
	                                            </div>
                                            <?php }?>
                                            	
                                            
                                        </div>
                                    	<!-- 下画派单 end-->
                                    
                                    </div>
                                </div>

                                <?php if($info['order_status'] < 9):?>
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
                                <?php endif;?>
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
		var ban = $(this).attr('ban');
		
		layer.open({
			  type: 2,
			  title: '包含点位',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['60%', '60%'],
			  content: '/housesassign/show_points?order_id='+order_id+'&houses_id='+houses_id+'&ban='+ban //iframe的url
			}); 
	});

	$('.m-confirm').click(function(){
		var assign_id = $(this).attr('data-id');
		var order_id = $(this).attr('order-id');
		var houses_id = $(this).attr('houses-id');
		var ban = $(this).attr('ban');
		var assign_type = $(this).attr('assign_type');

		$("#confirmModal").modal('show');

		//通过
		$('#sub-confirm').click(function(){
			var confirm_remark  = $('#confirm-remark').val();
			$.post('/housesorders/confirm_upload', {assign_id:assign_id, order_id:order_id, confirm_remark:confirm_remark, mark:1, assign_type:assign_type, houses_id:houses_id, ban:ban}, function(data){
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
		var area_id = $(this).attr('area_id');
		var ban = $(this).attr('ban');

		layer.open({
			  type: 2,
			  title: '查看验收图片',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['80%', '80%'],
			  content: '/housesorders/check_upload_img?order_id='+order_id+'&assign_id='+id+'&houses_id='+houses_id+'&area_id='+area_id+'&ban='+ban //iframe的url
			}); 
	});

    //更新订单状态
    $(".status-step").click(function(){
        var order_id = $("#order_id").val();
        var status = $(this).attr("data-status");

		var now_status = "<?php echo $info['order_status']?>";

		if(now_status >= 5 && status <= 5) {
			var d = dialog({
                title: '提示信息',
                content: '该订单已经进入派单，不能更新状态！',
                okValue: '确定',
                ok: function () {
                },
            });
            d.width(320);
            d.showModal();
            return false;
		}
        
        if (adv_img_count == 0) {
            var d = dialog({
                title: '提示信息',
                content: '请联系业务人员先上传广告画面！',
                okValue: '确定',
                ok: function () {
                    //window.location.href = '/housesorders/upload_adv_img/<?php echo $info["id"];?>';
                },
            });
            d.width(320);
            d.showModal();
            return false;
        }


      	//录入制作信息
		if(status == 2 && adv_img_count != 0) {
			layer.open({
				  type: 2,
				  title: '录入制作信息',
				  shadeClose: true,
				  shade: 0.8,
				  area: ['70%', '70%'],
				  content: '/housesorders/insert_make_info/'+order_id //iframe的url
				}); 

			return false;
		}
        
		if(status == 4) {
			if(now_status == status) {
				return false;
			}
			
			var d = dialog({
                title: '提示信息',
                content: '工程主管正在派单中，当工程人员确认了派单此状态自动更新',
                okValue: '查看派单情况',
                ok: function () {
                    
                },
            });
            d.width(320);
            d.showModal();
            return false;
        }


		if(status == 5) {
			 var d = dialog({
               title: '提示信息',
               content: '工程人员上传验收图片中，当工程人员完成验收图片上传此状态自动更新',
               okValue: '查看上传情况',
               ok: function () {
                   
               },
           });
           d.width(320);
           d.showModal();
           return false;
       }
        


        if (status == 6 && inspect_img_count == 0) {
            var d = dialog({
                title: '提示信息',
                content: '您还没有上传验收图片进行验收！',
                okValue: '立即上传',
                ok: function () {
                    window.location.href = '/housesorders/check_upload_img/<?php echo $info["id"];?>';
                },
            });
            d.width(320);
            d.showModal();
            return false;
        }

		//提前主动变为下画派单

		if(status == 7 && !($(this).parent().hasClass('active'))) {
			var d = dialog({
                title: '提示信息',
                content: '该订单还没有到投放结束时间，您确认要提前进行下画派单吗？',
                okValue: '确认',
                ok: function () {
                	$("#exampleModal").modal('show');
                	$("#lock-add").click(function(){
                        var remark = $("#remark").val();
                        $.ajax( {
                            url:'/housesorders/ajax_update_status',
                            data: {
                                'id':order_id,
                                'status':status,
                                'remark':remark,
                                'order_code':$("#order_code").html()
                            },
                            type:'POST',
                            dataType:'json',
                            beforeSend:function(){},
                            success:function(data) {
                                if(data.status == 0){
                                   window.location.reload();
                                } else {
                                    $(".error_msg").html(data.msg);
                                    return false;
                                }
                                $("#exampleModal").modal('hide');
                            }
                        });
                    });
                },
            });
			d.width(320);
            d.showModal();
            return false;

		}else if(status == 7 && $(this).parent().hasClass('active')) {
			return false;
		}

        

        $("#exampleModal").modal('show');
        $("#lock-add").click(function(){
            var remark = $("#remark").val();
            $.ajax( {
                url:'/housesorders/ajax_update_status',
                data: {
                    'id':order_id,
                    'status':status,
                    'remark':remark,
                    'order_code':$("#order_code").html()
                },
                type:'POST',
                dataType:'json',
                beforeSend:function(){},
                success:function(data) {
                    if(data.status == 0){
                       window.location.reload();
                    } else {
                        $(".error_msg").html(data.msg);
                        return false;
                    }
                    $("#exampleModal").modal('hide');
                }
            });
        });


        
    });

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

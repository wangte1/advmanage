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
#points_lists tr:hover, #selected_points tr:hover{background:#6fb3e0;}
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
                    <li class="active">新建订单</li>
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
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?><?php echo $order_type_text[$order_type];?>订单
                        <a href="/orders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                    <span class="text-warning bigger-110 orange">
                        <i class="icon-warning-sign"></i>
                        注：投放中的订单只允许修改点位，移除的点位则该订单以及该订单下最近的一次换画订单对应的点位也将移除，移除的这些点位将释放出来，新增加的点位需重新上传该站台验收图片。
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

                                        <div class="form-group" id="order_info">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2">订单信息：</label>
                                            <div class="col-sm-10" style="padding:0">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单类型 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_type"><?php echo $order_type_text[$info['order_type']];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 总价 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="price"><?php echo $info['total_price'].'元';?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 客户 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="customer_name"><?php echo $info['customer_name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_name"><?php echo $info['sales']['name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员手机号 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_mobile"><?php echo $info['sales']['phone_number'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 投放时间 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="release_time"><?php echo $info['release_start_time'].'至'.$info['release_end_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 广告性质 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['adv_nature'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 制作公司 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['make_company'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 制作完成时间 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['make_complete_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
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

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 委托内容 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['leave_content']) { echo C('order.leave_content')[$info['leave_content']]; } else { echo '无'; } ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 安装类型 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['install_type']) { echo C('order.install_type')[$info['install_type']]; } else { echo '无'; } ?></span>
                                                        </div>
                                                    </div>

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
                                                                }
                                                            ?>
                                                            <span class="badge <?php echo $class; ?>">
                                                                <?php echo C('order.order_status.text')[$info['order_status']];?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放点位： </label>
                                            <div class="col-sm-10">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>选择点位</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:580px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘名称： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select  id="houses_id" class="select2 ">
                                                                            <option value="">请选择楼盘</option>
                                                                            <?php foreach($housesList as $val):?>
                                                                            <option value="<?php echo $val['id'];?>" <?php if(isset($info['houses_id']) && $val['id'] == $info['houses_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘区域： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="area_id" id="area_id" class="select2">
                                                                            <option value="">请选择楼盘区域</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-1" style="padding-left:0"> 状态： </label>
                                                                    <div class="col-sm-8" style="padding-left:0;padding-top: 10px">
                                                                        <select class="input-medium" id="point_status">
                                                                            <option value="1">空闲</option>
                                                                            <option value="2">预定</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-sm-2 center">点位编号</th>
                                                                                <th class="col-sm-3 center">楼盘名称</th>
                                                                                <th class="col-sm-3 center">楼盘区域</th>
                                                                                <th class="col-sm-2 center">规格</th>
                                                                                <th class="col-sm-2 center"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                <div class="div-tbody">
                                                                    <table id="sample-table-1" class="table table-bordered">
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
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <input type="hidden" name="order_code" value="<?php echo $info['order_code'];?>" />
                                                <input type="hidden" name="customer_id" value="<?php echo $info['customer_id'];?>" />
                                                <input type="hidden" name="point_ids_old" value="<?php echo $info['point_ids'];?>" />
                                                <?php endif;?>

                                                <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
                                                <input type="hidden" name="point_ids" value="<?php if(isset($info['point_ids'])) { echo $info['point_ids']; } ?>" />
                                                <?php if(isset($info['id'])):?>
                                                    <!--<?php foreach ($points_make_num as $key => $value): ?>
                                                    <input type="hidden" name="make_num[<?php echo $value['point_id'];?>]" value="<?php echo $value['make_num'];?>" />
                                                    <?php endforeach;?>-->
                                                <?php endif;?>
                                                <button class="btn btn-info" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    保 存
                                                </button>

                                                &nbsp; &nbsp; &nbsp;
                                                <button class="btn" type="reset">
                                                    <i class="icon-undo bigger-110"></i>
                                                    重 置
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
                                    已选择<span id="selected_points_num"><?php if(isset($selected_points)) { echo count($selected_points);} else { echo 0; }?></span>个点位（总价：<span id="total_price"><?php if(isset($info['total_price'])) { echo $info['total_price']; } else { echo "0.00"; } ?></span>元）
                                </span>
                            </div>

                            <div class="widget-body" style="height:1538px">
                                <div class="widget-main">
                                    <div id="scrollTable">
                                        <div class="div-thead">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-sm-2 center">点位编号</th>
                                                        <th class="col-sm-3 center">楼盘名称</th>
                                                        <th class="col-sm-3 center">楼盘区域</th>
                                                        <th class="col-sm-2 center">规格</th>
                                                        <th class="col-sm-2 center"><button class="btn btn-xs btn-info remove-all" type="button">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="div-tbody" style="height: 1466px">
                                            <table id="sample-table-1" class="table table-bordered">
                                                <tbody id="selected_points">
                                                    <?php if(isset($selected_points)):?>
                                                        <?php foreach($selected_points as $value):?>
                                                        <tr point-id="<?php echo $value['id'];?>">
                                                            <td class="col-sm-2 center"><?php echo $value['code'];?></td>
                                                            <td class="col-sm-3 center"><?php echo $value['houses_name'];?></td>
                                                            <td class="col-sm-3 center"><?php echo $value['houses_area_name'];?></td>
                                                            <td class="col-sm-2 center"><?php echo $value['size'];?></td>
                                                            <td class="col-sm-2 center"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除<i class="fa fa-remove" aria-hidden="true"></i></button></td>
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
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script>-->
<script type="text/javascript">
$(function(){
	
	var order_type = '<?php echo $order_type;?>';
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});
    $('#timepicker1').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime: '18:00:00'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
	
	$('#houses_id,#point_status,#area_id').change(function(){
		if($(this).attr('id') == 'houses_id') {
			$("#area_id").html('');
			$(".select2-chosen:eq(2)").text('全部');
		}

		var houses_id = $('#houses_id').val();
		var point_status = $('#point_status').val();
		var customer_id = $('#customer_id').val();

		$.post('/housesorders/get_points', {order_type:order_type, houses_id:houses_id, point_status:point_status, customer_id:customer_id}, function(data){
			var pointStr =  '';
			var areaStr = ''; 
			if(data.flag == true) {
				$("#all_points_num").text(data.count);
				
				for(var i = 0; i < (data.points_lists).length; i++) {
					pointStr += "<tr point-id='"+(data.points_lists)[i]['id']+"'><td class='col-sm-2 center'>"+(data.points_lists)[i]['code']+"</td>";
					pointStr += "<td class='col-sm-3 center'>"+(data.points_lists)[i]['houses_name']+"</td>";
					pointStr += "<td class='col-sm-3 center'>"+(data.points_lists)[i]['area_name']+"</td>";
					pointStr += "<td class='col-sm-2 center'></td>";
					pointStr += "<td class='col-sm-2 center'><button class='btn btn-xs btn-info do-sel' type='button'>选择<i class='icon-arrow-right icon-on-right'></button></td></tr>";
				}

				for(var j = 0; j < (data.area_lists).length; j++) {
					areaStr += "<option value="+(data.area_lists)[j]['id']+">"+(data.area_lists)[j]['name']+"</option>";
				}
			}

			$("#points_lists").html(pointStr);
			$("#area_id").html(areaStr);
		});
	});

	//选择点位
    $('#points_lists').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#selected_points"));
        $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

       	var numObj = $(this).parent().parent().find('td:eq(3)');
        var inputVal = numObj.children().val();
        numObj.text(inputVal);
        //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="'+inputVal+'">');
        
        $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
        var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');


        
        $("input[name='point_ids']").val(point_ids);
    });

  	//移除点位
    $('#selected_points').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#points_lists"));
        $("#selected_points_num").html(Number($("#selected_points_num").html()) - 1);

        
        //$('input[name="make_num['+$(this).parent().parent().attr('point-id')+']"]').remove();
       

        $("#points_lists button").html('选择<i class="icon-arrow-right icon-on-right"></i>');

        var point_ids = [];
        var _self = $(this);
        $("#selected_points tr").each(function(){
            point_ids.push($(this).attr('point-id'));
        });
        var ids = point_ids.length >= 1 ? point_ids.join(',') : '';
        $("input[name='point_ids']").val(ids);
    });

  	//选择全部
    $(".select-all").click(function(){
        $("#points_lists tr").each(function(){
            $(this).appendTo($("#selected_points"));
            $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

            var numObj = $(this).find('td:eq(3)');
            var inputVal = numObj.children().val();
            numObj.text(inputVal);
            //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).attr('point-id')+']" value="'+inputVal+'">');

            $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
            var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).attr('point-id') :  $(this).attr('point-id');
            $("input[name='point_ids']").val(point_ids);
        });
    });

  	//移除全部
    $(".remove-all").click(function(){
        $("#selected_points tr").each(function(){
            $(this).appendTo($("#points_lists"));
            $("#selected_points_num").html('0');

            //$('input[name="make_num['+$(this).attr('point-id')+']"]').remove();

            $("input[name='point_ids']").val('');
            $("#points_lists button").html('选择<i class="icon-arrow-right icon-on-right"></i>');
        });
    });

  	//保存
    $(".btn-save").click(function(){
        var point_ids = $("input[name='point_ids']").val();
        if (point_ids == '') {
            var d = dialog({
                title: '提示信息',
                content: '您还没有选择点位哦！',
                okValue: '确定',
                ok: function () {

                }
            });
            d.width(320);
            d.showModal();
            return false;
        }
    });

    
})
	

                                                
                                                
   // var order_type = "<?php echo $order_type;?>";
    //$('.popover-lock').popover({html:true, placement:'bottom'});
    //$('[data-rel=popover]').popover({html:true});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

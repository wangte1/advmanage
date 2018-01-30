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
                        <a href="/houseswantorders">意向订单管理</a>
                    </li>
                    <li class="active">新建意向订单</li>
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
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?><?php echo $order_type_text[$order_type];?>意向订单
                        <a href="/houseswantorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                          
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>
										<?php if(isset($info)):?>
                                        <input type="hidden" name="id" value="<?php echo $info['id']?>" />
                                        <?php endif;?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告客户： </label>
                                            <div class="col-sm-10">
                                                <select id="lock_customer_id" name="lock_customer_id" class="select2" required>
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['lock_customer_id']) && $val['id'] == $info['lock_customer_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 预定点位数量（个）： </label>
                                            <div class="col-sm-10">
                                                
                                                <input type="text" class="required">
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>
                                        

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 模糊条件： </label>
                                            <div class="col-sm-8">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>输入模糊条件</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:580px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                            	<div class="row">
                                                            		<div class="col-sm-12">
	                                                                    <label class="col-sm-2 control-label" for="form-field-1"> 行政区域： </label>
	                                                                    <div class="col-sm-10" style="padding:0">
	                                                                        <div id="distpicker1">
																			  <select name="province" id="province"></select>
																			  <select name="city" id="city"></select>
																			  <select name="area" id="area"></select>
																			</div>
	                                                                    </div>
	                                                                </div>
                                                            	</div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘类型： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                    	<?php foreach(C('public.houses_type') as $k => $v) {?>
	                                                                    		<label style="margin-top:10px;margin-right:10px;"><input class="m-checkbox" name="houses_type" type="checkbox" value="<?php echo $k;?>"><?php echo $v;?></label>
	                                                                    	<?php }?>
										                                	
	                                                                    </div>
	                                                                </div>
	                                                                
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 交房年份： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                        <select id="begin_year" name="begin_year" ></select>
	                                                                       	至
	                                                                        <select id="end_year" name="end_year" ></select>
	                                                                    </div>
	                                                                </div>
                                                                </div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 投放行业： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<select id="put_trade" name="put_trade">
										                                		<option value="0">无</option>
										                                		<?php foreach (C('housespoint.put_trade') as $k => $v):?>
										                                			<option value="<?php echo $k;?>"><?php echo $v;?></option>
										                                   		<?php endforeach;?>
										                                	</select>
										                                </div>
										                            </div>
										                            
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 点位类型： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<?php foreach (C('order.houses_order_type') as $k => $v):?>
										                                    <label class="blue" style="margin-top:5px;">
										                                        <input name="order_type" value="<?php echo $k;?>" required type="radio" class="ace m-radio" <?php if($k == 1){echo 'checked="checked"';}?> />
										                                        <span class="lbl"> <?php echo $v;?> </span>
										                                    </label>
										                                    &nbsp;
										                                   <?php endforeach;?>
										                                </div>
										                            </div>
	                                                             </div>
                                                                
                                                            </div>
                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="10%">序号</th>
                                                                                <th width="10%">楼盘</th>
                                                                                <th width="10%">点位数量</th>
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
                                                <textarea class="form-control" name="remarks" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div style="text-align: center;">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <?php endif;?>
                                                <button class="btn btn-info btn-save" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    	提交
                                                </button>
                                              
                                            </div>
                                        </div>
                                    </form>
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
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script> -->
<script type="text/javascript">

window.onload=function(){ 
	//设置年份的选择 
	var myDate= new Date(); 
	var year=myDate.getFullYear(); 
	var startYear=myDate.getFullYear()-50;//起始年份 
	var endYear=myDate.getFullYear()+50;//结束年份 
	var obj1=document.getElementById('begin_year');
	var obj2=document.getElementById('end_year') 
	for (var i=startYear;i<=endYear;i++) { 
		obj1.options.add(new Option(i,i)); 
		obj2.options.add(new Option(i,i)); 
	} 

	$('#begin_year option').each(function(){
		if($(this).text() == year-10) {
			$(this).attr('selected', 'selected');
		}
	});

	$('#end_year option').each(function(){
		if($(this).text() == year) {
			$(this).attr('selected', 'selected');
		}
	});

	
} 
                                                    
$("#distpicker1").distpicker({
	province: '贵州省',
	city: '贵阳市',
	//district: ''
});

                                                    
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});

	$('#province, #city, #area, .m-checkbox, #begin_year, #end_year, #put_trade, .m-radio').change(function(){
		var province = $('#province').val();
		var city = $('#city').val();
		var area = $('#area').val();
		var houses_type = $('.m-checkbox:checked').val();
		var begin_year = $('#begin_year').val();
		var end_year = $('#end_year').val();
		var put_trade = $('#put_trade').val();
		var order_type = $('.m-radio:checked').val();
		alert(order_type);

		var postData = {province:province, city:city, area:area, houses_type:houses_type, begin_year:begin_year, end_year:end_year, put_trade:put_trade,order_type:order_type};

		$.post('/houseswantorders/get_points', postData, function(data){
			console.log(data);
		});
		
	});

	
	$('#houses_id,#area_id,#ban,#unit,#floor,#addr').change(function(){
		var houses_id = $('#houses_id').val();
		var ban = $('#ban').val();
		var unit = $('#unit').val();
		var floor = $('#floor').val();
		var addr = $('#addr').val();
		var lock_start_time = $('#lock_start_time').val();
		var postData = {order_type:order_type, put_trade:put_trade, houses_id:houses_id, ban:ban, unit:unit, floor:floor, lock_start_time:lock_start_time,addr:addr};
		$.post('/houseswantorders/get_points', postData, function(data){
			if(data.flag == true && data.count > 0) {
				$("#all_points_num").text(data.count);
				var tmpList = data.points_lists
				for(var i = 0; i < data.points_lists.length; i++) {
					pointStr += "<tr point-id='"+tmpList[i]['id']+"'><td width='10%'>"+tmpList[i]['code']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['houses_name']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['area_name']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['ban']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['unit']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['floor']+"</td>";
					if(tmpList[i]['addr'] == 1){
						pointStr += "<td width='10%'>门禁</td>";
					}else{
						pointStr += "<td width='10%'>电梯前室</td>";
					}
					
					pointStr += "<td width='10%'>"+tmpList[i]['size']+"</td>";
					switch (tmpList[i]['point_status']) {
                        case '1':
                            $class = 'badge-success';
                            break;
                        case '3':
                            $class = 'badge-danger';
                            break;
                	}
					pointStr += "<td width='10%'><span class='badge "+$class+"'>"+tmpList[i]['point_status_txt']+"</span></td>";
					pointStr += "<td width='10%'><button class='btn btn-xs btn-info do-sel' type='button'>选择点位<i class='icon-arrow-right icon-on-right'></button></td></tr>";
				}
				$('#area').html();
				for(var j = 0; j < data.area_list.length; j++) {
					areaStr += "<option value="+data.area_list[j]['id']+">"+data.area_list[j]+"</option>";
				}
			}else{
				alert('暂无可预约 <?php echo $order_type_text[$order_type];?> 点位');
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
        
        $("#selected_points button").html('移除点位<i class="fa fa-remove" aria-hidden="true"></i>');
        var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');


        
        $("input[name='point_ids']").val(point_ids);
    });

  	//移除点位
    $('#selected_points').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#points_lists"));
        $("#selected_points_num").html(Number($("#selected_points_num").html()) - 1);

        
        //$('input[name="make_num['+$(this).parent().parent().attr('point-id')+']"]').remove();
       

        $("#points_lists button").html('选择点位<i class="icon-arrow-right icon-on-right"></i>');

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

            $("#selected_points button").html('移除点位<i class="fa fa-remove" aria-hidden="true"></i>');
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
            $("#points_lists button").html('选择点位<i class="icon-arrow-right icon-on-right"></i>');
        });
    });

  	//保存
    $(".btn-save").click(function(){
        var point_ids = $("input[name='point_ids']").val();
        var lock_customer_id = $('#lock_customer_id').val();
        var sales_id = $('#sales_id').val();
        if (lock_customer_id == '') {
            alert('请选择客户！');
            return false;
        }
        if (sales_id == '') {
            alert('请选择业务员！');
            return false;
        }
        if (point_ids == '') {
            alert('您还没有选择点位哦！');
            return false;
        }
    });

    function alert(msg){
    	var d = dialog({
            title: '提示信息',
            content: msg,
            okValue: '确定',
            ok: function () {

            }
        });
        d.width(320);
        d.showModal();
    }
})

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

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
    
    .margin5{
    	margin-top: 5px;
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
                    <li class="active">意向订单详情</li>
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
                        意向订单详情
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
                                            <div class="col-sm-4">
                                            	<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $info['customer_id']?>">
                                               	<?php foreach($customers as $val):?>
                                              		<?php if(isset($info['customer_id']) && $val['id'] == $info['customer_id']){?>
                                                    	<input readonly="readonly" type="text" value="<?php echo $val['name'];?>">
                                                	<?php }?>
                                             	<?php endforeach;?>
                                                
                                            </div>
                                            
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 预定点位数量（个）： </label>
                                            <div class="col-sm-4">
                                            	<input readonly="readonly"  type="text" id="points_count" name="points_count" value="<?php echo $info['points_count']?>">
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 模糊条件： </label>
                                            <div class="col-sm-8">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>模糊条件</h4>
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
	                                                                        	<input type="text" readonly="readonly" name="province" id="province" value="<?php echo $info['province'];?>">
	                                                                        	<input type="text" readonly="readonly" name="city" id="city" value="<?php echo $info['city'];?>">
	                                                                        	<input type="text" readonly="readonly" name="area" id="area" value="<?php echo $info['area'];?>">
																			</div>
	                                                                    </div>
	                                                                </div>
                                                            	</div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘类型： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                    	<input type="hidden" id="houses_type" name="houses_type">
	                                                                    	<?php foreach(C('public.houses_type') as $k => $v) {?>
	                                                                    		<label style="margin-top:10px;margin-right:10px;"><input disabled="disabled" class="m-checkbox" name="s_houses_type" type="checkbox" <?php if(in_array($k, explode(',',$info['houses_type']))){?>checked="checked"<?php }?> value="<?php echo $k;?>"><?php echo $v;?></label>
	                                                                    	<?php }?>
										                                	
	                                                                    </div>
	                                                                </div>
	                                                                
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 交房年份： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                    	<input readonly="readonly" type="text" id="begin_year" name="begin_year" value="<?php echo $info['begin_year']?>">
	                                                                       	至
	                                                                       	<input readonly="readonly" type="text" id="end_year" name="end_year" value="<?php echo $info['end_year']?>">
	                                                                    </div>
	                                                                </div>
                                                                </div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 投放行业： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<input type="hidden" id="put_trade" name="put_trade" value="<?php echo $info['put_trade']?>">
										                                	<?php foreach (C('housespoint.put_trade') as $k => $v):?>
										                                		<?php if($k == $info['put_trade']) {?>
										                                			<input type="text" readonly="readonly" value="<?php echo $v;?>">
										                                		<?php }?>
									                                   		<?php endforeach;?>
										                                </div>
										                            </div>
										                            
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 点位类型： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<input type="hidden" id="order_type" name="order_type" value="<?php echo $info['order_type']?>">
										                                	<?php foreach (C('order.houses_order_type') as $k => $v):?>
										                                		<?php if($k == $info['order_type']) {?>
										                                			<input type="text" readonly="readonly" value="<?php echo $v;?>">
										                                		<?php }?>
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
                                                                                <th width="20%">序号</th>
                                                                                <th width="60%">楼盘</th>
                                                                                <th width="20%">空闲点位数量</th>
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
                                                <label class="margin5"><?php echo $info['remark'];?></label>
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
	province: '<?php if($info['province']) {echo $info['province'];}?>',
	city: '<?php if($info['city']) {echo $info['city'];}?>',
	district: '<?php if($info['area']) {echo $info['area'];}?>'
});


function get_checkbox(){
	
    obj = document.getElementsByName("s_houses_type");
    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
	$('#houses_type').val(check_val.toString());
    return check_val.toString();
    
}

function load_houses() {
	var province = $('#province').val();
	var city = $('#city').val();
	var area = $('#area').val();
	var houses_type = get_checkbox();	//获取楼盘类型
	var begin_year = $('#begin_year').val();
	var end_year = $('#end_year').val();
	var put_trade = $('#put_trade').val();
	var order_type = $('#order_type').val();

	var postData = {province:province, city:city, area:area, houses_type:houses_type, begin_year:begin_year, end_year:end_year, put_trade:put_trade,order_type:order_type};
	$.post('/houseswantorders/get_houses', postData, function(data){
		console.log(data);
		if(data.flag == true && data.count > 0) {
			var pointStr = '';
			$("#all_points_num").text(data.count);
			for(var i = 0; i < data.houses_lists.length; i++) {
				pointStr += "<tr><td width='20%'>"+(i+1)+"</td>";
				pointStr += "<td width='60%'>"+data.houses_lists[i]['houses_name']+"</td>";
				pointStr += "<td width='20%'>"+data.houses_lists[i]['count']+"</td>";
			}
		}else{
			alert('暂无空闲点位');
		}

		$("#points_lists").html('');
		$("#points_lists").html(pointStr);
	});
}
                                                    
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});

    load_houses();
	/*$('#province, #city, #area, .m-checkbox, #begin_year, #end_year, #put_trade, .m-radio').change(function(){

		var province = $('#province').val();
		var city = $('#city').val();
		var area = $('#area').val();
		var houses_type = get_checkbox();	//获取楼盘类型
		var begin_year = $('#begin_year').val();
		var end_year = $('#end_year').val();
		var put_trade = $('#put_trade').val();
		var order_type = $('.m-radio:checked').val();

		var postData = {province:province, city:city, area:area, houses_type:houses_type, begin_year:begin_year, end_year:end_year, put_trade:put_trade,order_type:order_type};

		$.post('/houseswantorders/get_points', postData, function(data){
			if(data.flag == true && data.count > 0) {
				var pointStr = '';
				$("#all_points_num").text(data.count);
				for(var i = 0; i < data.houses_lists.length; i++) {
					pointStr += "<tr><td width='20%'>"+(i+1)+"</td>";
					pointStr += "<td width='60%'>"+data.houses_lists[i]['houses_name']+"</td>";
					pointStr += "<td width='20%'>"+data.houses_lists[i]['count']+"</td>";
				}
			}else{
				alert('暂无空闲点位');
			}

			$("#points_lists").html('');
			$("#points_lists").html(pointStr);
		});
		
	});*/

  	//保存
    $(".btn-save").click(function(){
    	if($('#customer_id').val() == '') {
			alert('请选择客户');
			return;
        }

    	if($('#points_count').val() == '') {
			alert('请输入预定点位数量');
			return;
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

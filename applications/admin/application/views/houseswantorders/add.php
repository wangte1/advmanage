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
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?>意向订单
                        <a href="/houseswantorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="">
                          
                            <div class="">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>
										<?php if(isset($info)):?>
                                        <input type="hidden" name="id" value="<?php echo $info['id']?>" />
                                        <?php endif;?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告客户： </label>
                                            <div class="col-sm-4">
                                                <select id="customer_id" name="customer_id" class="select2" required>
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['customer_id']) && $val['id'] == $info['customer_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                            
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 预定点位数量（个）： </label>
                                            <div class="col-sm-4">
                                                
                                                <input type="text" name="points_count" required>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            
                                        </div>
                                        

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 模糊条件： </label>
                                            <div class="col-sm-8">
                                                <div class="">
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
																			  	<!-- <select name="area" id="area"></select> -->
																				<select id="area" multiple="multiple"></select>
																				<input id="hid-area" name="area" type="hidden" value="">
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
	                                                                    		<label style="margin-top:10px;margin-right:10px;"><input class="m-checkbox" name="s_houses_type" type="checkbox" value="<?php echo $k;?>"><?php echo $v;?></label>
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
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
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


<style>
.btn-default {
	background-color: #fff;
}
</style>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script> -->
<script src="<?php echo css_js_url('bootstrap-multiselect.js','admin');?>"></script>
<link href="<?php echo css_js_url('bootstrap-multiselect.css', 'admin');?>" rel="stylesheet" />
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
	district: ''
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
                                                    
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});

	
	$('#province, #city, #area, .m-checkbox, #begin_year, #end_year, #put_trade, .m-radio').change(function(){

		var area_str = '';
		$('.multiselect-container .active input[type="checkbox"]:checked').each(function(){
			if($(this).val() != 'multiselect-all') {
				area_str += $(this).val()+',';
			}
		});
		$('#hid-area').val(area_str);
		
		var province = $('#province').val();
		var city = $('#city').val();
		var area = area_str;
		var houses_type = get_checkbox();	//获取楼盘类型
		var begin_year = $('#begin_year').val();
		var end_year = $('#end_year').val();
		var put_trade = $('#put_trade').val();
		var order_type = $('.m-radio:checked').val();

		var postData = {province:province, city:city, area:area, houses_type:houses_type, begin_year:begin_year, end_year:end_year, put_trade:put_trade,order_type:order_type};

		$.post('/houseswantorders/get_houses', postData, function(data){
			if(data.flag == true && data.count > 0) {
				var pointStr = '';
				$("#all_points_num").text(data.count);
				for(var i = 0; i < data.houses_lists.length; i++) {
					pointStr += "<tr><td width='20%'>"+(i+1)+"</td>";
					pointStr += "<td width='60%'>"+data.houses_lists[i]['houses_name']+"</td>";
					pointStr += "<td width='20%'>"+data.houses_lists[i]['count']+"</td>";
				}
			}else{
				layer.alert('暂无空闲点位');
			}

			$("#points_lists").html('');
			$("#points_lists").html(pointStr);
		});
		
	});

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
        
<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#area').multiselect({
        	includeSelectAllOption: true,
        	selectAllText : '选择所有',
        	nonSelectedText : '请选择区域',
        	allSelectedText : '选择所有'
        });

        $('#area option:eq(0)').remove();
        $('#area option:eq(0)').attr("selected",false);

        $('.multiselect-container li:eq(1)').remove();
        $('.multiselect-container li:eq(1)').removeClass('active');
        $('.multiselect-container li:eq(1)').find('input[type="checkbox"]').prop("checked",false);

        $('.multiselect').attr('title','请选择区域');
        $('.multiselect-selected-text').text('请选择区域');
         
    });

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

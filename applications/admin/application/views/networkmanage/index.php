<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>
<style>


/*#table td:hover{
	 background-color: green;
}*/

.font-red {
	color: red;
}

table tbody {
    display:block;
    overflow-y:scroll;
}
table thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}

table thead {
    width: calc( 100% - 1.3em );
}

thead th {
	color: #000;
	background-color: #fff;
}

tbody td {
	border: none;
}

#table th,td{
	 text-align:center; /*设置水平居中*/
     vertical-align:middle;/*设置垂直居中*/
     cursor:pointer
}

</style>


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
                        <a href="#">资源管理</a>
                    </li>
                    <li class="active">网络排班</li>
                </ul>

            </div>
			
			<form action="/networkmanage/exportExcel" method="post" >
			<input id="year" name="year"  type="hidden" value="<?php echo $year?>">
			<input id="month" name="month" type="hidden" value="<?php echo $month?>">
			<input id="days" name="days"  type="hidden" value="<?php echo $days?>">
			<input id="mod" name="mod" type="hidden" value="<?php echo $mod?>">
			
			<input id="gobase" name="gobase" type="hidden" value="<?php if(isset($gobase))echo $gobase?>">
		
            <div class="page-content">
            
                <div class="">
					<ul class="nav nav-tabs" id="myTab" style="height:34px;">
						<?php foreach($list1 as $key1 => $val1) {?>
							<li <?php if($key1 == 0) {?>class="active"<?php }?>>
								<a data-toggle="tab" class="m-tab-a" mid="<?php echo $val1['id']?>" mkey="<?php echo $key1?>"  href="#tabpanel<?php echo $key1?>">
									<?php echo $val1['name']?>
								</a>
							</li>
						<?php }?>
						
						<li style="float:right;">
							<select id="myMonth" onchange="changePage();">
								<?php for($i=0; $i<12; $i++) {?>
								<option <?php if($month == ($i+1)) {?>selected="selected" <?php }?>><?php echo $i+1?></option>
								<?php }?>
							</select>
						</li>
						
						<li style="float:right;">
							<select id="myYear" onchange="changePage();">
							</select>
						</li>

						
					</ul>

					<div id="tab-content" class="tab-content" style="overflow:auto;">
						
						<?php foreach($list1 as $key1 => $val1) {?>
						<?php if($key1 == 0) {?>
						<div id="tabpanel<?php echo $key1?>" class="tab-pane in <?php if($key1 == 0) {?>active<?php }?>">
							
							<table id="table" class="table table-bordered">
						     
						      <thead>
						      	<tr>
						          <th rowspan="3" width="15%">投放位置</th>
						          <!-- <th rowspan="3" width="5%">广告形式</th> -->
						          <th rowspan="3" width="7%">格式</th>
						          <th colspan="<?php echo $days+2?>"><?php echo $year?>年<?php echo $month?>月</th>
						          <!-- <th rowspan="3">单价</th>
						          <th rowspan="3">总价</th>
						          <th rowspan="3">折扣</th>
						          <th rowspan="3">净价</th> -->
						        </tr>
						        
						        <tr>
						        	<th>日期</th>
				                    <?php for($i=0; $i<$days; $i++){?>
				                    	<th><?php echo $i+1 ?></th>
				                    <?php }?>
				                    <th rowspan="2">天数</th>
						        </tr>
						        
						        <tr>
						        	<th>星期</th>
				                    <?php for($i=0; $i<$days; $i++){?>
				                    	<th <?php if($weeks[$i] == '六' || $weeks[$i] == '日') { ?>class="font-red"<?php }?>><?php echo $weeks[$i]?></th>
				                    <?php }?>
						        </tr>
						      </thead>	
						     
						      <tbody>
						      	<?php foreach($list2 as $key2 => $val2) {?>
						      	<?php if($val2['type'] == $val1['id']) {?>
							        <tr>
							          <td width="15%"><?php echo $val2['name']?></td>
							          <!-- <td width="5%"><?php echo $val2['adform']?></td> -->
							          <td width="7%"><?php echo $val2['format']?></td>
							          <td></td>
							          <?php for($i=0; $i<$days; $i++){?>
							          	   <?php if(isset($newArr[$key2][$i+1])) {?>
							          	   		<?php if($newArr[$key2][$i+1]['status'] == 'order') {?>
							          	   			<td class="date-td date-order" style="background-color:#FF9900;"  title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：<?php echo $newArr[$key2][$i+1]['customer']?>">
							          	   				<?php if($roleid == 3) {?>
							          	   					<input id="<?php echo $val2['id']?>-<?php echo $i+1?>" base_id="<?php echo $val2['base_id']?>" type="checkbox" style="display:none;margin:0;">
							          	   				<?php }?>
							          	   			</td>
							          	   		<?php }else {?>
								          	   		<td class="date-td date-used" style="background-color:green;" title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：<?php echo $newArr[$key2][$i+1]['customer']?>">
								          	   			<?php if($roleid == 3) {?>
								          	   			<input id="<?php echo $val2['id']?>-<?php echo $i+1?>" type="checkbox" style="display:none;margin:0;">
								          	   			<?php }?>
								          	   		</td>
							          	   		<?php }?>
							          	   <?php }else {?>
								          	   	<td class="date-td date-free" onclick="setCheckBox(this);">
				                    	   			<input onclick="setInput(this);"  id="<?php echo $val2['id']?>-<?php echo $i+1?>" base_id="<?php echo $val2['base_id']?>" type="checkbox" style="display:none;margin:0;cursor:pointer;">
				                    	   		</td>
							          	   <?php }?>
				                    	  
					                  <?php }?>
							          <td></td>
							          <!-- <td><?php echo $val2['unitprice']?></td>
							          <td><?php echo $val2['totalprice']?></td>
							          <td><?php echo $val2['discount']?></td>
							          <td><?php echo $val2['netprice']?></td> -->
							        </tr>
						        <?php }?>
						        <?php }?>
						       
						      </tbody>
						    </table>
						</div>
						<?php break;?>
						<?php }?>
						<?php }?>
					
					</div>
				</div>
				
				<div style="float:left;">
					<span style="float:left;display:block;width:30px;height:20px;line-height:30px;color:#fff;text-align:center;background-color:#FF9900"></span><span style="float:left;">&nbsp;已预定&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<span style="float:left;display:block;width:30px;height:20px;line-height:30px;color:#fff;text-align:center;background-color:green"></span><span style="float:left;">&nbsp;已上画</span>
				
				</div>
				
				<div style="float:right;">
					<button type="button" onclick="exportExcel();" class="btn btn-info btn-sm">导出Excel</button>
					<button type="button" onclick="order();" class="btn btn-info btn-sm">申请预定</button>
					<?php if($roleid == 3) {?>
					<button type="button" onclick="unOrder();" class="btn btn-info btn-sm">取消预定</button>
					<button type="button" onclick="used();" class="btn btn-info btn-sm">上画</button>
					
					<button type="button" onclick="unUsed();" class="btn btn-info btn-sm">取消上画</button>
					<?php }?>
				</div>
				
            </div>
            </form>
        </div>
    </div>
</div>


<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>


<script language="javascript" type="text/javascript"> 
	function setInput(obj) {
		if($(obj).prop("checked") == true) {
			$(obj).prop("checked", false);
		}else {
			$(obj).prop("checked", true);
		}
	}


	function setCheckBox(obj) {
		if($(obj).children('input').prop("checked") == true) {
			$(obj).children('input').prop("checked", false);
		}else {
			$(obj).children('input').prop("checked", true);
		}
	}


	function changePage() {
		if($('#mod').val() == 1) {
			location.href='/networkmanage/goindex1?year='+$('#myYear').val()+'&month='+$('#myMonth').val();
		}else {
			location.href='/networkmanage/goindex2?year='+$('#myYear').val()+'&month='+$('#myMonth').val();
		}
		
	}
				                  
	window.onload=function(){ 
		//设置年份的选择 
		var myDate= new Date(); 
		var startYear=myDate.getFullYear()-10;//起始年份 
		var endYear=myDate.getFullYear()+10;//结束年份 
		var obj=document.getElementById('myYear') 
		for (var i=startYear;i<=endYear;i++) { 
			obj.options.add(new Option(i,i)); 
		} 

		$('#myYear option').each(function(){
			if($(this).text() == <?php echo $year?>) {
				$(this).attr('selected', 'selected');
			}
		});
	} 

	
</script> 

<script>

$(function(){

	$(document).on('mouseover', '.date-td',function(){
		$(this).children('input').css("display","");
	});

	$(document).on('mouseout', '.date-td',function(){
		if($(this).children('input').is(':checked')) {

		}else {
			$(this).children('input').css("display","none");	
		}	
	});

	var contentH = $(window).height() - 230;
	$('.tab-content').height(contentH);
	$('table tbody').height($('.tab-content').height()-170);

	$('.m-tab-a').click(function(){
		var id = $(this).attr('mid');
		var key = $(this).attr('mkey');
		
		var tmpStr = '<div id="tabpanel'+key+'" class="tab-pane in active"><table id="table" class="table table-bordered">';
		tmpStr += '<thead>';
		tmpStr += '<tr>';
		tmpStr += '<th rowspan="3" width="15%">投放位置</th>';
		tmpStr += '<th rowspan="3" width="7%">格式</th>';
		tmpStr += '<th colspan="<?php echo $days+2?>"><?php echo $year?>年<?php echo $month?>月</th>';
		tmpStr += '</tr>';
		tmpStr += '<tr><th>日期</th>';
	        <?php for($i=0; $i<$days; $i++){?>
	    tmpStr += '<th><?php echo $i+1 ?></th>';
	        <?php }?>
	    tmpStr += '<th rowspan="2">天数</th></tr><tr><th>星期</th>';
	        <?php for($i=0; $i<$days; $i++){?>
	    tmpStr += '<th <?php if($weeks[$i] == '六' || $weeks[$i] == '日') { ?>class="font-red"<?php }?>><?php echo $weeks[$i]?></th>';
	        <?php }?>
	    tmpStr += '</tr></thead><tbody>';

	    
      	<?php foreach($list2 as $key2 => $val2) {?>

      	if(id == <?php echo $val2['type']?>) {
      	
      		tmpStr += '<tr><td width="15%"><?php echo $val2['name']?></td><td width="7%"><?php echo $val2['format']?></td><td></td>';
	          <?php for($i=0; $i<$days; $i++){?>
	          	   <?php if(isset($newArr[$key2][$i+1])) {?>
	          	   		<?php if($newArr[$key2][$i+1]['status'] == 'order') {?>
	          	   	tmpStr += '<td class="date-td date-order" style="background-color:#FF9900;"  title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：<?php echo trim($newArr[$key2][$i+1]['customer']);?>">';
	          	   				<?php if($roleid == 3) {?>
	          	   			tmpStr += '<input id="<?php echo $val2['id']?>-<?php echo $i+1?>" base_id="<?php echo $val2['base_id']?>" type="checkbox" style="display:none;margin:0;">';
	          	   				<?php }?>
	          	   			tmpStr += '</td>';
	          	   		<?php }else {?>
						
		          	   	var tmpCustomer = "<?php echo trim($newArr[$key2][$i+1]['customer']);?>";
	          	   	tmpStr += '<td class="date-td date-used" style="background-color:green;" title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：'+tmpCustomer+'">';
		          	   			<?php if($roleid == 3) {?>
		          	   		tmpStr += '<input id="<?php echo $val2['id']?>-<?php echo $i+1?>" type="checkbox" style="display:none;margin:0;">';
		          	   			<?php }?>
		          	   		tmpStr += '</td>';
	          	   		<?php }?>
	          	   <?php }else {?>
	          	 tmpStr += '<td class="date-td date-free" onclick="setCheckBox(this);"><input onclick="setInput(this);" id="<?php echo $val2['id']?>-<?php echo $i+1?>" base_id="<?php echo $val2['base_id']?>" type="checkbox" style="display:none;margin:0;cursor:pointer;"></td>';
	          	   <?php }?>
            	  
              <?php }?>
              tmpStr += '<td></td></tr>';
      	}
        <?php }?>
       

      	tmpStr += '</tbody></table></div>';

		if($("#tabpanel"+key).length == 0) {
			$('.tab-pane').removeClass("active");
			$('#tab-content').append(tmpStr);
		}

		$('table tbody').height($('.tab-content').height()-170);
	})

	
		
});

//导出excel表
function exportExcel() {
	$('#excelcontent').val($('.tab-pane:first').html());
	console.log($('.tab-pane:first').html());
	$('form').submit();
}

//预定
function order() {
	var mark = false;
	var checkStr = '';
	var baseid = '';

	$('.date-free input[type="checkbox"]').each(function(){
		if($(this).is(':checked')) {
			mark = true;
			checkStr += $(this).attr('id') + ',';
			baseid += $(this).attr('base_id') + ',';
		}
	});

	if(mark == false) {
		layer.alert('您还没有选择预定！');
		return;
	}

	//alert(checkStr);
	var year = $('#year').val();
	var month = $('#month').val();
	var days = $('#days').val();
	var gobase = $('#gobase').val();

	
	layer.prompt({title: '输入预定客户', formType: 2}, function(pass, index){
		layer.close(index);
		var index = layer.load(1, {
			  shade: [0.5,'#fff'] //0.1透明度的白色背景
			});
		/*$.post('/networkmanage/order',{checkstr:checkStr, customer:pass, year:year, month:month, days:days, gobase:gobase, baseid:baseid},function(data){
			if(data.code == 1) {
				layer.alert(data.msg,function(){
					//location.reload();
					location.href='/networkapply/index';
				});
			}else {
				layer.alert(data.msg);
			}
		});*/

		$.ajax({
		　　url:'/networkmanage/order',  //请求的URL
		　　timeout : 5000, //超时时间设置，单位毫秒
		　　type : 'post',  //请求方式，get或post
		　　data :{checkstr:checkStr, customer:pass, year:year, month:month, days:days, gobase:gobase, baseid:baseid},  //请求所传参数，json格式
		　　dataType:'json',//返回的数据格式
		　　success:function(data){ //请求成功的回调函数
		　　　　	if(data.code == 1) {
					layer.alert(data.msg,function(){
						//location.reload();
						location.href='/networkapply/index';
					});
				}else {
					layer.alert(data.msg);
				}
		　　},
		　　complete: function (XMLHttpRequest,status) {
                if(status == 'timeout') {
                    xhr.abort();    // 超时后中断请求
                    layer.alert("网络超时，请刷新", function () {
                        location.reload();
                    })
                }
           }
		});

	
	});
	//alert(checkStr);
}

//取消预定
function unOrder() {
	var mark = false;
	var checkStr = '';

	$('.date-order input[type="checkbox"]').each(function(){
		if($(this).is(':checked')) {
			mark = true;
			checkStr += $(this).attr('id') + ',';
		}
	});

	if(mark == false) {
		layer.alert('您还没有选择将要取消的预定！');
		return;
	}

	//alert(checkStr);
	var year = $('#year').val();
	var month = $('#month').val();
	var days = $('#days').val();
	
	$.post('/networkmanage/unOrder',{checkstr:checkStr,year:year,month:month,days:days},function(data){
		if(data.code == 1) {
			layer.alert(data.msg,function(){
				location.reload();
			});
		}
	});
	
}

//确定占用
function used() {
	
	var mark = false;
	var checkStr = '';

	var baseid = '';

	$('.date-order input[type="checkbox"]').each(function(){
		if($(this).is(':checked')) {
			mark = true;
			checkStr += $(this).attr('id') + ',';
			baseid += $(this).attr('base_id') + ',';
		}
	});
	
	var year = $('#year').val();
	var month = $('#month').val();
	var days = $('#days').val();
	var gobase = $('#gobase').val();

	if(mark == false) {
		layer.alert('您还没有选择已预定！');
		return;
	}

// 	alert(checkStr);
	$.post('/networkmanage/used',{checkstr:checkStr, year:year, month:month, days:days, baseid:baseid},function(data){
		
		console.log(data);
		if(data.code == 1) {
			layer.alert(data.msg,function(){
				location.reload();
			});
		}
	});
}

//取消占用
function unUsed() {
	var mark = false;
	var checkStr = '';

	$('.date-used input[type="checkbox"]').each(function(){
		if($(this).is(':checked')) {
			mark = true;
			checkStr += $(this).attr('id') + ',';
		}
	});

	if(mark == false) {
		layer.alert('您还没有选择将要取消的占用！');
		return;
	}

	//alert(checkStr);
	var year = $('#year').val();
	var month = $('#month').val();
	var days = $('#days').val();
	
	$.post('/networkmanage/unUsed',{checkstr:checkStr,year:year,month:month,days:days},function(data){
		if(data.code == 1) {
			layer.alert(data.msg,function(){
				location.reload();
			});
		}
	});
}

function clickCheck(obj) {
	if($(obj).children('input').is(':checked')) {
		$(obj).children('input').attr("checked", false);
	}else {
		$(obj).children('input').attr("checked", true);
	}
}
</script>
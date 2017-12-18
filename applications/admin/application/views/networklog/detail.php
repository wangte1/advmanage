<!-- 加载公用css -->
<?php $this->load->view('common/header');?>


<style>
#table th,td{
	 text-align:center; /*设置水平居中*/
     vertical-align:middle;/*设置垂直居中*/
     cursor:pointer
}

/*#table td:hover{
	 background-color: green;
}*/

.font-red {
	color: red;
}
</style>


<div class="main-container" id="main-container">
    <div class="main-container-inner">
     	<div class="page-content">

					<?php foreach($list1 as $key1 => $val1) {?>
						
						<div>
							<h4><?php echo $mod;?></h4>
						</div>
						
						<table id="table" class="table table-bordered">
					      <thead>
					        
					      </thead>
					     
					      <tbody>
					      	<tr>
					      	  <th rowspan="3">所属tab</th>
					          <th rowspan="3">投放位置</th>
					          <!-- <th rowspan="3">广告形式</th>
					          <th rowspan="3">格式</th> -->
					          <th colspan="<?php echo $val1['days']+2?>"><?php echo $val1['year']?>年<?php echo $val1['month']?>月</th>
					          <!-- <th rowspan="3">单价</th>
					          <th rowspan="3">总价</th>
					          <th rowspan="3">折扣</th>
					          <th rowspan="3">净价</th> -->
					        </tr>
					        
					        <tr>
					        	<th>日期</th>
			                    <?php for($i=0; $i<$val1['days']; $i++){?>
			                    	<th><?php echo $i+1 ?></th>
			                    <?php }?>
			                    <th rowspan="2">天数</th>
					        </tr>
					        
					        <tr>
					        	<th>星期</th>
			                    <?php for($i=0; $i<$val1['days']; $i++){?>
			                    	<th <?php if(json_decode($val1['weeks'],true)[$i] == '六' || json_decode($val1['weeks'],true)[$i] == '日') { ?>class="font-red"<?php }?>><?php echo json_decode($val1['weeks'],true)[$i]?></th>
			                    <?php }?>
					        </tr>
					      	
					      	
					      	<?php foreach($val1['networkinfo'] as $key2 => $val2) {?>
						        <tr>
						          <td><?php echo $val2['type_name']?></td>
						          <td><?php echo $val2['name']?></td>
						          <!-- <td ondblclick="layer.alert();"></td>
						          <td></td> -->
						          <td></td>
						          <?php for($i=0; $i<$val1['days']; $i++) {?>
						          	<?php if(isset($newArr[$key2][$i+1])) {?>
			                    	  		<?php if($list1[0]['apply'] == '取消上画') {?>
			                    	  			<td class="date-td date-order" style="background-color:#FF9900;" title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：<?php echo trim($newArr[$key2][$i+1]['customer']);?>">
			                    	  		<?php }else {?>
			                    	  			<td class="date-td date-order" style="background-color:#336699;" title="预定人：<?php echo $newArr[$key2][$i+1]['username']?>,  客户：<?php echo trim($newArr[$key2][$i+1]['customer']);?>">
							          	   	<?php }?>
							          	   	</td>
			                    	 <?php }else{?>
		                    	  		<td class="date-td date-free" >
			                    	   	</td>
		                    	  	<?php }?>
				                  <?php }?>
						          <!-- <td></td>
						          <td></td>
						          <td></td>
						          <td></td> -->
						          <td></td>
						        </tr>
					        <?php }?>
					       
					      </tbody>
					    </table>
					<?php }?>
					
					<div style="float:left;">
						<span style="float:left;display:block;width:30px;height:20px;line-height:30px;color:#fff;text-align:center;background-color:#336699"></span><span style="float:left;">&nbsp;取消预定&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					
					<div style="float:left;">
						<span style="float:left;display:block;width:30px;height:20px;line-height:30px;color:#fff;text-align:center;background-color:#FF9900"></span><span style="float:left;">&nbsp;取消上画&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					
		</div>
				
    </div>
</div>


<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
<script>
//显示详情
function detail(id,networkId) {
    layer.open({
      type: 2,
      title: '排班申请详情',
      shadeClose: true,
      shade: false,
      maxmin: true, //开启最大化最小化按钮
      area: ['893px', '600px'],
      content: '/networkapply/detail'
    });
}

//通过
function pass(id) {
	layer.prompt({
		  formType: 2,
		  value: '同意',
		  title: '请输入审核内容',
		  //area: ['800px', '350px'] //自定义文本域宽高
		}, function(value, index, elem){
			$.post('/networkapply/pass', {id:id, pass:value}, function(data){
				if(data) {
					layer.alert(data.msg,function(){
						location.reload();
					});
				}
			});
			
		  //alert(value); //得到value
		  //layer.close(index);
		});
	return;
}

//不通过
function noPass(id) {
	layer.prompt({
		  formType: 2,
		  value: '未通过',
		  title: '请输入审核内容',
		  //area: ['800px', '350px'] //自定义文本域宽高
		}, function(value, index, elem){
			$.post('/networkapply/nopass', {id:id, nopass:value}, function(data){
				if(data) {
					layer.alert(data.msg,function(){
						location.reload();
					});
				}
			});
			
		  //alert(value); //得到value
		  //layer.close(index);
		});
	return;
}
</script>

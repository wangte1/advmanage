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

<div class="main-container" id="main-container">
<form class="form-horizontal" role="form" method="post">
	<input type="hidden" name="order_id" value="<?php echo $order_id;?>">
	<div style="text-align: center;">
	<?php foreach ($user_list as $k1 => $v1):?>
		<div class="btn btn-default"><?php echo $v1?>： <span id= "pre_num_<?php echo $k1;?>">0</span> 个</div>
    <?php endforeach;?>  
	</div>
	<div id="table-panel">
	    <table id="sample-table-1" class="table table-striped table-bordered table-hover" >
			<thead>
				<tr>
					<th>序号</th>
					<th>行政区域</th>
					<th>楼盘</th>
					<th>点位数量（个）</th>
					<th>已分派数量</th>
					<th>上画负责人</th>
					<?php if($assign_type == 2) {?>
						<th width="15%">下画负责人</th>
					<?php }?>
					<th>说明</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k => $v) {?>
				<tr>
					<td><?php echo $k+1;?></td>
					<td><?php echo $v['ad_area'];?></td>
					<td><?php echo $v['houses_name'];?><input type="hidden" name="houses_id[]" value="<?php echo $v['houses_id'];?>"></td>

					<td><span class="m-count"><?php echo $v['count'];?></span><input type="hidden" name="points_count[]" value="<?php echo $v['count'];?>"></td>
					<td>
						<span id="count_<?php echo $v['houses_id'];?>" class="sel_count">0</span>
						<input id="area_id_<?php echo $v['houses_id'];?>" name="area_id[]" type="hidden">
						<input id="ban_<?php echo $v['houses_id'];?>" name="ban[]" type="hidden">
						<input id="charge_<?php echo $v['houses_id'];?>" name="ban_charge[]" type="hidden">
						<input id="remark_<?php echo $v['houses_id'];?>" name="ban_remark[]" type="hidden">
						<input id="ban_count_<?php echo $v['houses_id'];?>" name="ban_count[]" type="hidden">
					</td>
					<?php if($assign_type == 2) {?>
						<td>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v1['houses_id'] == $v['houses_id']) {?>
									
									<?php $tmp_charge = explode(',', $v1['charge_user']);?>
									<?php foreach ($tmp_charge as $k2 => $v2) {?>
										<?php if(isset($user_list[$v2])) echo $user_list[$v2]." ";?>
									<?php }?>
									<?php break;?>
									
									
								<?php }?>
							<?php }?>
						</td>
					<?php }?>
					
					<td>
						<select class="select2 charge-sel" name="charge_user[]" id="p_charge_<?php echo $v['houses_id'];?>">
							<option value=""></option>
							<?php foreach($user_list as $k1 => $v1) {?>
								<option value="<?php echo $k1;?>"><?php echo $v1;?></option>
							<?php }?>
						</select>
					</td>
					<td><textarea name="remark[]" rows="1"></textarea></td>
					<td>
						<a class="green tooltip-info m-detail" data-id="<?php echo $v['houses_id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                        	<i class="icon-eye-open bigger-130"></i>
                       	</a>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<div style="height: 50px;">
			<center>
        		<button style="position: fixed;" class="sub-button" type="button">保存并通知</button>
        	</center>
		</div>
	</div>
	
</form>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
$('[data-rel=popover]').popover({html:true});
$(".select2").css('width','150px').select2({allowClear:true});
$(function(){
	$('#table-panel').height($(window).height()-50);

	$('.m-detail').click(function(){
		var order_id = '<?php echo $order_id;?>';
		var houses_id = $(this).attr('data-id');

		var charge_id_str = $('#charge_'+houses_id).val();
		var remark_str = $('#remark_'+houses_id).val();
		var assign_type = '<?php echo $assign_type;?>';
		layer.open({
			  type: 2,
			  title: '按组团楼栋派单',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['90%', '90%'],
			  content: '/housesassign/show_ban?order_id='+order_id+'&houses_id='+houses_id+'&charge_id_str='+charge_id_str+'remark_str='+remark_str+'&assign_type='+assign_type //iframe的url
		}); 
	});

	$('.charge-sel').change(function(){
		<?php if($assign_type == 2) {?>
			$(this).parent('td').prev().prev().find('input[name="area_id[]"]').val('');
			$(this).parent('td').prev().prev().find('input[name="ban[]"]').val('');
			$(this).parent('td').prev().prev().find('input[name="ban_charge[]"]').val('');
			$(this).parent('td').prev().prev().find('input[name="ban_remark[]"]').val('');
			$(this).parent('td').prev().prev().find('input[name="ban_count[]"]').val('');

			var all_count = $(this).parent('td').prev().prev().prev().find('span').text();
			if($(this).val() == '') {
				$(this).parent('td').prev().prev().find('span').text('0');
			}else {
				$(this).parent('td').prev().prev().find('span').text(all_count);
			}
		<?php }else {?>
			$(this).parent('td').prev().find('input[name="area_id[]"]').val('');
			$(this).parent('td').prev().find('input[name="ban[]"]').val('');
			$(this).parent('td').prev().find('input[name="ban_charge[]"]').val('');
			$(this).parent('td').prev().find('input[name="ban_remark[]"]').val('');
			$(this).parent('td').prev().find('input[name="ban_count[]"]').val('');

			var all_count = $(this).parent('td').prev().prev().find('span').text();
			if($(this).val() == '') {
				$(this).parent('td').prev().find('span').text('0');
			}else {
				$(this).parent('td').prev().find('span').text( all_count );
			}
			
		<?php }?>
		//当页面数据发送变化时，发起请求获取统计数据
		auto();
	});
	
	$('.sub-button').click(function(){

		var flag = false;
		$('.sel_count').each(function(){
			if($(this).text() == '' || ($(this).parent('td').prev().find('span').text() != $(this).text())) {
				flag = true;
				return false;
			}
		});

		if(flag == true) {
			layer.alert('您还没有点位没有分配！');
			return;
		}
		
		layer.confirm('您确定保存并给负责人发送短信通知吗？', {
			  	btn: ['确定','取消'] //按钮
			}, function(){
			 	$('form').submit();
			});
	});

});

function auto(){
	var data = $('form').serialize();
	$.post('/housesassign/statistical', data, function(data){
		if(data.code == 1){
			var _data = data.list;
			$.each(_data, function(i,item){
				$('#pre_num_'+item['id']).text(item['count']);
			});
		}
	});
}

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

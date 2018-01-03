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
	<div id="table-panel">
	    <table id="sample-table-1" class="table table-striped table-bordered table-hover" >
			<thead>
				<tr>
					<th>序号</th>
					<th>行政区域</th>
					<th>楼盘</th>
					<th>点位数量（个）</th>
					<th>现负责人</th>
					<th width="15%">更换负责人</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k => $v) {?>
				<tr>
					<td><?php echo $k+1;?></td>
					<td><?php echo $v['ad_area'];?></td>
					<td><?php echo $v['houses_name'];?><input type="hidden" name="houses_id[]" value="<?php echo $v['houses_id'];?>"></td>
					<td><?php echo $v['count'];?><input type="hidden" name="points_count[]" value="<?php echo $v['count'];?>"></td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<input type="hidden" value="<?php echo $v1['charge_user'];?>">
									<?php echo $user_list1[$v1['charge_user']];?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
					<td>
						<select class="select2 charge-sel" name="charge_user[]">
							<option value=""></option>
							<?php foreach($user_list as $k1 => $v1) {?>
								<option value="<?php echo $v1['id'];?>"><?php echo $v1['fullname'];?></option>
							<?php }?>
						</select>
					</td>
					<td><button class="btn btn-xs btn-info" type="button">详情</button></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	
	<center>
		<button class="btn btn-sm btn-info sub-button" type="button">保存并通知</button>
	</center>
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
	
	$('.sub-button').click(function(){
		var mark = false;
		$('.charge-sel').each(function(){
			if($(this).val() != '' && $(this).val() != $(this).parent().prev().find('input').val()) {
				mark = true;
				return false;
			}
		})
		if(mark == false) {
			layer.alert("没有更换的负责人！");
			return;
		}
		
		layer.confirm('您确定更换负责人并给更换负责人发送短信通知吗？', {
		  	btn: ['确定','取消'] //按钮
		}, function(){
		 	$('form').submit();
		});
	});
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

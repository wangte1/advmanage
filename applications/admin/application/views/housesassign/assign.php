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
					<td><span><?php echo $v['count'];?></span><input type="hidden" name="points_count[]" value="<?php echo $v['count'];?>"></td>
					<td>
						<span id="count_<?php echo $v['houses_id'];?>">0</span>
						<input id="charge_<?php echo $v['houses_id'];?>" name="ban_assign[]" type="text" class="ban_assign">
						<input id="remark_<?php echo $v['houses_id'];?>" name="ban_assign[]" type="text" class="ban_assign">
					</td>
					<?php if($assign_type == 2) {?>
						<td>
							<?php foreach($user_list as $k1 => $v1) {?>
								<?php if($v1['id'] == $assign_list[$v['houses_id']]) {?>
									<?php echo $v1['fullname'];?>
								<?php }?>
							<?php }?>
						</td>
					<?php }?>
					
					<td>
						<select class="select2 charge-sel" name="charge_user[]">
							<option value=""></option>
							<?php foreach($user_list as $k1 => $v1) {?>
								<option value="<?php echo $v1['id'];?>" <?php if($assign_type == 2 && $v1['id'] == $assign_list[$v['houses_id']]) {?> selected="selected"<?php }?>><?php echo $v1['fullname'];?></option>
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

	$('.m-detail').click(function(){
		var order_id = '<?php echo $order_id;?>';
		var houses_id = $(this).attr('data-id');
		
		layer.open({
			  type: 2,
			  title: '按楼栋派单',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['90%', '90%'],
			  content: '/housesassign/show_ban?order_id='+order_id+'&houses_id='+houses_id //iframe的url
			}); 
	});
	
	$('.sub-button').click(function(){
		layer.confirm('您确定保存并给负责人发送短信通知吗？', {
			  	btn: ['确定','取消'] //按钮
			}, function(){
			 	$('form').submit();
			});
	});
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

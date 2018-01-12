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
	

	<div id="table-panel">
		
		<table id="sample-table-1" class="table table-striped table-bordered table-hover" >
			<thead>
				<tr>
					<th>序号</th>
					<th>行政区域</th>
					<th>楼盘</th>
					<th>点位数量（个）</th>
					<th>负责人</th>
					<th>派单人</th>
					<th>派单时间</th>
					<th width="20%">说明</th>
					<th >状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k => $v) {?>
				<tr>
					<td><?php echo $k+1;?></td>
					<td><?php echo $v['ad_area'];?></td>
					<td><?php echo $v['houses_name'];?></td>
					<td><?php echo $v['count'];?></td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<?php echo $user_list[$v1['charge_user']];break;?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<?php echo $user_list[$v1['assign_user']];break;?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<?php echo $v1['assign_time'];break;?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<?php echo $v1['remark'];break;?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
					<td>
						<?php if(isset($assign_list)) {?>
							<?php foreach ($assign_list as $k1 => $v1) {?>
								<?php if($v['houses_id'] == $v1['houses_id']) {?>
									<?php if(isset($houses_assign_status[$v1['status']])) echo $houses_assign_status[$v1['status']];?>
								<?php }?>
							<?php }?>
						<?php }?>
					</td>
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
		var assign_type = '<?php echo $assign_type;?>';
		
		layer.open({
			  type: 2,
			  title: '包含点位',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['90%', '90%'],
			  content: '/housesassign/show_points?order_id='+order_id+'&houses_id='+houses_id+'&assign_type='+assign_type //iframe的url
			}); 
	});
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

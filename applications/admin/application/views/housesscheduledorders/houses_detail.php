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
					<th>
						<input 
							class="select_all" 
							<?php if(count($point_list) == $confirm_point_num){echo 'checked';}?>
							type="checkbox" />
					</th>
					<th>点位编号</th>
					<th>组团</th>
					<th>楼栋</th>
					<th>楼层</th>
					<th>单元</th>
					<th>位置</th>
					<th>点位类型</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($point_list as $k => $v) {?>
				<tr>
					<td>
						<input class="point" 
						data-point_id="<?php echo $v['id']?>"  
						<?php if(in_array($v['id'], $confirm_point_ids)){echo 'checked';}?>
						type="checkbox" />
					</td>
					<td><?php echo $v['code'];?></td>
					<td><?php echo $v['area_name']?></td>
					<td><?php echo $v['ban'];?></td>
					<td><?php echo $v['floor'];?></td>
					<td><?php echo $v['unit'];?></td>
					<td><?php if(isset($point_addr[$v['addr']])) echo $point_addr[$v['addr']];?></td>
					<td><?php if(isset($order_type_text[$v['type_id']])) echo $order_type_text[$v['type_id']];?></td>
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

	var order_id = '<?php echo $order_id?>';
	var houses_id = '<?php echo $houses_id?>';
	$('.select_all').on('click', function(data){
		var status = 0;
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		$.post('/housesscheduledorders/select_all', {'order_id':order_id, 'houses_id':houses_id, 'status':status}, function(data){
			if(data){
				window.location.reload();
			}
		});
	});

	$('.point').on('click', function(data){
		var status = 0;
		var point_id = $(this).attr('data-point_id');
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		$.post('/housesscheduledorders/select_one', {'order_id':order_id, 'point_id':point_id, 'status':status}, function(data){
			if(data){
				window.location.reload();
			}
		});
	});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

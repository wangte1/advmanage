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
					<th>楼盘</th>
					<th>组团</th>
					<th>楼栋</th>
					<th>点位数量</th>
					<th>负责人</th>
					<th>说明</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k => $v) {?>
				<tr>
					<td><?php echo $k+1;?></td>
					<td>
						<?php foreach ($houses_list as $k1 => $v1) {?>
							<?php if($v1['id'] == $v['houses_id']) {echo $v1['name'];}?>
						<?php }?>
					</td>
					<td>
						<?php foreach ($area_list as $k1 => $v1) {?>
							<?php if($v1['id'] == $v['area_id']) {echo $v1['name'];}?>
						<?php }?>
					</td>
					<td><?php echo $v['ban'];?></td>
					<td><?php echo $v['count'];?></td>
					<td>
						<select class="select2 charge-sel" name="charge_user[]">
							<option value=""></option>
							<?php foreach($user_list as $k1 => $v1) {?>
								<option value="<?php echo $v1['id'];?>"><?php echo $v1['fullname'];?></option>
							<?php }?>
						</select>
					</td>
					<td>
						<textarea class="remark" name="remark[]" rows="1"></textarea>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		
		<!--分页start-->
        <?php $this->load->view('common/page');?>
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

// 	$('.m-detail').click(function(){
		var order_id = '<?php echo $order_id;?>';
// 		var houses_id = $(this).attr('data-id');
		
// 		layer.open({
// 			  type: 2,
// 			  title: '显示点位',
// 			  shadeClose: true,
// 			  shade: 0.6,
// 			  area: ['90%', '90%'],
// 			  content: 'housesassign/show_points?order_id='+order_id+'houses_id='+houses_id //iframe的url
// 			}); 
// 	});

	$('.charge-sel, .remark').change(function(){

		var charge_str = '';
		var remark_str = '';

		var i = 0;
		$('.select2-chosen').each(function(){
			charge_str += $(this).text() + ',';
			if($(this).text() != '') {
				i += parseInt($(this).parent().parent().parent().prev().text());
			}
		});

		$('.remark').each(function(){
			remark_str += $(this).val() + ',';
		});

		
		window.parent.$('#count_<?php echo $houses_id;?>').text(i);
		window.parent.$('#charge_<?php echo $houses_id;?>').val(charge_str);
		window.parent.$('#remark_<?php echo $houses_id;?>').val(remark_str);

	});
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

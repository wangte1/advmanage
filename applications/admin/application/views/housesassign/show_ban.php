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
						<input type="hidden" value="<?php echo $v['area_id'];?>">
						<?php foreach ($area_list as $k1 => $v1) {?>
							<?php if($v1['id'] == $v['area_id']) {echo $v1['name'];}?>
						<?php }?>
					</td>
					<td><?php echo $v['ban']?></td>
					<td><?php echo $v['count'];?></td>
					<td>
						<select class="charge-sel" name="charge_user[]" data-area_id="<?php echo $v['area_id']?>">
							<option value=""></option>
							<?php foreach($user_list as $k1 => $v1) {?>
								<option value="<?php echo $v1['id'];?>"  <?php if(isset($charge_id_arr[$k]) && $charge_id_arr[$k] == $v1['id']) {?>selected="selected"<?php }?>><?php echo $v1['fullname'];?></option>
							<?php }?>
						</select>
					</td>
					<td>
						<textarea class="remark" name="remark[]" rows="1"><?php if(isset($remark_arr[$k])) echo $remark_arr[$k];?></textarea>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		
		<!--分页start-->
        <!--<?php $this->load->view('common/page');?>-->
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
	var order_id = '<?php echo $order_id;?>';

	$('.remark').change(function(){
		doselect();
	});

	$('.charge-sel').change(function(){
		//获取组团id
		var area_id = $(this).attr('data-area_id');
		//获取用户值
		var user_id = $(this).val();
		var _this = $(this);
		$('.charge-sel').each(function(){
			if($(this).attr('data-area_id') == area_id && $(this).val() == ""){
				$(this).find('option[value="'+user_id+'"]').attr('selected', true);
			}
		});
		doselect();
	});

	function doselect(){
		var area_id_str = '';
		var ban_str = '';
		var charge_str = '';
		var remark_str = '';
		var ban_count = '';
		var i = 0;
		
		$('.charge-sel').each(function(){
			if(typeof($(this).attr('id'))=="undefined") {
				charge_str += $(this).val() + ',';

				if($(this).val() != '') {
					i += parseInt($(this).parent('td').prev().text());
					ban_count += $(this).parent('td').prev().text() + ',';
					ban_str += $(this).parent('td').prev().prev().text() + ',';
					area_id_str += $(this).parent('td').prev().prev().prev().find('input').val() + ',';
				}
			}
		});

		$('.remark').each(function(){
			remark_str += $(this).val() + ',';
		});

		window.parent.$('#s2id_p_charge_<?php echo $houses_id;?>').find('.select2-chosen').text('');
		window.parent.$('#p_charge_<?php echo $houses_id;?> option:first').prop("selected", 'selected');
		window.parent.$('#count_<?php echo $houses_id;?>').text(i);
		window.parent.$('#area_id_<?php echo $houses_id;?>').val(area_id_str);
		window.parent.$('#ban_<?php echo $houses_id;?>').val(ban_str);
		window.parent.$('#charge_<?php echo $houses_id;?>').val(charge_str);
		window.parent.$('#remark_<?php echo $houses_id;?>').val(remark_str);
		window.parent.$('#ban_count_<?php echo $houses_id;?>').val(ban_count);
		window.parent.auto();
	}
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

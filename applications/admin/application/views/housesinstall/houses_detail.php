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
    .text{
    	text-align: center;
	    border: none;
    	BACKGROUND-COLOR: transparent;
    }
</style>

<div class="main-container" id="main-container">
<form class="form-horizontal" role="form" method="post">
	<div id="table-panel">
	    <table id="sample-table-1" class="table table-striped table-bordered table-hover" >
			<thead>
				<tr>
					<th>楼盘</th>
					<th>组团</th>
					<th>联系人</th>
					<th>电话</th>
					<th>职位</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k => $v) {?>
				<tr>
					<td><?php echo $v['house_name'];?></td>
					<td><?php echo $v['area_name'];?></td>
					<td><input class="text" id="name_<?php echo $v['id']?>" value="<?php echo $v['linkman']?>"></td>
					<td><input class="text" id="tel_<?php echo $v['id']?>" value="<?php echo $v['linkman_tel']?>"></td>
					<td><input class="text" id="duty_<?php echo $v['id']?>" value="<?php echo $v['linkman_duty']?>"></td>
					<td><input type="button" class="save" data-id="<?php echo $v['id']?>" value="保存"></td>
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

	$('.m-detail').click(function(){
		var order_id = '<?php echo $order_id;?>';
		var houses_id = $(this).attr('data-id');
		
		layer.open({
			  type: 2,
			  title: '显示点位',
			  shadeClose: true,
			  shade: 0.6,
			  area: ['90%', '90%'],
			  content: 'housesassign/show_points?order_id='+order_id+'houses_id='+houses_id //iframe的url
			}); 
	});
});
</script>
<script>
$('.save').click(function(){
	var id = $(this).attr('data-id');
	var name = $('#name_' + id).val();
	var tel = $('#tel_' + id).val();
	var duty = $('#duty_' + id).val();
	$.post('/housesinstall/ajax_update',{id:id,name:name,tel:tel,duty:duty},function(data){
		layer.msg(data.msg);
	});
});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

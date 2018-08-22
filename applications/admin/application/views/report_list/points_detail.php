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
					<th>编号</th>
					<th>点位编号</th>
					<th>楼盘</th>
					<th>组团</th>
					<th>详细地址</th>
					<th>报损图片</th>
					<th>修复图片</th>
					<th>报损人</th>
					<th>报损类型</th>
					<th>说明</th>
					<th>是否可以上画</th>
					<th>安装公司</th>
					<th>报损日期</th>
					<th>修复人</th>
					<th>修复日期</th>
					<th>修复备注</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $key => $val) {?>
				<tr>
					<td><?php echo $val['id'];?></td>
                    <td><?php echo $val['point']['code'];?></a></td>
                    <td><?php echo $val['point']['houses_name'];?></td>
                    <td><?php echo $val['point']['houses_area_name'];?></td>
                    <td>
                    	<?php echo $val['point']['ban'].'&'.$val['point']['unit'].'&'.$val['point']['floor'];?>楼
                    	<?php if(isset(C('housespoint.point_addr')[$val['point']['addr']])) echo C('housespoint.point_addr')[$val['point']['addr']];?>
                    </td>
                    <td>
                    	<img style="width:25px;height:25px;cursor:pointer;" src="<?php echo $val['report_img']?>" layer-src="<?php echo $val['report_img']?>" src="<?php echo $val['report_img']?>" alt="点位编号：<?php echo $val['point']['code'];?>"/>
                    </td>
                    <td>
                    	<img style="width:25px;height:25px;cursor:pointer;" src="<?php echo $val['repair_img']?>" />
                    </td>
                    <td>
                    	<?php echo $val['fullname'];?>
                    </td>
                    <td>
                    	<?php 
                    	
                    	    $type = explode(',', $val['report']);
                    	    foreach ($type as $k => $v){
                    	        if($v){
                    	            if($k == 0){
                    	                echo C('housespoint.report')[$v];
                    	            }else{
                    	                echo ','.C('housespoint.report')[$v];
                    	            }
                    	        }
                    	        
                    	    }
                    	?>
                    </td>
                    <td>
                    	<?php echo $val['report_msg'];?>
                    </td>
                    <td>
                        <?php if($val['usable']){echo'是';}else{echo'否';}?>
                    </td>
                    <td>
                        <?php if($val['install'] != '0') echo $val['install'];else echo '';?>
                    </td>
                    <td>
                        <?php echo date('Y-m-d', $val['create_time']);?>
                    </td>
                    <td>
                    	<?php if(isset($val['repair_name'])) echo $val['repair_name']; else echo '';?>
                    </td>
                    <td>
                    	<?php echo date('Y-m-d', $val['repair_time']);?>
                	</td>
                	<td>
                		<?php echo $val['remarks']?>
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
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

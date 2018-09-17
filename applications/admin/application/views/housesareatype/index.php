<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<!-- 头部 -->
<style>
.padding0 {
	padding: 0;
}
.padding-right0 {
	padding-right: 0;
}
</style>
<div class="main-container" id="main-container">
        <div class="main-container-inner">

        </div>
            <div class="page-content">
                <div class="row">
                     <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <caption style="border: 1px solid #ddd;"><span style="font-size:18px">楼盘-组团-置业类型</span></caption>
                    <thead>
                    <tr>
                    	<th style="text-align: center;width:10%;">序号</th>
                        <th style="text-align: center;width:30%;">楼盘</th>
                        <th style="text-align: center;width:20%;">组团</th>
                        <th style="text-align: center;width:20%;">点位数</th>
                        <th style="text-align: center;width:20%;">置业类型</th>
                    </tr>
                    </thead>
                    <tbody>
                    	<?php $j = 0;?>
						<?php if($list):?>
						<?php foreach ($list as $k => $v):?>
						<?php $i = 0;?>
						<?php foreach ($v['area'] as $key => $val):?>
                        <tr>
                        	<td><?php echo $j+1;$j++;?></td>
                        	<?php if($i == 0):?>
                            <td style="vertical-align: middle;" rowspan="<?php echo count($v['area']);?>"><?php echo $v['houses_name'];?></td>
                            <?php endif;?>
                            <td><?php echo $val['area_name']?></td>
                            <td><?php echo $val['num']?></td>
                            <td>
                            	<select class="diy" data-houses_id="<?php echo $v['houses_id']?>" data-area_id="<?php echo $val['id'];?>" name="roleId_<?php echo $v['houses_id']?>_<?php echo $val['id'];?>">
                            		<option value="0">请选择</option>
                            		<?php foreach (C('zhiye') as $k1 => $v1):?>
                            		<option <?php if($k1 == $val['houses_type_id']){echo 'selected';}?> value="<?php echo $k1?>"><?php echo $v1?></option>
                            		<?php endforeach;?>
                            	</select>
                            </td>
                        </tr>
                        <?php $i++;?>
                        <?php endforeach;?>
                        <?php endforeach;?>
                        <?php endif;?>                  
                     </tbody>
                </table>
            </div>
                </div><!-- /.row -->

            </div><!-- /.page-content -->
        </div><!-- /.main-content -->



</div><!-- /.main-container -->



<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
	$('body').on('change', '.diy', function(){
		var houses_type = $(this).val();
		var houses_id = $(this).attr('data-houses_id');
		var area_id = $(this).attr('data-area_id');
		$.post('/housesareatype/set_houses_area_type', {'houses_type':houses_type, 'houses_id':houses_id, 'area_id':area_id}, function(data){
			layer.msg(data.msg);
		});
	});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
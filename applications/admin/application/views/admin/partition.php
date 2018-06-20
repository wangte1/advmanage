<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>

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
            <?php $this->load->view("common/left");?>

        </div>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="/housespoints">管理员管理</a>
                    </li>
                    <li class="active">用户自定义区域设定</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                
                <div class="row">
                     <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <caption style="border: 1px solid #ddd;"><span style="font-size:18px">用户-区域</span></caption>
                    <thead>
                    <tr>
                        <th style="text-align: center;width:20%;">序号</th>
                        <th style="text-align: center;width:20%;">姓名</th>
                        <th style="text-align: center;width:20%;">自定义区域</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php if($userList):?>
						<?php foreach ($userList as $k => $v):?>
                        <tr>
                            <td style="vertical-align: middle;" ><?php echo $k+1;?></td>
                            <td><?php echo $v['fullname']?></td>
                            <td>
                            	<select class="diy" data-user_id="<?php echo $v['id'];?>">
                            		<option value="0">请选择</option>
                            		<?php foreach (C('diy_area') as $k1 => $v1):?>
                            		<option <?php if($k1 == $v['diy_area_id']){echo 'selected';}?> value="<?php echo $k1?>"><?php echo $v1?></option>
                            		<?php endforeach;?>
                            	</select>
                            	
                            </td>
                        </tr>
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
		var diy_area_id = $(this).val();
		var user_id = $(this).attr('data-user_id');
		$.post('/admin/set_diy_area', {'diy_area_id':diy_area_id, 'user_id':user_id}, function(data){
			layer.msg(data.msg);
		});
	});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
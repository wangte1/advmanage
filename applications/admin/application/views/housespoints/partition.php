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
                        <a href="/housespoints">楼盘点位管理</a>
                    </li>
                    <li class="active">自定义区域设定</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                
                <div class="row">
                     <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <caption style="border: 1px solid #ddd;"><span style="font-size:18px">楼盘-组团-区域</span></caption>
                    <thead>
                    <tr>
                        <th style="text-align: center;width:20%;">楼盘</th>
                        <th style="text-align: center;width:20%;">组团</th>
                        <th style="text-align: center;width:20%;">点位数</th>
                        <th style="text-align: center;width:20%;">自定义区域</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php if($list):?>
						<?php foreach ($list as $k => $v):?>
						<?php $i = 0;?>
						<?php foreach ($v['area'] as $key => $val):?>
                        <tr>
                        	<?php if($i == 0):?>
                            <td style="vertical-align: middle;" rowspan="<?php echo count($v['area']);?>"><?php echo $v['houses_name'];?></td>
                            <?php endif;?>
                            <td><?php echo $val['area_name']?></td>
                            <td><?php echo $val['num']?></td>
                            <td>
                            	<select name="roleId_<?php echo $v['houses_id']?>_<?php echo $val['area_id']?>">
                            		<option value="0">请选择</option>
                            		<?php foreach (C('diy_area') as $k1 => $v1):?>
                            		<option value="<?php echo $k1?>"><?php echo $v1?></option>
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
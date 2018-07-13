<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>

<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <!-- 左边导航菜单 -->
        <?php $this->load->view('common/left');?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">首页</a>
                    </li>
                    <li>
                        <a href="#">社区资源管理</a>
                    </li>
                    <li class="active">报损列表</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search" method="get" action="#">
                        <span class="input-icon">
                            <input type="text" placeholder="请输入客户名称..."  value="" name="name" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>

            <div class="page-content">
            	<div class="page-header">
                	<a href="javascript:;" class="btn btn-sm btn-primary btn-export"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出</a>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>筛选条件</h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="icon-chevron-up"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="get" action="#">
                                    
                                    	<div class="form-group">
                                    		<div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘 </label>
                                                <div class="col-sm-9">
                                                	<select id="houses" class="select2" data-placeholder="Click to Choose..." name="houses_id">
                                                		<option value="">全部</option>
				                                		<?php foreach ($hlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if(isset($houses_id) && $v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                    	
                                    	
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 报损类型： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="report" >
                                                        <option value="0">全部</option>
                                                        <?php foreach ($report as $k => $v) {?>
			                                    		<option value="<?php echo $k;?>" <?php if($k == $report_id) {?>selected="selected"<?php }?>><?php echo $v;?></option>
				                                    	<?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                    
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 修复状态： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="repair_time" >
                                                        <option value="0">未修复</option>
                                                        <option value="1"<?php if($repair_time == '1') { echo "selected"; }?>>已修复</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 是否可上画： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="usable">
                                                        <option value="-1">全部</option>
                                                        <option value="0"<?php if($usable == '0') { echo "selected"; }?>>不可上画</option>
                                                        <option value="1"<?php if($usable == '1') { echo "selected"; }?>>可以上画</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 报损时间： </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker" type="text" name="time" value="<?php if(isset($time)){ echo $time;}?>" >
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 报损人： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="create_id">
                                                        <option value="0">全部</option>
                                                        <?php if($adminList):?>
                                                        <?php foreach ($adminList as $k => $v):?>
                                                        <option <?php if(isset($create_id) && $create_id == $v['id']){echo "selected";}?>  value="<?php echo $v['id']?>"><?php echo $v['fullname']?></option>
                                                        <?php endforeach;?>
                                                        <?php endif;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        	<div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装公司： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="install">
                                                        <option value="0">全部</option>
                                                        <?php foreach (C('install.install') as $k => $v):?>
                                                        <option <?php if(isset($install) && $install== $k){echo "selected";}?>  value="<?php echo $k;?>"><?php echo $v?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位编号： </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="rcode" value="<?php if(isset($rcode)){echo $rcode;}?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
                                                <button class="btn" type="reset">
                                                    <i class="icon-undo bigger-110"></i>
                                                    重置
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>编号</th>
                                                <th>点位编号</th>
                                                <th>楼盘</th>
                                                <th>组团</th>
                                                <th>详细地址</th>
                                                <th>报损图片</th>
                                                <?php if($repair_time == 1):?>
                                                <th>修复图片</th>
                                                <?php endif;?>
                                                <th>报损人</th>
                                                <th>报损类型</th>
                                                <th>说明</th>
                                                <th>是否可以上画</th>
                                                <th>安装公司</th>
                                                <?php if($repair_time == 0):?>
                                                <th>报损日期</th>
                                                <th>操作</th>
                                                <?php else :?>
                                                <th>修复日期</th>
                                                <th>修复备注</th>
                                                <?php endif;?>
                                            </tr>
                                        </thead>
                                        <tbody id="layer-photos-demo" class="layer-photos-demo">
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo $val['id'];?></td>
                                                    <td><?php echo $val['point']['code'];?></td>
                                                    <td><?php echo $val['point']['houses_name'];?></td>
                                                    <td><?php echo $val['point']['houses_area_name'];?></td>
                                                    <td>
                                                    	<?php echo $val['point']['ban'].'&'.$val['point']['unit'].'&'.$val['point']['floor'];?>楼
                                                    	<?php if(isset(C('housespoint.point_addr')[$val['point']['addr']])) echo C('housespoint.point_addr')[$val['point']['addr']];?>
                                                    </td>
                                                    <td>
                                                    	<img style="width:100px;" src="<?php echo $val['report_img']?>" layer-src="<?php echo $val['report_img']?>" src="<?php echo $val['report_img']?>" alt="点位编号：<?php echo $val['point']['code'];?>"/>
                                                    </td>
                                                    <?php if($repair_time == 1):?>
                                                    <td>
                                                    	<img style="width:100px;" src="<?php echo $val['repair_img']?>" />
                                                    </td>
                                                    <?php endif;?>
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
                                                    <?php if($repair_time == 0):?>
                                                    <td><?php echo date('Y-m-d', $val['create_time']);?></td>
                                                    <td>
                                                        <button class="btn btn-primary report" data-id="<?php echo $val['id']?>" data-code="<?php echo $val['point']['code'];?>">修复</button>
                                                    </td>
                                                    <?php else :?>
                                                    <td><?php echo date('Y-m-d', $val['repair_time']);?></td>
                                                    <td><?php echo $val['remarks']?></td>
                                                    <?php endif;?>
                                                </tr>
                                            <?php } }?>
                                        </tbody>
                                    </table>
                                    <?php $this->load->view('common/page');?>
                                    <!-- 分页 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
$(".select2").css('width','230px').select2({allowClear:true});
var baseUrl = "<?php echo $domain['admin']['url'];?>";
// $('img').on('click', function(){
// 	url = $(this).attr('src');
// 	window.open(baseUrl+url,'_blank');
// });

$(".btn-export").on('click', function(){
	$(".form-horizontal").attr('action', '/report_list/out_excel');
    $(".form-horizontal").submit();
    $(".form-horizontal").attr('action', '');
});

//报修点位
$('.report').on('click', function(){
	var id = $(this).attr('data-id');
	var code = $(this).attr('data-code');
	layer.open({
	  type: 2,
	  title: '编号: '+code+' 点位修复',
	  shadeClose: true,
	  shade: 0.8,
	  area: ['50%', '50%'],
	  content: '/report_list/report?id='+id
	});
});
</script>
<script>
	//调用示例
    layer.photos({
      photos: '#layer-photos-demo'
      ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
    }); 
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

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
                                                		<option value="">请选择楼盘</option>
				                                		<?php foreach ($hlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                    	
                                    	
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 报损类型： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="report" id="form-field-select-1" >
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
                                                    <select class="select2" name="repair_time" id="form-field-select-1" >
                                                        <option value="0">未修复</option>
                                                        <option value="1"<?php if($repair_time == '1') { echo "selected"; }?>>已修复</option>
                                                    </select>
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
                                                <th>报损人</th>
                                                <th>报损类型</th>
                                                <th>说明</th>
                                                <th>是否可以上画</th>
                                                <th>日期</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                    	<img style="width:100px;" src="<?php echo $val['report_img']?>" />
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
                                                    <td><?php echo date('Y-m-d', $val['create_time']);?></td>
                                                    <td>
                                                        <button class="btn btn-primary report" data-id="<?php echo $val['id']?>" data-code="<?php echo $val['point']['code'];?>">修复</button>
                                                    </td>
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
$('img').on('click', function(){
	url = $(this).attr('src');
	window.open(baseUrl+url,'_blank');
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
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

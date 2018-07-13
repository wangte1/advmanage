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
                    <li class="active">巡视列表</li>
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
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 巡视日期： </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker" type="text" name="time" value="<?php if(isset($create_time)){ echo $create_time;}?>" >
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 巡视人： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="principal_id">
                                                        <option value="0">全部</option>
                                                        <?php if($adminList):?>
                                                        <?php foreach ($adminList as $k => $v):?>
                                                        <option <?php if(isset($principal_id) && $principal_id== $v['id']){echo "selected";}?>  value="<?php echo $v['id']?>"><?php echo $v['fullname']?></option>
                                                        <?php endforeach;?>
                                                        <?php endif;?>
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
                                                <th>序号</th>
                                                <th>工程人员</th>
                                                <th>巡视日期</th>
                                                <th>巡视个数</th>
                                                <th>详情</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($list):?>
                                            <?php foreach ($list as $k => $v):?>
                                            <tr>
                                                <td><?php echo $k+1;?></td>
                                                <td><?php echo $v['fullname']?></td>
                                                <td><?php echo $v['create_time']?></td>
                                                <td><?php echo $v['num']?></td>
                                                <td>
                                                    <button class="btn btn-primary detail" data-id="<?php echo $v['id']?>">详情</button>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <?php endif;?>
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

//报修点位
$('.detail').on('click', function(){
	var user_id = $(this).attr('data-user_id');
	layer.open({
	  type: 2,
	  title: '巡视详情',
	  shadeClose: true,
	  shade: 0.8,
	  area: ['50%', '50%'],
	  content: '/hosuestour/detail?user_id='+user_id
	});
});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

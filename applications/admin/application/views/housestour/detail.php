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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 点位编号： </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <input class="form-control col-sm-12" type="text" name="point_code" value="<?php if(isset($point_code)){ echo $point_code;}?>" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                            	<input type="hidden" name="create_time" value="<?php echo $create_time?>">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘： </label>
                                                <div class="col-sm-9">
                                                    <select class="select2" name="houses_id">
                                                        <option value="0">全部</option>
                                                        <?php if($hlist):?>
                                                        <?php foreach ($hlist as $k => $v):?>
                                                        <option <?php if(isset($houses_id) && $houses_id == $v['id']){echo 'selected';}?> value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
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
                                                <th>点位编号</th>
                                                <th>地址</th>
                                                <th>图片</th>
                                            </tr>
                                        </thead>
                                        <tbody id="layer-photos-demo">
                                            <?php if($list):?>
                                            <?php foreach ($list as $k => $v):?>
                                            <tr>
                                                <td><?php echo $k+1;?></td>
                                                <td><?php echo $v['fullname']?></td>
                                                <td><?php echo $v['create_time']?></td>
                                                <td><?php echo $v['code']?></td>
                                                <td>
                                                    <?php echo $v['houses_name'].$v['area_name'].$v['unit'].$v['floor'].$v['addr'];?>
                                                </td>
                                                <td>
                                                    <img style="width:100px;" alt="点位编号：<?php echo $v['code']?>" src="<?php echo $v['img']?>" />
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
//调用示例
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
}); 
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

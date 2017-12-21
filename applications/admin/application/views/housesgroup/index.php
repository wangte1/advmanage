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
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">社区资源管理</a>
                    </li>
                    
                    <li>
                        <span>组团管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housesgroup/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加组团</a>
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
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 组团名称</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="houses_name" value=""  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘</label>
                                                <div class="col-sm-9">
                                                    <select id="state" name="houses_id"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="">全部</option>
                                                        <?php foreach($houses_list as $key=>$val){ ?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($houses_id == $val['id']) { echo "selected"; }?>><?php echo $val['name'];?></option>
                                                        <?php } ?>
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

                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-xs-12">
                                 <div class="table-responsive">
                                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>所属楼盘</th>
                                                <th>组团名称</th>
                                                <th>创建日期</th>
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
                                                    <td>
                                                    	<?php foreach ($houses_list as $k => $v):?>
                                                    	<?php if($v['id'] == $val['houses_id']):?>
                                                    	<?php echo $v['name'];?>
                                                    	<?php endif;?>
                                                    	<?php endforeach;?>
                                                    </td>
                                                    <td><?php echo $v['group_name'];?></td>
                                                    <td><?php echo $v['create_time'];?></td>
			
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housesgroup/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/housesgroup/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } }?>
										</tbody>
                                    </table>
									<!--分页start-->
                                    <?php $this->load->view('common/page');?>
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

<script>
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php if(isset($province)) { echo $province;}else{?>贵州省<?php }?>",
		city: "<?php if(isset($city)) { echo $city;}else{?>贵阳市<?php }?>",
		district : "<?php if(isset($area)) { echo $area;}?>",
	});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
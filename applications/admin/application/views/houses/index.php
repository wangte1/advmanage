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
                        <span>楼盘管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/houses/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加楼盘</a>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘名称</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属地区</label>
                                                <div class="col-sm-9">
				                                    <div id="distpicker1">
													  <select name="province"></select>
													  <select name="city"></select>
													  <select name="area"></select>
													</div>
				                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 类型</label>
                                                <div class="col-sm-9">
                                                    <select id="state" name="type"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="all" <?php if($type == 'all' || empty($type)){ echo 'selected'; }?>>全部</option>
                                                        <?php foreach(C('public.houses_type') as $key=>$val){ ?>
                                                            <option value="<?php echo $key;?>" <?php if($type != 'all' && ($key == $type)) { echo "selected"; }?>><?php echo $val;?></option>
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
                                                <th>序号</th>
                                                <th>楼盘名称</th>
                                                <th>地区</th>
                                                <th>禁投放行业</th>
                                                <th>类型</th>
                                                <th>发送物业审核</th>
                                                <th>备注</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><a href=""><?php echo $key+1;?></a></td>
                                                    <td><a href=""><?php echo $val['name'];?></a></td>
                                                    <td><?php echo $val['province'];?>-<?php echo $val['city'];?>-<?php echo $val['area'];?></td>
													<td><?php if(isset($put_trade[$val['put_trade']])) echo $put_trade[$val['put_trade']];?></td>
													<td><?php echo $houses_type[$val['type']];?></td>
													<td><?php if($val['is_check_out'] == 1) echo '是'; else echo '否';?></td>
													<td class="hidden-480">
                                                        <?php echo $val['remarks'];?>
                                                    </td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/houses/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/houses/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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
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
                        <span>楼盘安装管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
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
                                    <form id="search-form" class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘名称</label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="name">
                                                		<option value="">全部</option>
				                                		<?php foreach ($hlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $name) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
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
                                                <th>物业联系人</th>
                                                <th>联系人职务</th>
                                                <th>联系人电话</th>
                                                <th>签约数量</th>
                                                <th>签约室内数量</th>
                                                <th>签约室外数量</th>
                                                <th>完工日期</th>
                                                <th>安装数量</th>
                                                <th>安装室内数量</th>
                                                <th>安装室外数量</th>
                                                <th>安装结算数量</th>
                                                <th>安装备注</th>
                                                <th>是否验收</th>
                                                <th>验收人</th>
                                                <th>验收日期</th>
                                                <th>验收图片</th>
                                                <th>是否结算</th>
                                                <th>结算日期</th>
                                                <th>是否提成</th>
                                                <th>提成数量</th>
                                                <th>提成日期</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="layer-photos-demo" class="layer-photos-demo">
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><a href=""><?php echo $key+1;?></a></td>
                                                    <td><a href=""><?php echo $val['name'];?></a></td>
                                                    <td><?php echo $val['linkman'];?></td>
                                                    <td><?php echo $val['linkman_duty'];?></td>
                                                    <td><?php echo $val['linkman_tel'];?></td>
                                                    <td><?php echo $val['sign_num'];?></td>
                                                    <td><?php echo $val['sign_in_num'];?></td>
                                                    <td><?php echo $val['sign_out_num'];?></td>
                                                    <td><?php echo $val['finish_date'];?></td>
                                                    <td><?php echo $val['install_num'];?></td>
                                                    <td><?php echo $val['install_in_num'];?></td>
                                                    <td><?php echo $val['install_out_num'];?></td>
                                                    <td><?php echo $val['install_account_num'];?></td>
                                                    <td><?php echo $val['install_remake'];?></td>
                                                    <td><?php if($val['is_check']){echo '是';}else{echo '否';};?></td>
                                                    <td><?php echo $val['fullname'];?></td>
                                                    <td><?php echo $val['check_date'];?></td>
                                                    <td><?php if(!empty($val['check_img'])):?>
                                                    	<img src="<?php echo $val['check_img']?>" style="width:25px;height:25px;cursor:pointer;">
                                                    	<?php endif;?>
                                                	</td>
                                                    <td><?php if($val['is_account']){echo '是';}else{echo '否';};?></td>
                                                    <td><?php echo $val['account_date'];?></td>
                                                    <td><?php if($val['is_push']){echo '是';}else{echo '否';};?></td>
                                                    <td><?php echo $val['push_num'];?></td>
                                                    <td><?php echo $val['push_date'];?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housesinstall/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script>
$(".select2").css('width','230px').select2({allowClear:true});
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php if(isset($province)) { echo $province;}else{?>贵州省<?php }?>",
		city: "<?php if(isset($city)) { echo $city;}else{?>贵阳市<?php }?>",
		district : "<?php if(isset($area)) { echo $area;}?>",
	});

	$(function(){
		$(".btn-export").click(function(){
        	$("#search-form").attr('action', '/housesinstall/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
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
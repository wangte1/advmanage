<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>
<?php 
//权限处理
//组团
$area_status = false;
foreach ($power as $k => $v){
    if($v == '109'){
        $area_status = true;
    }
}
//置业类型
$housesareatype_status = false;
foreach ($power as $k => $v){
    if($v == '229'){
        $housesareatype_status = true;
    }
}
//安装管理
$housesinstall_status = false;
foreach ($power as $k => $v){
    if($v == '222'){
        $housesinstall_status = true;
    }
}
?>

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
                <div class="row">
                    <div class="col-xs-12">
                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                <li <?php if($tab == 'basic'){echo 'class="active"';}?>>
                                    <a data-toggle="tab" href="#basic">基础信息</a>
                                </li>
                                <?php if($area_status):?>
                                <li <?php if($tab == 'housesarea'){echo 'class="active"';}?>>
                                    <a data-toggle="tab" href="#housesarea">组团管理</a>
                                </li>
                                <?php endif;?>
                                <?php if($housesareatype_status):?>
                                <li <?php if($tab == 'housesareatype'){echo 'class="active"';}?>>
                                    <a data-toggle="tab" href="#housesareatype">置业类型</a>
                                </li>
                                <?php endif;?>
                                <?php if($housesinstall_status):?>
                                <li <?php if($tab == 'housesinstall'){echo 'class="active"';}?>>
                                    <a data-toggle="tab" href="#housesinstall">安装管理</a>
                                </li>
                                <?php endif;?>
                            </ul>
                            <div class="tab-content">
                            	<div id="basic" class="tab-pane <?php if($tab == 'basic'){echo 'in active';}?>">
                            		<div class="page-header">
                                        <a href="/houses/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加楼盘</a>
                                    	<a href="javascript:;" class="btn btn-sm btn-primary btn-export"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出</a>
                                    	<a href="javascript:;" class="btn btn-sm btn-primary btn-export-one"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出验收数据</a>
                                    </div>
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
                                                        <!-- <div class="col-sm-4">
                                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 类型</label>
                                                            <div class="col-sm-9">
                                                                <select id="state" name="type"  class="select2" data-placeholder="Click to Choose...">
                                                                    <option value="all" <?php if($type == 'all' || empty($type)){ echo 'selected'; }?>>全部</option>
                                                                    <?php foreach(C('public.houses_type') as $key=>$val){ ?>
                                                                        <option value="<?php echo $key;?>" <?php if($type != 'all' && ($key == $type)) { echo "selected"; }?>><?php echo $val;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div> -->
                                                        
                                                        <div class="col-sm-4">
                                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 等级</label>
                                                            <div class="col-sm-9">
                                                                <select id="state" name="grade"  class="select2" data-placeholder="Click to Choose...">
                                                                    <option value="all" <?php if($grade == 'all' || empty($grade)){ echo 'selected'; }?>>全部</option>
                                                                    <?php foreach(C('public.houses_grade') as $key=>$val){ ?>
                                                                        <option value="<?php echo $key;?>" <?php if($grade != 'all' && ($key == $grade)) { echo "selected"; }?>><?php echo $val;?></option>
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
                                                            <th>区域</th>
                                                            <th>地址</th>
                                                            <th>规划入住户数（户）</th>
                                                            <th>层数（层）</th>
                                                            <th>入住率</th>
                                                            <th>单元数</th>
                                                            <th>禁投放行业</th>
                                                            <th>类型</th>
                                                            <th>等级</th>
                                                            <th>交付年份</th>
                                                            <th>发送物业审核</th>
                                                            <th>物业公司</th>
                                                            <th>门禁</th>
                                                            <th>地面电梯前室</th>
                                                            <th>地下电梯前室</th>
                                                            <th>合计</th>
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
                                                                <td><?php echo $val['area'];?></td>
                                                                <td><?php echo $val['position'];?></td>
                                                                <td><?php echo $val['households'];?></td>
                                                                <td><?php echo $val['floor_num'];?></td>
                                                                <td><?php echo $val['occ_rate'] * 100 . '%';?></td>
                                                                <td><?php echo $val['unit_rate'];?></td>
            													<td>
            														<?php if(isset($val['put_trade']) && !empty($val['put_trade'])) {
            														    $put_trade_arr = explode(',', $val['put_trade']);
            														    $put_trade_str = '';
            														    foreach ($put_trade_arr as $k => $v) {
            														        $put_trade_str .= $put_trade[$v].',';
            														    }
            														    echo $put_trade_str;
            													    }?>
            													</td>
            													<td><?php echo $val['type'];?></td>
            													<td><?php if(isset($houses_grade[$val['grade']])) echo $houses_grade[$val['grade']];?></td>
            													<td><?php if($val['deliver_year'] == '0000') echo ''; else echo $val['deliver_year'];?></td>
            													<td><?php if($val['is_check_out'] == 1) echo '是'; else echo '否';?></td>
            													<td><?php echo $val['property_company'];?></td>
            													<td><?php if($val['count_1']['count']) echo $val['count_1']['count'];?></td>
            													<td><?php if($val['count_2']['count']) echo $val['count_2']['count'];?></td>
            													<td><?php if($val['count_3']['count']) echo $val['count_3']['count'];?></td>
            													<td><?php if($val['count_4']['count']) echo $val['count_4']['count'];?></td>
            													<td><?php echo $val['remarks'];?></td>
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
                            	<?php if($area_status):?>
                            	<div id="housesarea" class="tab-pane <?php if($tab == 'housesarea'){echo 'in active';}?>">
                            		<iframe class="myiframe" src="/housesarea" frameborder="0" width="100%" height="100%" scrolling="no"></iframe>
                            	</div>
                            	<?php endif;?>
                            	<?php if($housesareatype_status):?>
                            	<div id="housesareatype" class="tab-pane <?php if($tab == 'housesareatype'){echo 'in active';}?>">
                            		<iframe class="myiframe" src="/housesareatype" frameborder="0" width="100%" height="100%" ></iframe>
                            	</div>
                            	<?php endif;?>
                            	<?php if($housesinstall_status):?>
                            	<div id="housesinstall" class="tab-pane <?php if($tab == 'housesinstall'){echo 'in active';}?>">
                            		<iframe class="myiframe" src="/housesinstall" frameborder="0" width="100%" height="100%"></iframe>
                            	</div>
                            	<?php endif;?>
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
var ifm = document.getElementsByClassName('myiframe');
console.log(ifm);
for(var i=0 ;i<ifm.length; i++){
	ifm[i].height = document.documentElement.clientHeight;
}

$(".select2").css('width','230px').select2({allowClear:true});
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php if(isset($province)) { echo $province;}else{?>贵州省<?php }?>",
		city: "<?php if(isset($city)) { echo $city;}else{?>贵阳市<?php }?>",
		district : "<?php if(isset($area)) { echo $area;}?>",
	});

	$(function(){
		$(".btn-export").click(function(){
        	$("#search-form").attr('action', '/houses/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
       });
	   $(".btn-export-one").click(function(){
        	var houses_id = $('select[name="name"]').val();
            if(houses_id==""){
				layer.alert("请选择楼盘");
				return;
            }
            location.href="/houses/load_accept?houses_id="+houses_id;
       });
	   
	});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
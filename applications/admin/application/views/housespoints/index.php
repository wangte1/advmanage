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
                        <span>点位管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housespoints/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增点位</a>
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
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位类型 </label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="type_id">
                                                		<option value="">全部</option>
				                                    	<?php foreach ($tlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['type'];?>" <?php if($v['type'] == $type_id) {?>selected="selected"<?php }?>><?php echo $order_type_text[$v['type']];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘 </label>
                                                <div class="col-sm-9">
                                                	<select id="houses" class="select2" data-placeholder="Click to Choose..." name="houses_id">
                                                		<option value="">全部</option>
				                                    	<?php foreach ($hlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属组团 </label>
                                                <div class="col-sm-9">
                                                	<select id="area" class="select2" data-placeholder="Click to Choose..." name="area_id" onchange="get_buf_info();">
                                                		<option value="">全部</option>
                                                		<?php if(isset($houses_id) && isset($area_list)):?>
                                                		<?php foreach ($area_list as $k => $v):?>
                                                		<option value="<?php echo $v['id'];?>" <?php if(isset($area_id) && $area_id == $v['id']){echo 'selected="selected"';}?>><?php echo $v['name'];?></option>
				                                    	<?php endforeach;?>
				                                    	<?php endif;?>
				                                    </select>
                                                </div>
                                            </div>
                                		
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼栋 </label>
                                                <div class="col-sm-9">
                                                	<select id="ban-sel" class="select2" data-placeholder="Click to Choose..." name="ban">
                                                		<option value="">全部</option>
                                                		<?php $banArr = array_unique(array_column($buf, 'ban'));?>
				                                    	<?php foreach ($banArr as $k => $v) {?>
				                                    		<?php if($v != '') {?>
				                                    			<option value="<?php echo $v;?>" <?php if($v == $ban) {?>selected="selected"<?php }?>><?php echo $v;?></option>
				                                    		<?php }?>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                      	</div>
                                		<div class="form-group">
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 单元 </label>
                                                <div class="col-sm-9">
                                                	<select id="unit-sel" class="select2" data-placeholder="Click to Choose..." name="unit">
                                                		<option value="">全部</option>
				                                    	<?php $unitArr = array_unique(array_column($buf, 'unit'));?>
				                                    	<?php foreach ($unitArr as $k => $v) {?>
				                                    		<?php if($v != '') {?>
				                                    			<option value="<?php echo $v;?>" <?php if($v == $unit) {?>selected="selected"<?php }?>><?php echo $v;?></option>
				                                    		<?php }?>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                             <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼层 </label>
                                                <div class="col-sm-9">
                                                	<select id="floor-sel" class="select2" data-placeholder="Click to Choose..." name="floor">
                                                		<option value="">全部</option>
                                                		<?php $floorArr = array_unique(array_column($buf, 'floor'));?>
				                                    	<?php foreach ($floorArr as $k => $v) {?>
				                                    		<?php if($v != '') {?>
				                                    			<option value="<?php echo $v;?>" <?php if($v == $floor) {?>selected="selected"<?php }?>><?php echo $v;?></option>
				                                    		<?php }?>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位位置</label>
                                                <div class="col-sm-9">
                                                	<select id="addr" class="select2" data-placeholder="Click to Choose..." name="addr">
                                                		<option value="">全部</option>
                                                		<option value="1" <?php if($addr == 1) {?>selected="selected"<?php }?>>门禁</option>
                                                		<option value="2" <?php if($addr == 2) {?>selected="selected"<?php }?>>电梯前室</option>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 锁定状态 </label>
                                                <div class="col-sm-9">
                                                	<select id="is_lock" class="select2" data-placeholder="Click to Choose..." name="is_lock">
                                                		<option value="">全部</option>
                                                		<?php foreach (C('housesscheduledorder.point_status') as $k => $v):?>
                                                		<option value="<?php echo $k;?>" <?php if(isset($is_lock) && $is_lock == $k){echo 'selected="selected"';}?>><?php echo $v;?></option>
				                                    	<?php endforeach;?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                     	</div>
                                    	<div class="form-group">
                                    		<div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位状态 </label>
                                                <div class="col-sm-9">
                                                	<select id="area" class="select2" data-placeholder="Click to Choose..." name="point_status">
                                                		<option value="">全部</option>
                                                		<?php foreach (C('public.points_status') as $k => $v):?>
                                                		<option value="<?php echo $k;?>" <?php if(isset($point_status) && $point_status== $k){echo 'selected="selected"';}?>><?php echo $v;?></option>
				                                    	<?php endforeach;?>
				                                    </select>
                                                </div>
                                            </div>
                                    		
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 占用客户 </label>
                                                <div class="col-sm-9">
                                                	<select id="area" class="select2" data-placeholder="Click to Choose..." name="customer_id">
                                                		<option value="">全部</option>
                                                		<?php foreach ($customers as $k => $v):?>
                                                		<option value="<?php echo $v['id'];?>" <?php if(isset($customer_id) && $customer_id== $v['id']){echo 'selected="selected"';}?>><?php echo $v['name'];?></option>
				                                    	<?php endforeach;?>
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
                                                <th>点位编号</th>
                                                <th>所属楼盘</th>
                                                <th>所属组团</th>
                                                <th>楼栋</th>
                                                <th>单元</th>
                                                <th>楼层</th>
                                                <th>点位位置</th>
                                                <th>类型</th>
                                                <th>占用客户</th>
                                                <th>状态</th>
                                                <th>锁定状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><a href=""><?php echo $val['code'];?></a></td>
                                                    <td>
                                                    	<?php foreach ($hlist as $k => $v) {?>
                                                    		<?php if($v['id'] == $val['houses_id']) {?>
                                                    			<?php echo $v['name'];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
                                                    <td>
                                                    	<?php foreach ($alist as $k => $v) {?>
                                                    		<?php if($v['id'] == $val['area_id']) {?>
                                                    			<?php echo $v['name'];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
                                                    <td><?php echo $val['ban'];?></td>
                                                    <td><?php echo $val['unit'];?></td>
                                                    <td><?php echo $val['floor'];?></td>
                                                    <td>
                                                    	<?php if($val['addr'] == 1) {echo '门禁';}else if($val['addr'] == 2){echo '电梯前室';}?>
                                                    </td>
                                                    <td>
                                                    	<?php foreach ($tlist as $k => $v) {?>
                                                    		<?php if($v['type'] == $val['type_id']) {?>
                                                    			<?php echo $order_type_text[$v['type']];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
													<td>
														<?php if(isset($customer_name[$val['customer_id']])) echo $customer_name[$val['customer_id']];?>
													</td>
													<td>
                                                        <?php 
	                                                        switch ($val['point_status']) {
	                                                            case '1':
	                                                                $class = 'badge-success';
	                                                                break;
	                                                            case '2':
	                                                                $class = 'badge-warning';
	                                                                break;
	                                                            case '3':
	                                                                $class = 'badge-danger';
	                                                                break;
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php echo C('public.points_status')[$val['point_status']];?>
	                                                    </span>
                                                    </td>
                                                    <td>
                                                        <?php 
	                                                        switch ($val['is_lock']) {
	                                                            case '1':
	                                                                $class = 'badge-warning';
	                                                                break;
	                                                            default:
	                                                                $class = 'badge-success';
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php echo C('housesscheduledorder.point_status')[$val['is_lock']];?>
	                                                    </span>
                                                    </td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housespoints/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/housespoints/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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


<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
	var buf_info = '';
	
    $(function(){
       $(".select2").css('width','230px').select2({allowClear:true});
    });
    $('#houses').change(function(){
        $('#area').html();
        $('.select2-chosen:eq(2)').text('全部');
        var areaStr = '<option value="">全部</option>';
    	var houses_id = $(this).val();
    	$.post('/housespoints/get_area', {'houses_id':houses_id}, function(data){
    		if(data.code == 1){
				for(var i=0; i < data.list.length; i++){
					areaStr += '<option value="'+data.list[i]["id"]+'">'+data.list[i]["name"]+'</option>';
				}
        	}
    		$("#area").html(areaStr);

    		get_buf_info();
    	});
    });

    $('#ban-sel').change(function(){
        var ban_val = $(this).val();
        var unitArr = new Array();
        var unitStr = '<option value="">选择单元</option>';
		for(var i = 0; i < buf_info.length; i++) {
			if(buf_info[i]['ban'] != '' && ban_val == buf_info[i]['ban'] && unitArr.indexOf(buf_info[i]['unit']) == -1) {
				unitArr[i] = buf_info[i]['unit'];
				unitStr += '<option value="'+buf_info[i]['unit']+'">'+buf_info[i]['unit']+'</option>'
				$.unique(unitArr);
			}
		}

		$('#unit-sel').html(unitStr);

		var floorArr = new Array();
        var floorStr = '<option value="">选择楼层</option>';
		for(var i = 0; i < buf_info.length; i++) {
			if(buf_info[i]['ban'] != '' && ban_val == buf_info[i]['ban'] && floorArr.indexOf(buf_info[i]['floor']) == -1) {
				floorArr[i] = buf_info[i]['floor'];
				floorStr += '<option value="'+buf_info[i]['floor']+'">'+buf_info[i]['floor']+'</option>'
				$.unique(floorArr);
			}
		}

		$('#floor-sel').html(floorStr);
		
    });


    function get_buf_info() {
    	var houses_id = $("#houses").val();
    	var area_id = $("#area").val();

    	$.post('/housespoints/get_buf_info',{houses_id:houses_id, area_id:area_id},function(data){
			if(data.code == 1) {
				buf_info = data.list;

				var banArr = new Array();
				var banStr = '<option value="">选择楼栋</option>';
				for(var i = 0; i < data.list.length; i++) {
					if((data.list)[i]['ban'] != '' && banArr.indexOf((data.list)[i]['ban']) == -1) {
						banArr[i] = (data.list)[i]['ban'];
						banStr += '<option value="'+(data.list)[i]['ban']+'">'+(data.list)[i]['ban']+'</option>'
						$.unique(banArr);
					}
				}

				$('#ban-sel').html(banStr);
				
			}
		});
    	
    }
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
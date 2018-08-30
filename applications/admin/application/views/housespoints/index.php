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
                	<a href="javascript:;" class="btn btn-sm btn-primary btn-export"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出</a>
                	<a href="/housespoints/partition" class="btn btn-sm btn-primary"><i class="ace-icon glyphicon glyphicon-edit" aria-hidden="true"></i> 分配区域</a>
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
                                            
                                            <div class="col-sm-6">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 行政区域 </label>
                                                <div class="col-sm-9">
                                              		<div id="distpicker1">
													  <select name="province" id="province"></select>
													  <select name="city" id="city"></select>
													  <select name="area" id="area0"></select>
													</div>
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
                                            
                                      	</div>
                                		<div class="form-group">
                                            
                                            
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
                                                		<?php foreach (C('housespoint.point_addr') as $k => $v):?>
                                                			<option value="<?php echo $k;?>" <?php if(isset($addr) && $addr== $k){echo 'selected="selected"';}?>><?php echo $v;?></option>
				                                    	<?php endforeach;?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                     	</div>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位状态 </label>
                                                <div class="col-sm-9">
                                                	<select  class="select2" data-placeholder="Click to Choose..." name="point_status">
                                                		<option value="">全部</option>
                                                		<?php foreach (C('housespoint.points_status') as $k => $v):?>
                                                		<option value="<?php echo $k;?>" <?php if(isset($point_status) && $point_status== $k){echo 'selected="selected"';}?>><?php echo $v;?></option>
				                                    	<?php endforeach;?>
				                                    </select>
                                                </div>
                                            </div>
                                    		
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 占用客户 </label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="customer_id">
                                                		<option value="">全部</option>
                                                		<?php foreach ($customers as $k => $v):?>
                                                		<option value="<?php echo $v['id'];?>" <?php if(isset($customer_id) && $customer_id== $v['id']){echo 'selected="selected"';}?>><?php echo $v['name'];?></option>
				                                    	<?php endforeach;?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位编号 </label>
                                                <div class="col-sm-9">
                                                	<input type="text" name="code" value="<?php if(isset($point_code)) echo $point_code;?>">
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
                                                <th>行政区域</th>
                                                <th>所属楼盘</th>
                                                <th>所属组团</th>
                                                <th>楼栋</th>
                                                <th>单元</th>
                                                <th>楼层</th>
                                                <th>点位位置</th>
                                                <th>类型</th>
                                                <th>可投放数量</th>
                                                <th>已投放数量</th>
                                                <th>总投放次数</th>
                                                <th>锁定数</th>
                                                <th>状态</th>
                                                <th>占用客户</th>
                                                <th>备注</th>
                                                <th>报损</th>
                                                <th>巡视照片</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="layer-photos-demo" class="layer-photos-demo">
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><a href=""><?php echo $val['code'];?></a></td>
                                                    <td>
                                                    	<?php echo $val['province'].'-'.$val['city'].'-'.$val['area'];?>
                                                    </td>
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
                                                    	<?php if(isset(C('housespoint.point_addr')[$val['addr']])) echo C('housespoint.point_addr')[$val['addr']];?>
                                                    </td>
                                                    <td>
                                                    	<?php foreach ($tlist as $k => $v) {?>
                                                    		<?php if($v['type'] == $val['type_id']) {?>
                                                    			<?php echo $order_type_text[$v['type']];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
													<td><?php echo $val['ad_num']?></td>
													<td><?php echo $val['ad_use_num']?></td>
													<td><?php echo $val['used_num']?></td>
													<td><?php echo $val['lock_num']?></td>
													<td>
                                                        <?php 
	                                                        switch ($val['point_status']) {
	                                                            case '1':
	                                                                $class = 'badge-success';
	                                                                break;
	                                                            case '4':
	                                                                $class = 'badge-warning';
	                                                                break;
	                                                            case '3':
	                                                                $class = 'badge-danger';
	                                                                break;
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php echo C('housespoint.points_status')[$val['point_status']];?>
	                                                    </span>
                                                    </td>
                                                    <td>
                                                    	<?php if(isset($val['customer_id'])):?>
                                                    	<?php foreach (explode(',', $val['customer_id']) as $k => $v):?>
                                                    	<?php foreach ($customers as $k1 => $v2):?>
                                                    	<?php if($v2['id'] == $v):?>
                                                    	<?php if($k!=0){echo ',';} echo $v2['name'];?>
                                                    	<?php endif;?>
                                                    	<?php endforeach;?>
                                                    	<?php endforeach;?>
                                                    	<?php endif;?>
													</td>
													<td><?php echo $val['remarks']?></td>
													<td>
														<?php if($val['point_status'] == 4):?>
														<button class="btn-primary see-report">已报损</button>
														<?php endif;?>
													</td>
													<td>			
														<?php if(!empty($val['tour_img'])):?>					
															<img alt="点位编号：<?php echo $val['code'];?>" layer-src="<?php echo get_adv_img($val['tour_img'])?>" src="<?php echo get_adv_img($val['tour_img'], 'thumb')?>" style="width:25px;height:25px;cursor:pointer;">
														<?php endif;?>
													</td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                       		<?php if($val['point_status'] == 4):?>
                                                        	<!--a class="green tooltip-info reported" point_id="<?php echo $val['id'];?>" point_code="<?php echo $val['code'];?>" data-rel="tooltip" data-placement="top" data-original-title="修复">
                                                                <i class="ace-icon glyphicon glyphicon-refresh bigger-130"></i>
                                                            </a-->
                                                            <!--a class="green tooltip-info report_img" img="<?php echo $val['destroy_img'];?>" data-rel="tooltip" data-placement="top" data-original-title="查看报损图片">
                                                                <i class="ace-icon glyphicon glyphicon-picture bigger-130"></i>
                                                            </a-->
                                                            <?php endif;?>
                                                            <?php if($val['point_status'] != 4 && $val['can_report']):?>
                                                        	<a class="green tooltip-info reportnow" point_id="<?php echo $val['id'];?>" point_code="<?php echo $val['code'];?>" data-status="<?php echo $val['point_status']?>" data-rel="tooltip" data-placement="top" data-original-title="报损">
                                                                <i class="ace-icon fa fa-gavel bigger-130"></i>
                                                            </a>
                                                            <?php endif;?>
                                                            <a class="green tooltip-info" href="/housespoints/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info m-del" href="javascript:;" data-url="/housespoints/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
	var buf_info = '';
	
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php if(isset($province)) { echo $province;}else{?>贵州省<?php }?>",
		city: "<?php if(isset($city)) { echo $city;}else{?>贵阳市<?php }?>",
		district : "<?php if(isset($area)) { echo $area;}?>",
	});
	
    $(function(){
       $(".select2").css('width','230px').select2({allowClear:true});

       $(".btn-export").click(function(){
        	$("#search-form").attr('action', '/housespoints/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
       });
       
       $("#distpicker1 select").change(function(){
			var province = $("#province").val();
			var city = $("#city").val();
			var area = $("#area0").val();
			
			$.post('/housespoints/ajax_houses_info',{province:province,city:city,area:area},function(data){
				if(data) {
					$('.select2-chosen:eq(2)').text('--请选择组团--');
					$('.select2-chosen:eq(1)').text('--请选择楼盘--');
					var housesStr = '<option value="">--请选择楼盘--</option>';
					for(var i = 0; i < data.length; i++) {

						housesStr += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
					}
					
					$("select[name='houses_id']").html(housesStr);

					//getArea();
				}
			});
			
       });
    });
	
	$('.m-del').click(function(){
		var url = $(this).attr('data-url');
		layer.confirm('确认要删除该点位吗？', {
			  btn: ['确认','取消'] //按钮
			}, function(){
				location.href = url;
			});
	});

	$('.see-report').on('click', function(){
		layer.msg('请打开报损列表查看');
	});
    
    $('#houses').change(function(){
        $('#area').html();
        $('#s2id_area,#s2id_ban-sel,#s2id_unit-sel,#s2id_floor-sel').find('.select2-chosen').text('全部');
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
    //修复点位
    $('.reported').on('click', function(){
        return;
		var id = $(this).attr('point_id');
		var code = $(this).attr('point_code');
		layer.confirm(
			'确定点位 '+code+' 已经修复？',
			{
			  	btn: ['确定','取消'] //按钮
			}, 
			function(){
				$.post('/housespoints/reported', {'id':id}, function(data){
					if(data.code == 1){
						window.parent.location.reload(); //刷新父页面
						return;
					}
					layer.msg(data.msg, {icon: 2});
				});
			}, 
			function(){
			  	layer.close();
			}
		);
    });

    //报修点位
    $('.report_img').on('click', function(){
		var img = $(this).attr('img');
		if(img == "") {layer.msg('该点位没有上传报损图片');return;}
		layer.open({
			  type: 1,
			  area: ['50%', '50%'], //宽高
			  content: '<div style="width:100%;height:100%;text-align: center;"><img src="'+img+'"></div>'
		});
    });
    
    //报修点位
    $('.reportnow').on('click', function(){
		var id = $(this).attr('point_id');
		var code = $(this).attr('point_code');
		var status  =$(this).attr('data-status');
		if(status == 3){
			layer.alert('该点位已被占用，确认要提交报损吗？', function(){
				show_report_add(id, code);
			});
			return;
		}
		show_report_add(id, code);
		
    });

    function show_report_add(id, code){
    	layer.open({
    		  type: 2,
    		  title: '编号: '+code+' 点位报修',
    		  shadeClose: true,
    		  shade: 0.8,
    		  area: ['50%', '70%'],
    		  content: '/housespoints/report?id='+id
    	});
    }
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

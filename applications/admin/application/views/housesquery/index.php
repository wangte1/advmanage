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
                        <span>位置查询</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
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
                                    <form id="search-form" class="form-horizontal" role="form">
                                        <div class="form-group">
                                            
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘 </label>
                                                <div class="col-sm-9">
                                                	<select id="houses" class="select2" data-placeholder="Click to Choose..." name="houses_id">
                                                		<option value="">请选择楼盘</option>
				                                		<?php foreach ($list as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
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
                                                <th>楼盘名称</th>
                                                <th>地区</th>
                                                <th>具体位置</th>
                                                <th>规划入住户</th>
                                                <th>入住率</th>
                                                <th>禁投放行业</th>
                                                <th>等级</th>
                                                <th>交付年份</th>
                                                <th>门禁点位数</th>
                                                <th>地面电梯前室点位数</th>
                                                <th>地下电梯前室	点位数</th>
                                                <th>合计点位数</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                	<td><?php echo $val['name'];?></td>
                                                	<td><?php echo $val['province'];?>-<?php echo $val['city']?>-<?php echo $val['area']?></td>
                                                	<td><?php echo $val['position']?></td>
                                                	<td><?php echo $val['households']?></td>
                                                	<td><?php echo $val['occ_rate'] * 100 . '%'?></td>
                                                	<td><?php echo $val['put_trade']?></td>
                                                	<td><?php echo $val['grade']?></td>
                                                	<td><?php echo $val['deliver_year']?></td>
                                                	<td><?php echo $count['count_1']?></td>
                                                	<td><?php echo $count['count_2']?></td>
                                                	<td><?php echo $count['count_3']?></td>
                                                	<td><?php echo $count['count_4']?></td>
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
    	$.post('/housesquery/get_houses',{},function(data){
        	console.log(data);
			if(data) {
				var housesStr = '<option value="">--请选择楼盘--</option>';
				for(var i = 0; i < data.list.length; i++) {
					housesStr += '<option value="' + data.list[i].id + '">' + data.list[i].name + '</option>';
				}
				$("select[name='houses_id']").html(housesStr);
			}
		});
       $(".select2").css('width','230px').select2({allowClear:true});

       $(".btn-export").click(function(){
        	$("#search-form").attr('action', '/housespoints/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
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
			layer.alert('占用点位在pc端不支持报损，请先将该点位从订单中移除再进行报损操作');
			return;
		}
		layer.open({
		  type: 2,
		  title: '编号: '+code+' 点位报修',
		  shadeClose: true,
		  shade: 0.8,
		  area: ['50%', '70%'],
		  content: '/housespoints/report?id='+id
		});
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

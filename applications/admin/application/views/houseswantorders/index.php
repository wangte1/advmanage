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
                        <a href="#">社区订单管理</a>
                    </li>
                    <li class="active">意向订单</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/houseswantorders/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新建意向订单</a>
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
                                        	<div class="col-sm-6">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 行政区域 </label>
                                                <div class="col-sm-9">
                                                    <div id="distpicker1">
													  <select name="province" id="province"></select>
													  <select name="city" id="city"></select>
													  <select name="area" id="area"></select>
													</div>
                                                </div>
                                            </div>
                                        	
                                        	<div class="col-sm-6">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 投放行业 </label>
                                                <div class="col-sm-9">
                                                    <select name="put_trade" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach (C('housespoint.put_trade') as $k => $v):?>
                                                        <option value="<?php echo $k;?>" <?php if($put_trade == $k){ echo "selected"; }?>><?php echo $v;?></option>
                                                       	<?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                             			</div>
                             			<div class="form-group">
                                            
                             				<div class="col-sm-6">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户 </label>
                                                <div class="col-sm-9">
                                                    <select name="customer_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($customer_list as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>
                                                <div class="col-sm-9">
                                                    <select name="status" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach(C('houseswantorder.houses_want_status')as $key => $value): ?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $status){ echo "selected"; }?>><?php echo $value;?></option>
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
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>序号</th>
                                                <th>客户</th>
                                                <th>行政区域</th>
                                                <th>楼盘类型</th>
                                                <th>点位类型</th>
                                                <th>交房年份</th>
                                                <th>投放行业</th>
                                                <th>预定点位数量</th>
                                                <th>状态</th>
                                                <th>业务员</th>
                                                <th>创建时间</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($list) && $list):?>
                                            <?php foreach ($list as $key => $value) : ?>
                                            	<tr>
                                            		<td><?php echo $key+1;?></td>
                                            		<td>
                                            			<?php foreach($customers as $k => $v) {?>
                                            				<?php if($v['id'] == $value['customer_id']) { echo $v['name'];}?>
                                            			<?php }?>
                                            		</td>
                                            		<td><?php echo $value['province'].$value['city'].$value['area'];?></td>
                                            		<td>
                                            			<?php if(isset($value['houses_type'])) {
                                            				$tmp_arr = explode(',', $value['houses_type']);
                                            				$tmp_str = '';
                                            				foreach($tmp_arr as $k => $v) {
                                            					if(isset($houses_type_text[$v])) {
                                            						$tmp_str .= $houses_type_text[$v] . ',';
                                            					}
                                            				}
                                            				echo $tmp_str;
                                            			}
                                            			?>
                                            		</td>
                                            		<td><?php echo $order_type_text[$value['order_type']];?></td>
                                            		<td><?php echo $value['begin_year']."-".$value['end_year'];?></td>
                                            		<td><?php if(isset($value['put_trade']) && $value['put_trade'] > 0) echo C('housespoint.put_trade')[$value['put_trade']];?></td>
                                            		<td><?php echo $value['points_count'];?></td>
                                            		<td>
                                            			<?php 
	                                                        switch ($value['status']) {
	                                                            case '1':
	                                                                $class = 'badge-yellow';
	                                                                break;
	                                                            case '2':
	                                                                $class = 'badge-success';
	                                                                break;
	                                                            case '3':
	                                                                $class = 'badge-success';
	                                                                break;
	                                                            case '4':
	                                                                $class = 'badge-danger';
	                                                                break;
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php if(isset(C('houseswantorder.houses_want_status')[$value['status']])) echo C('houseswantorder.houses_want_status')[$value['status']];?>
	                                                    </span>
                                            			
                                            		</td>
                                            		<td>
                                            			<?php foreach($admins as $k => $v) {?>
                                            				<?php if($v['id'] == $value['create_user']) { echo $v['fullname'];}?>
                                            			<?php }?>
                                            		</td>
                                            		<td><?php echo $value['create_time'];?></td>
                                            		<td>
                                            			<a class="green tooltip-info" href="/houseswantorders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a> 
                                                        
                                                        <?php if($value['status'] == 1 && $userInfo['id'] == $value['create_user']) {?>
	                                                        <a class="green tooltip-info" onclick="cancle(this);" data-id="<?php echo $value['id'];?>" data-rel="tooltip" data-placement="top" title="" data-original-title="撤回">
	                                                            <i class="icon-reply bigger-130"></i>
	                                                        </a> 
                                                        <?php }?>
                                                        
                                                        <?php if($value['status'] == 1) {?>
	                                                        <a class="grey tooltip-info check"  data-id="<?php echo $value['id'];?>" data-customer="2" data-rel="tooltip" data-placement="top" data-original-title="审核">
	                                                            <i class="ace-icon glyphicon glyphicon-check bigger-130" aria-hidden="true"></i>
	                                                        </a>
                                                        <?php }?>
                                                        <?php if($value['status'] == 2) {?>
	                                                        <a class="grey tooltip-info checkout" href="/houseswantorders/checkout/<?php echo $value['id'];?>" data-id="129" data-customer="2" data-rel="tooltip" data-placement="top" data-original-title="转预定订单">
	                                                            <i class="ace-icon fa fa-random bigger-130" aria-hidden="true"></i>
	                                                        </a>
                                                        <?php }?>
                                            		</td>
                                            	</tr>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                        </tbody>
                                    </table>
                                    <!-- 分页 -->
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
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>

<script type="text/javascript">
    $(".select2").css('width','240px').select2({allowClear:true});

    $("#distpicker1").distpicker({
    	province: '<?php if($province){ echo $province;}else {?>贵州省<?php }?>',
    	city: '<?php if($city){ echo $city;}else {?>贵阳市<?php }?>',
    	district: '<?php if($area){ echo $area;}else {?>—— 区 ——<?php }?>'
    });
	//业务主管审核
    $('.check').on('click', function(){
    	var id = $(this).attr('data-id');
    	layer.confirm('您的审核意见是？', {
		 	btn: ['通过','不通过'] //按钮
		}, function(){
			$.post('/houseswantorders/check', {'id':id, 'status':2}, function(data){
				if(data) {
					layer.alert(data.msg, function(){
						location.reload();
					});
				}
			});
		},function(){
			$.post('/houseswantorders/check', {'id':id, 'status':4}, function(data){
				if(data) {
					layer.alert(data.msg, function(){
						location.reload();
					});
				}
			});
		});
    });

	function cancle(obj) {
		var id = $(obj).attr('data-id');
		layer.confirm('您确认要撤回吗？', {
			 	btn: ['确定','取消'] //按钮
			}, function(){
				$.post('/houseswantorders/cancle', {id:id}, function(data){
					if(data) {
						layer.alert(data.msg, function(){
							location.reload();
						});
					}
				});
			});
	}
	
    

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

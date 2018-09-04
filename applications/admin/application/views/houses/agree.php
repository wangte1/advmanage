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
                        <span>合同管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housesagree/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加合同</a>
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
                                            <div class="col-sm-3">
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
                                        	<div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 存档编号： </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="col-sm-10" name="doc_num" value="<?php if(isset($doc_num)){echo $doc_num;}?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 物业公司： </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="col-sm-10" name="pm_company" value="<?php if(isset($pm_company)){echo $pm_company;}?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 开发负责人： </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="col-sm-10" name="develer" value="<?php if(isset($develer)){echo $develer;}?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        	<div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 合同开始时间： </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker" type="text" name="agree_start_date" value="<?php if(isset($agree_start_date)){ echo $agree_start_date;}?>" >
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 合同结束时间： </label>
                                                <div class="col-sm-9">
                                                    <div class="input-group date datepicker">
                                                        <input class="form-control date-picker" type="text" name="agree_end_date" value="<?php if(isset($agree_end_date)){ echo $agree_end_date;}?>" >
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar bigger-110"></i>
                                                        </span>
                                                    </div>
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
                                            	<th>ID</th>
                                            	<th>存档编号</th>
                                                <th>物业公司 </th>
                                                <th>合同开始时间 </th>
                                                <th>合同结束时间 </th>
                                                <th>开发负责人 </th>
                                                <th>物业负责人 </th>
                                                <th>负责人职务 </th>
                                                <th>负责人电话 </th>
                                                <th>签约日期 </th>
                                                <th>签约楼盘 </th>
                                                <th>合同金额 </th>
                                                <th>支付方式</th>
                                                <th>已付金额</th>
                                                <th>开票类型 </th>
                                                <th>已收发票金额</th>
                                                <th>递增方式 </th>
                                                <th>咨询费</th>
                                                <th>备注 </th>
                                                <th>录入人 </th>
                                                <th>录入日期 </th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                       
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr id="tr_<?php echo $val['id']?>">
                                                   	<td><?php echo $val['id']?></td>
                                                   	<td><?php echo $val['doc_num']?></td>
                                                	<td><?php echo $val['pm_company']?></td>
                                                    <td><?php echo $val['agree_start_date']?></td>
                                                    <td><?php echo $val['agree_end_date']?></td>
                                                    <td><?php echo $val['develer']?></td>
                                                    <td><?php echo $val['property_owner']?></td>
                                                    <td><?php echo $val['principal_duty']?></td>
                                                    <td><?php echo $val['principal_tel']?></td>
                                                    <td><?php echo $val['sign_date']?></td>
                                                    <td><?php echo $val['house_list']?></td>
                                                    <td><?php echo $val['agree_price']?></td>
                                                    <td><?php echo $val['pay_method']?></td>
                                                    <td><?php echo $val['paid_money']?></td>
                                                    <td><?php echo $val['invoice_type']?></td>
                                                    <td><?php echo $val['received_invoice']?></td>
                                                    <td><?php echo $val['incr_type']?></td>
                                                    <td><?php echo $val['consult_cost']?></td>
                                                    <td><?php echo $val['remak']?></td>
                                                    <td><?php echo $val['create_user_name']?></td>
                                                    <td><?php echo $val['create_time']?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                           <a class="green tooltip-info" href="/housesagree/edit/<?php echo $val['id']?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                           </a>
                                                           <a class="red tooltip-info delagree" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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
        	$("#search-form").attr('action', '/housesagree/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
       });
	});

	$('.delagree').on("click", function(){
		var _this = $(this);
		var id = _this.attr('data-id');
		var index = layer.alert('您确认要删除该合同吗？', function(){
			layer.close(index);
			$.get("/housesagree/del", {'id':id}, function(data){
				if(data.code == 1){
					layer.msg(data.msg, {time:1000}, function(){
						$("#tr_"+id).remove();
					});
					return;
				}
				layer.alert(data.msg);
			});
		});
	});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
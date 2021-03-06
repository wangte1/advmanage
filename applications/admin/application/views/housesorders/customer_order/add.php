<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('bootstrap-timepicker.css', 'admin');?>" rel="stylesheet" />
<style type="text/css">
    #scrollTable table {
      margin-bottom: 0;
    }
    #scrollTable .div-thead {
    }
    #scrollTable .div-tbody{
      width:100%;
      height:450px;
      overflow:auto;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        padding: 4px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
        text-align: center;
    }
    #points_lists tr:hover, #selected_points tr:hover{background:#6fb3e0;}
</style>
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
                        <a href="/housesorders">订单管理</a>
                    </li>
                    <li class="active">编辑订单</li>
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
                    <h1>
                        编辑<?php echo $order_type_text[$order_type];?>订单
                        <a href="/housesorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>基本信息</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>
										<?php if(isset($info)):?>
                                        <input type="hidden" name="id" value="<?php echo $info['id']?>" />
                                        <?php endif;?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告客户： </label>
                                            <div class="col-sm-10">
                                                <select id="lock_customer_id" name="customer_id" class="select2" required>
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <?php if($val['is_self'] == 0):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['customer_id']) && $val['id'] == $info['customer_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                    <?php endif;?>
                                                    <?php endforeach;?>
                                                </select>
                                                
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 业务员： </label>
                                            <div class="col-sm-10">
                                                <select id="sales_id" name="sales_id" class="select2" required>
                                                    <option value="">请选择业务员</option>
                                                    <?php foreach($salesman as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['sales_id']) && $val['id'] == $info['sales_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" id="lock_start_time" type="text" name="release_start_time" value="<?php if(isset($info['release_start_time'])){ echo $info['release_start_time'];} else { echo date('Y-m-d'); }?>" >
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" type="text" name="release_end_time" value="<?php if(isset($info['release_end_time'])){ echo $info['release_end_time'];} else { echo date("Y-m-d",strtotime("+7 day"));} ?>">
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4" style="padding-top: 6px">
                                                <a href="javascript:;" data-rel="popover" title="说明" data-trigger="hover" data-content="锁定起止时间默认是从新建预定订单之日起一个星期之内，过了锁定结束时间，如果客户还没确认下单，系统将自动释放出该订单所有锁定点位。"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放点位： </label>
                                            <div style="width:100%" class="col-sm-10">
                                                <div class="widget-box" style="overflow: hidden;">
                                                    <div class="widget-header">
                                                        <h4>选择点位</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:650px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘名称： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select  id="houses_id" class="select2 ">
                                                                            <option value="">请选择楼盘</option>
                                                                            <?php foreach($housesList as $val):?>
                                                                            <option value="<?php echo $val['id'];?>" <?php if(isset($info['houses_id']) && $val['id'] == $info['houses_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 组团： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="area_id" id="area_id" class="select2">
                                                                            <option value="">请选择楼组团</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼栋： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="ban" id="ban" class="select2">
                                                                            <option value="">请选择楼栋</option>
                                                                            <?php if(!empty($BUFL['ban'])):?>
                                                                            <?php foreach ($BUFL['ban'] as $k => $v):?>
                                                                            <option value="<?php echo $v;?>"><?php echo $v;?></option>
                                                                            <?php endforeach;?>
                                                                            <?php endif;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 单元： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="unit" id="unit" class="select2">
                                                                            <option value="">请选择单元</option>
                                                                            <?php if(!empty($BUFL['unit'])):?>
                                                                            <?php foreach ($BUFL['unit'] as $k => $v):?>
                                                                            <option value="<?php echo $v;?>"><?php echo $v;?></option>
                                                                            <?php endforeach;?>
                                                                            <?php endif;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼层： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="floor" id="floor" class="select2">
                                                                            <option value="">请选择楼层</option>
                                                                            <?php if(!empty($BUFL['floor'])):?>
                                                                            <?php foreach ($BUFL['floor'] as $k => $v):?>
                                                                            <option value="<?php echo $v;?>"><?php echo $v;?></option>
                                                                            <?php endforeach;?>
                                                                            <?php endif;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 位置： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="addr" id="addr" class="select2">
                                                                            <option value="">请选择位置</option>
                                                                            <option value="1">门禁</option>
                                                                            <option value="2">电梯前室</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                	<br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 置业类型： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="houses_type" id="houses_type" class="select2">
                                                                        	<option value="">请选择置业类型</option>
                                                                            <?php foreach(C('zhiye') as $k => $v):?>
                                                                            <option value="<?php echo $k;?>" <?php if(isset($info['houses_type']) && $k == $info['houses_type']){ echo "selected"; }?>><?php echo $v;?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="10%">点位编号</th>
                                                                                <th width="10%">楼盘</th>
                                                                                <th width="10%">组团</th>
                                                                                <th width="10%">楼栋</th>
                                                                                <th width="10%">单元</th>
                                                                                <th width="10%">楼层</th>
                                                                                <th width="10%">置业类型</th>
                                                                                <th width="10%">位置</th>
                                                                                <th width="10%">可投放数</th>
                                                                                <th width="10%">状态</th>
                                                                                <th width="10%"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                <div class="div-tbody">
                                                                    <table id="sample-table-1" class="table table-bordered">
                                                                        <tbody id="points_lists">
                                                                            <?php if(isset($point_list) && $point_list):?>
                                                                            <?php foreach ($point_list as $k => $v):?>
                                                                            <tr point-id="<?php echo $v['id'];?>">
                                                                                <td width="10%"><?php echo $v['code'];?></td>
                                                                                <td width="10%"><?php echo $v['houses_name'];?></td>
                                                                                <td width="10%"><?php echo $v['houses_area_name'];?></td>
                                                                                <td width="10%"><?php echo $v['ban']?></td>
                                                                                <td width="10%"><?php echo $v['unit']?></td>
                                                                                <td width="10%"><?php echo $v['floor']?></td>
                                                                                <td width="10%">2233</td>
                                                                                <td width="10%">
                                                                                    <?php if(isset($point_addr[$v['addr']])) echo $point_addr[$v['addr']];?>
                                                                                </td>
                                                                                <td width="10%"><?php echo $v['ad_num'] - $v['ad_use_num']?></td>
                                                                                <td width="10%">
                                                                                    <?php if($v['point_status'] == 1) {?>
                                                                                	<span class="badge badge-success">空闲</span>
                                                                                	<?php }else if($v['point_status'] == 3) {?>
                                                                                	<span class="badge badge-danger">占用</span>
                                                                                	<?php }else if($v['point_status'] == 4) {?>
                                                                                	<span class="badge badge-danger">报损</span>
                                                                                	<?php }?>
                                                                                </td>
                                                                                <td width="10%">
                                                                                    <button class="btn btn-xs btn-info do-sel" type="button">选择点位<i class="icon-arrow-right icon-on-right"></i></button>
                                                                                </td>
                                                                             </tr>
                                                                             <?php endforeach;?>
                                                                            <?php endif;?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remarks'])) { echo $info['remarks'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <input type="hidden" name="point_ids_old" value="<?php echo $info['point_ids'];?>" />
                                                <?php endif;?>
                                                <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
                                                <input type="hidden" name="put_trade" value="<?php echo $put_trade;?>" />
                                                <input type="hidden" name="point_ids" value="<?php if(isset($info['point_ids'])) { echo $info['point_ids']; } ?>" />
                                                <button class="btn btn-info btn-save" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    保 存
                                                </button>

                                                &nbsp; &nbsp; &nbsp;
                                                <button class="btn" type="reset">
                                                    <i class="icon-undo bigger-110"></i>
                                                    重 置
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>已选择点位</h4>
                                <span class="widget-toolbar">
                                    已选择<span id="selected_points_num"><?php if(isset($selected_points)) { echo count($selected_points);} else { echo 0; }?></span>个点位
                                </span>
                            </div>

                            <div class="widget-body" style="height:1538px">
                                <div style="height: 100%;" class="widget-main">
                                    <div id="scrollTable">
                                        <div class="div-thead">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">点位编号</th>
                                                        <th width="10%">楼盘</th>
                                                        <th width="10%">组团</th>
                                                        <th width="10%">楼栋</th>
                                                        <th width="10%">单元</th>
                                                        <th width="5%">楼层</th>
                                                        <th width="10%">置业类型</th>
                                                        <th width="10%">位置</th>
                                                        <th width="10%">可投放数</th>
                                                        <th width="5%">状态</th>
                                                        <th width="10%"><button class="btn btn-xs btn-info remove-all" type="button">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="div-tbody" style="height: 1466px">
                                            <table id="sample-table-1" class="table table-bordered ">
                                                <tbody id="selected_points">
                                                    <?php if(isset($selected_points)):?>
                                                        <?php foreach($selected_points as $value):?>
                                                        <tr class="point" id="point_<?php echo $value['id'];?>" point-id="<?php echo $value['id'];?>">
                                                            <td width="10%"><?php echo $value['code'];?></td>
                                                            <td width="10%"><?php echo $value['houses_name'];?></td>
                                                            <td width="10%"><?php echo $value['houses_area_name'];?></td>
                                                            <td width="10%"><?php echo $value['ban'];?></td>
                                                            <td width="10%"><?php echo $value['unit'];?></td>
                                                            <td width="5%"><?php echo $value['floor'];?></td>
                                                            <td width="10%"><?php if(!empty($value['houses_type_name'])){echo $value['houses_type_name'];}else{echo '无';}?></td>
                                                            <?php if($value['addr'] == 1):?>
                                                            <td width="10%">门禁</td>
                                                            <?php else:?>
                                                            <td width="10%">电梯前室</td>
                                                            <?php endif;?>
                                                            <td width="10%"><?php echo $value['ad_num'] - $value['ad_use_num'];?></td>
                                                            <td width="5%">
                                                            	<?php 
                                                                    switch ($value['point_status']) {
                                                                        case '1':
                                                                            $class = 'badge-success';
                                                                            break;
                                                                        case '3':
                                                                            $class = 'badge-danger';
                                                                            break;
                                                                        case '4':
                                                                            $class = 'badge-danger';
                                                                            break;
                                                                    }
                                                                ?>
                                                                <span class="badge <?php echo $class; ?>">
                                                                    <?php echo C('housespoint.points_status')[$value['point_status']];?>
                                                                </span>
                                                            </td>
                                                            <td width="10%"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除点位<i class="fa fa-remove" aria-hidden="true"></i></button></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script> -->
<script type="text/javascript">
var p_area_id = 0;
$(function(){
	layer.msg('数据加载完毕!');
	var order_type = '<?php echo $order_type;?>';
	var put_trade = '<?php echo $put_trade;?>';
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});
    $('#timepicker1').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime: '18:00:00'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
	
    $('#area_id').on('click', function(){
    	p_area_id = $(this).val();
    });
	
	$('body').on('change', '#houses_id,#area_id,#ban,#unit,#floor,#addr,#houses_type', function(){
		var index = layer.load(0, {shade: true});
		var houses_id = $('#houses_id').val();
		var area_id = $('#area_id').val();
		if(area_id == ''){
			area_id = p_area_id;
		}
		var ban = $('#ban').val();
		var unit = $('#unit').val();
		var floor = $('#floor').val();
		var addr = $('#addr').val();
		var lock_start_time = $('#lock_start_time').val();
		var houses_type = $('#houses_type').val();
		var order_id = "<?php echo $info['id']?>";
		var postData = {order_id:order_id,order_type:order_type, put_trade:put_trade, houses_id:houses_id, area_id:area_id, ban:ban, unit:unit, floor:floor, lock_start_time:lock_start_time,addr:addr,houses_type:houses_type};
		$.post('/housesorders/get_points', postData, function(data){
			var pointStr =  '';
			var areaStr = '<option value="">请选择组团</option>'; 
			if(data.flag == true && data.count > 0) {
				$("#all_points_num").text(data.count);
				var tmpList = data.points_lists
				for(var i = 0; i < data.points_lists.length; i++) {
					pointStr += "<tr class='point' id='point_"+tmpList[i]['id']+"' point-id='"+tmpList[i]['id']+"'><td width='10%'>"+tmpList[i]['code']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['houses_name']+"</td>";
					if(typeof tmpList[i]['area_name'] === 'undefined'){
						pointStr += "<td width='10%'></td>";
					}else{
						pointStr += "<td width='10%'>"+tmpList[i]['area_name']+"</td>";
					}
					pointStr += "<td width='10%'>"+tmpList[i]['ban']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['unit']+"</td>";
					pointStr += "<td width='10%'>"+tmpList[i]['floor']+"</td>";
					if(tmpList[i]['houses_type_name'] == ''){
						pointStr += "<td width='10%'></td>";
					}else{
						pointStr += "<td width='10%'>"+tmpList[i]['houses_type_name']+"</td>";
					}
					if(tmpList[i]['addr'] == 1){
						pointStr += "<td width='10%'>门禁</td>";
					}else{
						pointStr += "<td width='10%'>电梯前室</td>";
					}
					var num = parseInt(tmpList[i]['ad_num'] - tmpList[i]['ad_use_num'] - tmpList[i]['lock_num']); 
					pointStr += "<td width='10%'>"+ num +"</td>";
					switch (tmpList[i]['point_status']) {
                        case '1':
                            $class = 'badge-success';
                            break;
                        case '3':
                            $class = 'badge-danger';
                            break;
                        case '4':
                            $class = 'badge-danger';
                            break;
                	}
					pointStr += "<td width='10%'><span class='badge "+$class+"'>"+tmpList[i]['point_status_txt']+"</span></td>";
					pointStr += "<td width='10%'><button data-id='"+tmpList[i]['id']+"' class='btn btn-xs btn-info do-sel' type='button'>选择点位<i class='icon-arrow-right icon-on-right'></button></td></tr>";
				}
				$('#area').html();
				for(var j = 0; j < data.area_list.length; j++) {
					areaStr += "<option value="+data.area_list[j]['id']+">"+data.area_list[j]['name']+"</option>";
				}
			}else{
				alert('暂无可预约 <?php echo $order_type_text[$order_type];?> 点位');
			}
			$("#points_lists").html(pointStr);
			$("#area_id").html(areaStr);
		});
		layer.close(index);
	});

	//选择点位
    $('#points_lists').on('click', '.do-sel', function(){
    	var id = $(this).data('id');
        //查找是否已经存在
        if($('#selected_points').find('#point_'+id).length>0){layer.msg('请勿重复选择');return;}
        $(this).parent().parent().appendTo($("#selected_points"));
        $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

       	var numObj = $(this).parent().parent().find('td:eq(3)');
        var inputVal = numObj.children().val();
        numObj.text(inputVal);
        //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="'+inputVal+'">');
        
        $("#selected_points button").html('移除点位<i class="fa fa-remove" aria-hidden="true"></i>');
        var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');


        
        $("input[name='point_ids']").val(point_ids);
    });

  	//移除点位
    $('#selected_points').on('click', '.do-sel', function(){
    	
        $(this).parent().parent().appendTo($("#points_lists"));
        $("#selected_points_num").html(Number($("#selected_points_num").html()) - 1);

        
        //$('input[name="make_num['+$(this).parent().parent().attr('point-id')+']"]').remove();
       

        $("#points_lists button").html('选择点位<i class="icon-arrow-right icon-on-right"></i>');

        var point_ids = [];
        var _self = $(this);
        $("#selected_points tr").each(function(){
            point_ids.push($(this).attr('point-id'));
        });
        var ids = point_ids.length >= 1 ? point_ids.join(',') : '';
        $("input[name='point_ids']").val(ids);
    });

  	//选择全部
    $(".select-all").click(function(){
        $("#points_lists tr").each(function(){
            $(this).appendTo($("#selected_points"));
            $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

            var numObj = $(this).find('td:eq(3)');
            var inputVal = numObj.children().val();
            numObj.text(inputVal);
            //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).attr('point-id')+']" value="'+inputVal+'">');

            $("#selected_points button").html('移除点位<i class="fa fa-remove" aria-hidden="true"></i>');
            var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).attr('point-id') :  $(this).attr('point-id');
            $("input[name='point_ids']").val(point_ids);
        });
    });

  	//移除全部
    $(".remove-all").click(function(){
        $("#selected_points tr").each(function(){
            $(this).appendTo($("#points_lists"));
            $("#selected_points_num").html('0');

            //$('input[name="make_num['+$(this).attr('point-id')+']"]').remove();

            $("input[name='point_ids']").val('');
            $("#points_lists button").html('选择点位<i class="icon-arrow-right icon-on-right"></i>');
        });
    });

  	//保存
    $(".btn-save").click(function(){
        var point_ids = $("input[name='point_ids']").val();
        var lock_customer_id = $('#lock_customer_id').val();
        var sales_id = $('#sales_id').val();
        if (lock_customer_id == '') {
            alert('请选择客户！');
            return false;
        }
        if (sales_id == '') {
            alert('请选择业务员！');
            return false;
        }
        if (point_ids == '') {
            alert('您还没有选择点位哦！');
            return false;
        }
    });

    function alert(msg){
    	var d = dialog({
            title: '提示信息',
            content: msg,
            okValue: '确定',
            ok: function () {

            }
        });
        d.width(320);
        d.showModal();
    }
})

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

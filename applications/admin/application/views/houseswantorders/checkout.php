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
      height:390px;
      overflow:auto;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        padding: 4px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
        text-align: center;
    }
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
                        <a href="/houseswantorders">意向订单管理</a>
                    </li>
                    <li class="active">生成预定订单</li>
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
                        意向订单转预定订单
                        <a href="/housesorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-7">
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
                                            	<input id="lock_customer_id" name="lock_customer_id" type="hidden" value="<?php echo $info['customer_id'];?>">
                                                <select  class="select2" required disabled="disabled">
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['customer_id']) && $val['id'] == $info['customer_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
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
                                            	<input type="hidden" id="sales_id" name="sales_id" value="<?php echo $info['create_user'];?>">
                                                <select  class="select2" required disabled="disabled">
                                                    <option value="">请选择业务员</option>
                                                    <?php var_dump($admins); foreach($admins as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['create_user']) && $val['id'] == $info['create_user']){ echo "selected"; }?>><?php echo $val['fullname'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 锁定开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input class="form-control" id="lock_start_time" type="text" name="lock_start_time" value="<?php if(isset($info['lock_start_time'])){ echo $info['lock_start_time'];} else { echo date('Y-m-d'); }?>" readonly>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 锁定结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="lock_end_time" value="<?php if(isset($info['lock_end_time'])){ echo $info['lock_end_time'];} else { echo date("Y-m-d",strtotime("+7 day"));} ?>" readonly>
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
                                            <div class="col-sm-10">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>选择点位（意向点位数量：<?php echo $info['points_count'];?>个）</h4>
                                                        <button type="button" style="margin-top:-3px;" class="btn btn-info btn-xs" onclick="machine_sel();">机选</button>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:820px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                            	<div class="row">
                                                            		<div class="col-sm-12">
	                                                                    <label class="col-sm-2 control-label" for="form-field-1"> 行政区域： </label>
	                                                                    <div class="col-sm-10" style="padding:0">
	                                                                        <div id="distpicker1">
																			  	<select name="province" id="province"></select>
																			  	<select name="city" id="city"></select>
																			  	<select id="area" multiple="multiple"></select>
																				<input id="hid-area" name="area" type="hidden" value="">
																			</div>
	                                                                    </div>
	                                                                </div>
                                                            	</div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘类型： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                    	<input type="hidden" id="houses_type" name="houses_type">
	                                                                    	<?php foreach(C('public.houses_type') as $k => $v) {?>
	                                                                    		<label style="margin-top:10px;margin-right:10px;"><input class="m-checkbox" name="s_houses_type" type="checkbox" value="<?php echo $k;?>" <?php if(in_array($k, explode(',', $info['houses_type']))) {?>checked="checked"<?php }?>><?php echo $v;?></label>
	                                                                    	<?php }?>
										                                	
	                                                                    </div>
	                                                                </div>
	                                                                
	                                                                <div class="col-sm-6">
	                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 交房年份： </label>
	                                                                    <div class="col-sm-8" style="padding:0">
	                                                                        <select id="begin_year" name="begin_year" ></select>
	                                                                       	至
	                                                                        <select id="end_year" name="end_year" ></select>
	                                                                    </div>
	                                                                </div>
                                                                </div>
                                                                
                                                                <div class="row" style="margin-top:10px;">
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 投放行业： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<select id="put_trade" name="put_trade">
										                                		<option value="0">无</option>
										                                		<?php foreach (C('housespoint.put_trade') as $k => $v):?>
										                                			<option value="<?php echo $k;?>" <?php if($k == $info['put_trade']){?>selected="selected"<?php }?>><?php echo $v;?></option>
										                                   		<?php endforeach;?>
										                                	</select>
										                                </div>
										                            </div>
										                            
										                            <div class="col-sm-6">
										                                <label class="col-sm-4 control-label" for="form-field-2"> 点位类型： </label>
										                                <div class="col-sm-8" style="padding:0">
										                                	<?php foreach (C('order.houses_order_type') as $k => $v):?>
										                                    <label class="blue" style="margin-top:5px;">
										                                        <input name="order_type" value="<?php echo $k;?>" required type="radio" class="ace m-radio" <?php if($k == $info['order_type']){echo 'checked="checked"';}?> />
										                                        <span class="lbl"> <?php echo $v;?> </span>
										                                    </label>
										                                    &nbsp;
										                                   <?php endforeach;?>
										                                </div>
										                            </div>
	                                                             </div>
                                                                
                                                            </div>
                                                            
                                                            <hr>
                                                            
                                                            
                                                            <div class="form-group">
                                                                <div class="col-sm-6">
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘名称： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select  id="houses_id" class="select2 ">
                                                                        	<option value="">请选择楼盘</option>
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
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 单元： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="unit" id="unit" class="select2">
                                                                            <option value="">请选择单元</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <br/>
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼层： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="floor" id="floor" class="select2">
                                                                            <option value="">请选择楼层</option>
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
                                                            </div>
                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="10%">编号</th>
                                                                                <th width="10%">楼盘</th>
                                                                                <th width="10%">组团</th>
                                                                                <th width="10%">楼栋</th>
                                                                                <th width="10%">单元</th>
                                                                                <th width="10%">楼层</th>
                                                                                <th width="10%">位置</th>
<!--                                                                                 <th width="10%">规格</th> -->
                                                                                <th width="10%">楼盘等级</th>
                                                                                <th width="10%">组团等级</th>
                                                                                <th width="10%">可投放数量</th>
                                                                                <th width="10%" nowrap="nowrap">状态</th>
                                                                                <th width="10%"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                <div class="div-tbody">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <tbody id="points_lists">
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
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="remarks" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <!-- <input type="hidden" name="point_ids_old" value="<?php echo $info['point_ids'];?>" /> -->
                                                <?php endif;?>
                                                <!-- <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />-->
                                                <!-- <input type="hidden" name="put_trade" value="<?php echo $put_trade;?>" /> -->
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

                    <div class="col-xs-5">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>已选择点位<span id="grade-percent">（0/0/0/0）</span></h4>
                                <span class="widget-toolbar">
                                    已选择<span id="selected_points_num"><?php if(isset($selected_points)) { echo count($selected_points);} else { echo 0; }?></span>个点位
                                </span>
                            </div>

                            <div class="widget-body" style="height:1538px">
                                <div class="widget-main">
                                    <div id="scrollTable">
                                        <div class="div-thead">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">编号</th>
                                                        <th width="10%">楼盘</th>
                                                        <th width="10%">组团</th>
                                                        <th width="10%">楼栋</th>
                                                        <th width="10%">单元</th>
                                                        <th width="10%">楼层</th>
                                                        <th width="10%">位置</th>
                                                        <!-- <th width="10%">规格</th>-->
                                                        <th width="10%">楼盘等级</th>
                                                        <th width="10%">组团等级</th>
                                                        <th width="10%">可投放数量</th>
                                                        <th width="10%">状态</th>
                                                        <th width="10%"><button class="btn btn-xs btn-info remove-all" type="button">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="div-tbody" style="height: 1466px">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <tbody id="selected_points">
                                                    <?php if(isset($selected_points)):?>
                                                        <?php foreach($selected_points as $value):?>
                                                        <tr point-id="<?php echo $value['id'];?>">
                                                            <td width="10%"><?php echo $value['code'];?></td>
                                                            <td width="10%"><?php echo $value['houses_name'];?></td>
                                                            <td width="10%"><?php echo $value['houses_area_name'];?></td>
                                                            <td width="10%"><?php echo $value['ban'];?></td>
                                                            <td width="10%"><?php echo $value['unit'];?></td>
                                                            <td width="10%"><?php echo $value['floor'];?></td>
                                                            <?php if($value['addr'] == 1):?>
                                                            <td width="10%">门禁</td>
                                                            <?php else:?>
                                                            <td width="10%">电梯前室</td>
                                                            <?php endif;?>
                                                            <!-- <td width="10%"><?php echo $value['size'];?></td> -->
                                                            <td width="10%"><?php echo $value['grade'];?></td>
                                                            <td width="10%"><?php echo $value['grade'];?></td>
                                                            <td width="10%"><?php echo $value['ad_num'];?></td>
                                                            <td width="10%">
                                                            	<?php 
                                                                    switch ($value['point_status']) {
                                                                        case '1':
                                                                            $class = 'badge-success';
                                                                            break;
                                                                        case '3':
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

<style>
.text-right {
	text-align:right;
}

.margintop10 {
	margin-top:10px;
}
                                                                
</style>

<div id="machine-sel-panel" style="display:none;">
	<?php foreach (C('public.houses_grade') as $k => $v) {?>
	<div class="row margintop10">
		<div class="col-xs-4 text-right" ><label><?php echo $v;?></label></div>
		<div class="col-xs-6"><input class="grade-input" type="text" id="grade-<?php echo $k;?>" m-id="<?php echo $k;?>" value="0"></div>
	</div>
	<?php }?>
	
	<div class="row margintop10">
		<center><button onclick="machine_sub();" class="btn btn-sm btn-info">确定</button></center>
	</div>

</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script> -->
<script src="<?php echo css_js_url('bootstrap-multiselect.js','admin');?>"></script>
<link href="<?php echo css_js_url('bootstrap-multiselect.css', 'admin');?>" rel="stylesheet" />
<script type="text/javascript">

//机选
function machine_sel() {
	
	layer.open({
		  type: 1,
		  title: '机选(填写楼盘等级对应的点位数量)',
		  area: ['420px', '300px'], //宽高
		  content: $('#machine-sel-panel').html()
		});
}

//机选确认
function machine_sub() {
	var m_arr = new Array();
	var flag = false;
	$('.layui-layer .grade-input').each(function(){
		m_arr.push($(this).val());
	});

	if($('#houses_id').val() != '') {
		flag = true;
	}
	
	for(var i = 0; i < m_arr.length; i++) {
		for(var j = 0; j < m_arr[i]; j++) {
			$('#points_lists tr').each(function(){
				if(flag == true) {
					if($(this).attr('grade') == (i + 1) || $(this).attr('area_grade') == (i + 1)) {
						$(this).find('button').click();
						return false;
					}
				}else {	//这里根据具体业务情况可能有变更
					if($(this).attr('grade') == (i + 1) || $(this).attr('area_grade') == (i + 1)) {
						$(this).find('button').click();
						return false;
					}
				}
			});
		}
	}
	layer.closeAll('page');
}

//计算已选点位的等级比率
function count_percent() {
	
	var count_arr = new Array();
	
	<?php foreach (C('public.houses_grade') as $k => $v) {?>
		count_arr['<?php echo $k-1;?>'] = 0;
		$('#selected_points tr').each(function(){
			if($(this).attr('grade') == '<?php echo $k;?>' || $(this).attr('area_grade') == '<?php echo $k;?>') {
				count_arr['<?php echo $k-1;?>'] = count_arr['<?php echo $k-1;?>']+1;
			}
		});
	<?php }?>

	count_arr.toString();
	
	$('#grade-percent').text('（'+count_arr.toString().replaceAll(',', '/')+'）');
	
}

String.prototype.replaceAll = function (FindText, RepText) {  
    let regExp = new RegExp(FindText,'g');  
    return this.replace(regExp, RepText);  
};  

                                                                
window.onload=function(){ 
	//设置年份的选择 
	var myDate= new Date(); 
	var year=myDate.getFullYear(); 
	var startYear=myDate.getFullYear()-50;//起始年份 
	var endYear=myDate.getFullYear()+50;//结束年份 
	var obj1=document.getElementById('begin_year');
	var obj2=document.getElementById('end_year') 
	for (var i=startYear;i<=endYear;i++) { 
		obj1.options.add(new Option(i,i)); 
		obj2.options.add(new Option(i,i)); 
	} 

	$('#begin_year option').each(function(){
		if($(this).text() == '<?php echo $info["begin_year"];?>') {
			$(this).attr('selected', 'selected');
		}
	});

	$('#end_year option').each(function(){
		if($(this).text() == '<?php echo $info["end_year"];?>') {
			$(this).attr('selected', 'selected');
		}
	});
} 
                                                                
$("#distpicker1").distpicker({
	province: '<?php echo $info['province'];?>',
	city: '<?php echo $info['city'];?>',
	district: ''
});

function get_checkbox(){
    obj = document.getElementsByName("s_houses_type");
    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
	$('#houses_type').val(check_val.toString());
    return check_val.toString();
    
}

//num1用于判断是否需要重新加载楼盘信息，num2判断是否是页面初始化时
function load_houses(num1, num2) {
	var index = layer.load(1);

	var area_str = '';
	if(num2 == 1) {
		area_str = '<?php echo $info['area'];?>';
	}else {
		$('.multiselect-container .active input[type="checkbox"]:checked').each(function(){
			area_str += $(this).val()+',';
		});
		$('#hid-area').val(area_str);
	}
	
	

	var province = $('#province').val();
	var city = $('#city').val();
	
	var area = area_str;
	var houses_type = get_checkbox();	//获取楼盘类型
	var begin_year = $('#begin_year').val();
	var end_year = $('#end_year').val();
	var put_trade = $('#put_trade').val();
	var order_type = $('.m-radio:checked').val();
	
	var houses_id = $('#houses_id').val();
	var area_id = $('#area_id').val();
	var ban = $('#ban').val();
	var unit = $('#unit').val();
	var floor = $('#floor').val();
	var addr = $('#addr').val();
	
	var postData = {
			province:province,
			city:city, area:area,
			houses_type:houses_type, 
			begin_year:begin_year, 
			end_year:end_year, 
			put_trade:put_trade,
			order_type:order_type,
			houses_id:houses_id,
			area_id:area_id,
			ban:ban,
			unit:unit,
			floor:floor,
			addr:addr
	};
	
	$.post('/houseswantorders/get_points', postData, function(data){
		if(data.flag == true && data.count > 0) {
			//楼盘
			if(num1 != 2) {
				var housesStr = '<option value="">请选择楼盘</option>';
				for(m_key1  in data.houses_lists){  
					housesStr += '<option value="'+m_key1+'">'+data.houses_lists[m_key1]+'</option>';
				}
				$('#houses_id').html(housesStr);
			}

			//组团
			var areaStr = '<option value="">请选择组团</option>';
			for(m_key2  in data.area_lists){  
				areaStr += '<option value="'+m_key2+'">'+data.area_lists[m_key2]+'</option>';
			}
			$('#area_id').html(areaStr);

			//楼栋
			var banStr = '<option value="">请选择楼栋</option>';
			for(m_key3  in data.ban_lists) {
				
				banStr += '<option value="'+data.ban_lists[m_key3]+'">'+data.ban_lists[m_key3]+'</option>';
			}
			
			$('#ban').html(banStr);


			//单元
			var unitStr = '<option value="">请选择单元</option>';
			for(m_key4  in data.unit_lists){  
				unitStr += '<option value="'+data.ban_lists[m_key3]+'">'+data.unit_lists[m_key4]+'</option>';
			}
			$('#unit').html(unitStr);

			//楼层
			var floorStr = '<option value="">请选择楼层</option>';
			for(m_key5  in data.floor_lists){  
				floorStr += '<option value="'+m_key5+'">'+data.floor_lists[m_key5]+'</option>';
			}
			$('#floor').html(floorStr);

			var pointStr = '';
			$("#all_points_num").text(data.count);
			for(var i = 0; i < data.points_lists.length; i++) {
				if(i == 1500) {
					break;
				}
				pointStr += "<tr point-id='"+(data.points_lists)[i]['id']+"' grade='"+(data.points_lists)[i]['grade']+"' area_grade='"+(data.points_lists)[i]['area_grade']+"'><td width='10%'>"+(data.points_lists)[i]['code']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['houses_name']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['houses_area_name']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['ban']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['unit']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['floor']+"</td>";
				if((data.points_lists)[i]['addr'] == 1){
					pointStr += "<td width='10%'>门禁</td>";
				}else if((data.points_lists)[i]['addr'] == 2){
					pointStr += "<td width='10%'>地面电梯前室</td>";
				}else {
					pointStr += "<td width='10%'>地下电梯前室</td>";
				}
				//pointStr += "<td width='10%'>"+(data.points_lists)[i]['size']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['houses_grade']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['area_grade_name']+"</td>";
				pointStr += "<td width='10%'>"+(data.points_lists)[i]['ad_num']+"</td>";
				var $class;
				switch ((data.points_lists)[i]['point_status']) {
                case '1':
                    $class = 'badge-success';
                    break;
                case '3':
                    $class = 'badge-danger';
                    break;
        		}
				pointStr += "<td width='10%'><span class='badge "+$class+"'>"+(data.points_lists)[i]['point_status_txt']+"</span></td>";
				pointStr += "<td width='10%'><button class='btn btn-xs btn-info do-sel' type='button'>选择点位<i class='icon-arrow-right icon-on-right'></button></td></tr>";
			}
		}else{
			layer.alert('暂无空闲点位');
		}

		$("#points_lists").html('');
		$("#points_lists").html(pointStr);

		layer.close(index);
	});

	
}
                                                                
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});

    load_houses(1, 1);

    $('#province, #city, #area, .m-checkbox, #begin_year, #end_year, #put_trade, .m-radio').change(function(){
    	$("#points_lists").html('');
    	load_houses(1, 2);
    });

    $('#houses_id, #area_id, #ban, #unit, #floor, #addr').change(function(){
    	$("#points_lists").html('');
    	load_houses(2, 2);
    });
	
	//选择点位
    $('#points_lists').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#selected_points"));
        $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

       	var numObj = $(this).parent().parent().find('td:eq(3)');
        var inputVal = numObj.children().val();
        numObj.text(inputVal);
        //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="'+inputVal+'">');
        
        $("#selected_points button").html('移除点位<i class="fa fa-remove" aria-hidden="true"></i>');
        var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');


        
        $("input[name='point_ids']").val(point_ids);

        count_percent();
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

        count_percent();
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

        count_percent();
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

        count_percent();
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

<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#area').multiselect({
        	includeSelectAllOption: true,
        	selectAllText : '选择所有',
        	nonSelectedText : '请选择区域',
        	allSelectedText : '选择所有'
        });

        $('#area option:eq(0)').remove();
        $('#area option:eq(0)').attr("selected",false);

        $('.multiselect-container li:eq(1)').remove();
        $('.multiselect-container li:eq(1)').removeClass('active');
        $('.multiselect-container li:eq(1)').find('input[type="checkbox"]').prop("checked",false);

        $('.multiselect').attr('title','请选择区域');
        $('.multiselect-selected-text').text('请选择区域');

        var sceneIdList = "<?php echo $info['area'];?>";
        sceneIdArr = sceneIdList.split(",");

        $('.multiselect-container li input[type="checkbox"]').each(function(i,content){
           if($.inArray($.trim(content.value),sceneIdArr)>=0){
               $(this).prop('checked', true);
               $(this).parent().parent().parent().attr('class', 'active');
               $('multiselect').attr('title', sceneIdList);
               $('.multiselect-selected-text').text(sceneIdList);
           }
       	});

		//设置选中值后，需要刷新select控件
		$("#sceneIdSelectBox").multiselect('refresh');
         
    });

</script>
        
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

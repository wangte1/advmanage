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
                        <a href="/changepicorders">订单管理</a>
                    </li>
                    <li class="active">新建换画订单</li>
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
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?><?php echo $order_type_text[$order_type];?>换画订单
                        <a href="/changepicorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
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
                                    	<input type="hidden" name="customer_id" id="customer_id" value="<?php if(isset($info['customer_id']))echo $info['customer_id']; ?>">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 订单编号： </label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <?php if(isset($info['order_code'])):?>
                                                    <input type="text" name="order_code" class="form-control" value="<?php echo $info['order_code']; ?>" placeholder="请输入订单编号" readonly>
                                                    <?php else:?>
                                                    <input type="text" name="order_code" class="form-control" placeholder="请输入订单编号" value="<?php if(isset($order_code)){ echo $order_code; } ?>" required>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-purple btn-sm search-point">
                                                            查询
                                                            <i class="icon-search icon-on-right bigger-110"></i>
                                                        </button>
                                                    </span>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="order_info" <?php if(!isset($info)): ?>style="display: none"<?php endif;?>>
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"></label>
                                            <div class="col-sm-10" style="padding:0">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单类型 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_type"><?php echo $order_type_text[$order['order_type']];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 投放点位 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="points"><?php echo count(explode(',', $order['point_ids'])).'个点位';?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 总价 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="price"><?php echo $order['total_price'].'元';?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 客户 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="customer_name"><?php echo $order['customer_name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_name"><?php echo $order['sales_name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员手机号 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_mobile"><?php echo $order['sales_mobile'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 投放时间 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="release_time"><?php echo $order['release_start_time'].'至'.$order['release_end_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单日期 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $order['create_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 换画点位： </label>
                                            <div class="col-sm-10">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>选择点位</h4>
                                                        <span class="widget-toolbar">
                                                            此订单下共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">

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
                                                                                <th width="10%">位置</th>
                                                                                <th width="10%">规格</th>
                                                                                <th width="10%"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                                <div class="div-tbody">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <tbody id="points_lists">
                                                                            <!--<?php if(isset($points_lists)):?>
                                                                                <?php foreach($points_lists as $value):?>
                                                                                <tr point-id="<?php echo $value['id'];?>">
                                                                                    <td class="col-sm-2"><?php echo $value['points_code'];?></td>
                                                                                    <td class="col-sm-3"><?php echo $value['media_name'].'('.$value['media_code'].')';?></td>
                                                                                    <td class="col-sm-3"><?php echo $value['size'];?></td>
                                                                                    <?php if($order_type == 1):?>
                                                                                    <td class="col-sm-2"><input type="text" style="width:91px" name="make_num[<?php echo $value['id'];?>]" value="<?php echo $value['make_num'];?>" /></td>
                                                                                    <?php endif;?>
                                                                                    <td class="col-sm-2"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">选择<i class="icon-arrow-right icon-on-right"></i></button></td>
                                                                                </tr>
                                                                                <?php endforeach;?>
                                                                            <?php endif;?>-->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告性质： </label>
                                            <div class="col-sm-10">
                                                <select name="adv_nature" class="select2" >
                                                    <option value="">请选择广告性质</option>
                                                    <?php foreach (C('order.adv_nature') as $value) : ?>
                                                    <option value="<?php echo $value;?>" <?php if(isset($info['adv_nature']) && $value == $info['adv_nature']) { echo "selected"; }?>><?php echo $value;?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>

                                        <?php if($order_type == 3 || $order_type == 4):?>
                                        <div class="form-group page-frequency">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告频次： </label>
                                            <div class="col-sm-10">
                                                <select name="adv_frequency" class="select2">
                                                    <option value="">请选择广告频次</option>
                                                    <?php foreach (C('order.adv_frequency') as $value) : ?>
                                                    <option value="<?php echo $value;?>" <?php if(isset($info['adv_frequency']) && $value == $info['adv_frequency']) { echo "selected"; }?>><?php echo $value;?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endif;?>  -->                                      


                                        <!--<?php if($order_type == 1 || $order_type == 2):?>
                                        <div class="form-group page-make-company">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作公司： </label>
                                            <div class="col-sm-10">
                                                <select name="make_company_id" class="select2" required>
                                                    <option value="">请选择制作公司</option>
                                                    <?php foreach($make_company as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['make_company_id']) && $val['id'] == $info['make_company_id']) { echo "selected"; }?>><?php echo $val['company_name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                   <?php
                                                        $html = '';
                                                        foreach ($make_company as $value) {
                                                            $html .= '<b>'.$value['company_name'].'</b>:';
                                                            $html .= $value['business_scope'].'<br/>';
                                                        }
                                                   ?>
                                                   <a href="javascript:;" data-rel="popover" title="如何选择制作公司？" data-trigger="hover" data-content="<?php echo $html; ?>"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endif;?>    

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作要求： </label>
                                            <div class="col-sm-8">
                                                <?php 
                                                    if(isset($info['make_requirement'])) { 
                                                        $make_requirement = $info['make_requirement'];
                                                    } else { 
                                                        switch ($order_type) {
                                                            case '2':
                                                                $make_requirement = "喷绘";
                                                                break;
                                                            case '3':
                                                                $make_requirement = "LED画面";
                                                                break;
                                                            case '4':
                                                                $make_requirement = "LED画面";
                                                                break;
                                                            default:
                                                                $make_requirement = "公交站台户外写真";
                                                                break;
                                                        }
                                                    }
                                                ?>
                                                <textarea class="form-control" name="make_requirement" rows="5" placeholder="请填写制作要求"><?php echo $make_requirement; ?></textarea>
                                            </div>
                                        </div>


                                        <div class="form-group page-make-fee">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作费用： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="make_fee" rows="5" placeholder="请填写制作费用要求"><?php if(isset($info['make_fee'])) { echo $info['make_fee'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group page-make-complete-time">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 制作完成时间： </label>
                                            <div class="col-sm-8">
                                                <div class="col-sm-6 input-group date datepicker" style="padding-left: 0">
                                                    <input class="form-control date-picker" type="text" name="make_complete_time" value="<?php if(isset($info['make_complete_time'])){ echo date('Y-m-d', strtotime($info['make_complete_time']));} else { echo date('Y-m-d',strtotime('+1 day')); }?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                                <div class="col-sm-6 input-group bootstrap-timepicker">
                                                    <input id="timepicker1" type="text" class="form-control" value="<?php if(isset($info['make_complete_time'])){ echo date('H:i:s', strtotime($info['make_complete_time']));}?>" />
                                                    <span class="input-group-addon">
                                                        <i class="icon-time bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 是否打小样： </label>
                                            <div class="col-sm-10">
                                                <label class="blue">
                                                    <input name="is_sample" value="1" type="radio" class="ace" <?php if((isset($info['is_sample']) && $info['is_sample'] == 1) || !isset($info['is_sample'])){ echo "checked"; }?> />
                                                    <span class="lbl"> 是</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input name="is_sample" value="0" type="radio" class="ace" <?php if(isset($info['is_sample']) && $info['is_sample'] == 0){ echo "checked"; }?>>
                                                    <span class="lbl"> 否</span>
                                                </label>
                                                &nbsp;
                                                <input type="text" name="sample_color" placeholder="请填写小样颜色" value="<?php if(isset($info['sample_color'])) { echo $info['sample_color']; }?>" <?php if(isset($info['is_sample']) && $info['is_sample'] == 0):?> style="display: none" <?php endif;?> />
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                            </div>
                                        </div>-->


                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <?php endif;?>

                                                <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
                                                <input type="hidden" name="point_ids" value="<?php if(isset($info['point_ids'])) { echo $info['point_ids']; } ?>" />
                                                
                                                <?php if(isset($info['id']) && ($order_type == 1 || $order_type == 2)):?>
                                                    <?php foreach ($points_make_num as $key => $value): ?>
                                                    <input type="hidden" name="make_num[<?php echo $value['point_id'];?>]" value="<?php echo $value['make_num'];?>" />
                                                    <?php endforeach;?>
                                                <?php endif;?>

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
                                <h4>已选择点位</h4>
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
                                                        <th width="10%">点位编号</th>
                                                        <th width="10%">楼盘</th>
                                                        <th width="10%">组团</th>
                                                        <th width="10%">楼栋</th>
                                                        <th width="10%">单元</th>
                                                        <th width="10%">楼层</th>
                                                        <th width="10%">位置</th>
                                                        <th width="10%">规格</th>
                                                        <th width="10%"><button class="btn btn-xs btn-info remove-all" type="button" data-id="3">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="div-tbody" style="height: 1466px">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <tbody id="selected_points">
                                                    <!--<?php if(isset($selected_points)):?>
                                                        <?php foreach($selected_points as $value):?>
                                                        <tr point-id="<?php echo $value['id'];?>">
                                                            <td class="col-sm-2"><?php echo $value['points_code'];?></td>
                                                            <td class="col-sm-3"><?php echo $value['media_name'].'('.$value['media_code'].')';?></td>
                                                            <td class="col-sm-3"><?php echo $value['size'];?></td>
                                                            <?php if($order_type == 1):?>
                                                            <td class="col-sm-2"><?php echo $value['make_num'];?></td>
                                                            <?php endif;?>
                                                            <td class="col-sm-2"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除<i class="fa fa-remove" aria-hidden="true"></i></button></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    <?php endif;?>-->
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
<!-- <script src="<?php echo css_js_url('changepicorder.js','admin');?>"></script>-->
<script type="text/javascript">
    var order_type = "<?php echo $order_type;?>";
    $('[data-rel=popover]').popover({html:true});


    $(function(){
    	$(".select2").css('width','220px').select2({allowClear:true});

        $('#timepicker1').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            defaultTime: '18:00:00'
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });

        //根据订单编号查询订单信息点位列表
        $('.search-point').click(function(){
            if ($("input[name='order_code']").val()) {
                $.post('/houseschangepicorders/get_points', {order_code: $("input[name='order_code']").val(), order_type: $("input[name='order_type']").val()}, function(data){

                    if (data.flag && data.points_lists.length > 0) {
                        //订单信息显示
                        $("#order_info").css('display', 'block');
                        $("#order_type").html(data.order_info['order_type']);
                        $("#points").html(data.count+"个点位");
                        $("#price").html(data.order_info['total_price']+'元');
                        $("#customer_name").html(data.order_info['customer_name']);
                        $("#sales_name").html(data.order_info['sales_name']);
                        $("#sales_mobile").html(data.order_info['sales_mobile']);
                        $("#release_time").html(data.order_info['release_start_time']+'至'+data.order_info['release_end_time']);
                        $("#order_time").html(data.order_info['create_time']);
                        $("#customer_id").val(data.order_info['customer_id']);

                        //点位列表显示
                        $("#points_lists").empty();
                        var lists = data.points_lists;
                        var html = '';
                        for (var i = 0; i < lists.length; i++) {
                            html += '<tr point-id="'+lists[i]['id']+'">';
                            html += '<td width="10%">' + lists[i]['code'] + '</td>';
                            html += '<td width="10%">' + lists[i]['houses_name'] + '</td>';
                            html += '<td width="10%">' + lists[i]['houses_area_name'] + '</td>';
                            html += '<td width="10%">' + lists[i]['ban'] + '</td>';
                            html += '<td width="10%">' + lists[i]['unit'] + '</td>';
                            html += '<td width="10%">' + lists[i]['floor'] + '</td>';
                            html += '<td width="10%">' + lists[i]['addr'] + '</td>';
                            html += '<td width="10%">' + lists[i]['size'] + '</td>';
                            
                            var point_id = lists[i]['id'];
                            var point_ids_arr = $('input[name="point_ids"]').val().split(',');
                            if ($.inArray(point_id, point_ids_arr) != -1) {
                                html += '<td width="10%"><button class="btn btn-xs btn-default do-sel" type="button" data-id="'+lists[i]['id']+'" disabled>已选择</button></td>';
                            } else {
                                html += '<td width="10%"><button class="btn btn-xs btn-info do-sel" type="button" data-id="'+lists[i]['id']+'">选择点位<i class="icon-arrow-right icon-on-right"></i></button></td>';
                            }
                            
                        }
                        $("#all_points_num").html(data.count);
                        $("#points_lists").append(html);
                    } 
                });
            }
        });

      //选择点位
        $('#points_lists').on('click', '.do-sel', function(){
            $(this).parent().parent().appendTo($("#selected_points"));
            $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

            if (order_type == 1) {
                var numObj = $(this).parent().parent().find('td:eq(3)');
                var inputVal = numObj.children().val();
                numObj.text(inputVal);
                //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="'+inputVal+'">');
            } else if (order_type == 2) { //高杆一个点位只对应一张
                //$("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="1">');
            }
           
            $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
            var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');
            $("input[name='point_ids']").val(point_ids);
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

                $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
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
                $("#points_lists button").html('选择<i class="icon-arrow-right icon-on-right"></i>');
            });
        });

        //是否打小样
        $("input[name='is_sample']").change(function(){
            if($(this).val() == 0) {
                $("input[name='sample_color']").val("");
                $("input[name='sample_color']").hide(); 
                // $("input[name='sample_color']").removeAttr('required');
            } else{
                // $("input[name='sample_color']").attr('required', true);
                $("input[name='sample_color']").show();
            }
        });

        //保存
        $(".btn-save").click(function(){
            var point_ids = $("input[name='point_ids']").val();
            if (point_ids == '') {
                var d = dialog({
                    title: '提示信息',
                    content: '您还没有选择点位哦！',
                    okValue: '确定',
                    ok: function () {

                    }
                });
                d.width(320);
                d.showModal();
                return false;
            }
        });
    });
    

    
</script>

<?php if(isset($order_code)):?>
<script type="text/javascript">
    $(function(){
        var order_code = "<?php echo $order_code;?>";
        if (order_code > 0) {
            $('.search-point').trigger("click");
        }
    });
</script>
<?php endif;?>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

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
                        <a href="/housesorders">预定订单管理</a>
                    </li>
                    <li class="active">预定订单转订单</li>
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
                        生成<?php echo $order_type_text[$info['order_type']];?>订单
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

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告客户： </label>
                                            <div class="col-sm-10">
                                                <select id="customer_id" name="customer_id" class="select2" required>
                                                    <option value="">请选择客户</option>
                                                    <?php foreach($customers as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['lock_customer_id']) && $val['id'] == $info['lock_customer_id']){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <!-- <select name="project_id" class="select2">
                                                    <option value="">请选择项目</option>
                                                    <?php if(isset($project)):?>
                                                        <?php foreach($project as $value):?>
                                                        <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $info['project_id']){ echo "selected"; }?>><?php echo $value['project_name'];?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select> -->
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" type="text" name="release_start_time" value="<?php if(isset($info['release_start_time'])){ echo $info['release_start_time'];}?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" type="text" name="release_end_time" value="<?php if(isset($info['release_end_time'])){ echo $info['release_end_time'];}?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 合同价格： </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="total_price" value="<?php if(isset($info['total_price'])){ echo $info['total_price'];} else { echo "0.00"; } ?>" />
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle">元 填写合同价格后才能对订单的销售额进行统计、趋势分析</span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display:none;">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放点位： </label>
                                            <div class="col-sm-10">
                                                <div class="widget-box" >
                                                    <div class="widget-header">
                                                        <h4>选择点位</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:580px">
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
                                                                    <label class="col-sm-4 control-label" for="form-field-1"> 楼盘区域： </label>
                                                                    <div class="col-sm-8" style="padding:0">
                                                                        <select name="area_id" id="area_id" class="select2">
                                                                            <option value="">请选择楼盘区域</option>
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
                                                                                <th width="10%">位置</th>
                                                                                <th width="10%">规格</th>
                                                                                <th width="10%">状态</th>
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
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 业务员<?php echo $info['sales_id']?>： </label>
                                            <div class="col-sm-9">
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

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告性质： </label>
                                            <div class="col-sm-9">
                                                <select name="adv_nature" class="select2" required>
                                                    <option value="">请选择广告性质</option>
                                                    <?php foreach (C('order.adv_nature') as $value) : ?>
                                                    <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                </span>
                                            </div>
                                        </div>

                                        <?php if($order_type == 1 || $order_type == 2):?>
                                        <div class="form-group page-make-company">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作公司： </label>
                                            <div class="col-sm-9">
                                                <select name="make_company_id" class="select2" required>
                                                    <option value="">请选择制作公司</option>
                                                    <?php foreach($make_company as $val):?>
                                                    <option value="<?php echo $val['id'];?>" ><?php echo $val['company_name'];?></option>
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
                                                <!-- <textarea class="form-control" name="make_requirement" rows="5" placeholder="请填写制作要求"><?php echo $make_requirement;?></textarea> -->
                                            	<textarea class="form-control" name="make_requirement" rows="5" placeholder="请填写制作要求"></textarea>
                                            </div>
                                        </div>

                                        <?php if($order_type == 1 || $order_type == 2):?>
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
                                            <div class="col-sm-9">
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

                                        <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 委托内容： </label>
                                            <div class="col-sm-9">
                                                <label class="blue">
                                                    <input name="leave_content" value="1" type="radio" class="ace" <?php if((isset($info['leave_content']) && $info['leave_content'] == 1) || !isset($info['leave_content'])){ echo "checked"; }?> />
                                                    <span class="lbl"> 仅制作</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input name="leave_content" value="2" type="radio" class="ace" <?php if((isset($info['leave_content']) && $info['leave_content'] == 2) || (!isset($info['leave_content']) && $order_type == 2)){ echo "checked"; }?>>
                                                    <span class="lbl"> 制作及安装</span>
                                                </label>
                                            </div>
                                        </div>

                                        <?php if($order_type == 2):?>
                                        <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 安装类型： </label>
                                            <div class="col-sm-9">
                                                <label class="blue">
                                                    <input name="install_type" value="1" type="radio" class="ace" <?php if((isset($info['install_type']) && $info['install_type'] == 1) || !isset($info['install_type'])){ echo "checked"; }?> />
                                                    <span class="lbl"> 覆盖</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input name="install_type" value="2" type="radio" class="ace" <?php if((isset($info['install_type']) && $info['install_type'] == 2) || (!isset($info['install_type']) && $order_type == 2)){ echo "checked"; }?>>
                                                    <span class="lbl"> 替换原画</span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php endif;?>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
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
                                                
                                                <!--<?php if(isset($info['id']) && ($order_type == 1 || $order_type == 2)):?>
                                                    <?php foreach ($points_make_num as $key => $value): ?>
                                                    <input type="hidden" name="make_num[<?php echo $value['point_id'];?>]" value="<?php echo $value['make_num'];?>" />
                                                    <?php endforeach;?>
                                                <?php endif;?>-->
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
                                                        <th width="10%">状态</th>
                                                        <!-- <th width="10%"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th> -->
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
                                                            <td width="10%"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                                            <td width="10%"><?php echo $value['size'];?></td>
                                                            <td width="10%">
                                                            	<?php if($value['point_status'] == 1) {?>
                                                            	<span class="badge badge-success">空闲</span>
                                                            	<?php }else if($value['point_status'] == 3) {?>
                                                            	<span class="badge badge-danger">占用</span>
                                                            	<?php }?>
                                                            </td>
                                                            <!-- <td width="10%"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除点位<i class="fa fa-remove" aria-hidden="true"></i></button></td> -->
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
$(function(){
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
	
	$('#houses_id,#is_lock,#area_id,#ban,#unit,#floor,#addr').change(function(){
		if($(this).attr('id') == 'houses_id') {
			$("#area_id").html('');
			$(".select2-chosen:eq(2)").text('全部');
		}

		if($(this).attr('id') == 'is_lock' && $('#is_lock').val() == 1) {
			if($('#customer_id').val() == '') {
				layer.alert("请先选择客户");
				$("#is_lock").val(0);
				return;
			}
			
		}

		var houses_id = $('#houses_id').val();
		var ban = $('#ban').val();
		var unit = $('#unit').val();
		var floor = $('#floor').val();
		var addr = $('#addr').val();
		var is_lock = $('#is_lock').val();
		var customer_id = $('#customer_id').val();
		var postData = {order_type:order_type, put_trade:put_trade, houses_id:houses_id, is_lock:is_lock, customer_id:customer_id,ban:ban,unit:unit,floor:floor,addr:addr};
		$.post('/housesorders/get_points', postData, function(data){
			var pointStr =  '';
			var areaStr = ''; 
			if(data.flag == true) {
				$("#all_points_num").text(data.count);
				for(var i = 0; i < (data.points_lists).length; i++) {
					pointStr += "<tr point-id='"+(data.points_lists)[i]['id']+"'><td width='10%'>"+(data.points_lists)[i]['code']+"</td>";
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['houses_name']+"</td>";
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['area_name']+"</td>";
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['ban']+"</td>";
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['unit']+"</td>";
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['floor']+"</td>";
					if((data.points_lists)[i]['addr'] == 1){
						pointStr += "<td width='10%'>门禁</td>";
					}else{
						pointStr += "<td width='10%'>电梯前室</td>";
					}
					pointStr += "<td width='10%'>"+(data.points_lists)[i]['size']+"</td>";
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

				for(var j = 0; j < (data.area_lists).length; j++) {
					areaStr += "<option value="+(data.area_lists)[j]['id']+">"+(data.area_lists)[j]['name']+"</option>";
				}
			}

			$("#points_lists").html(pointStr);
			$("#area_id").html(areaStr);
		});
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

    
})
	

                                                
                                                
   // var order_type = "<?php echo $order_type;?>";
    //$('.popover-lock').popover({html:true, placement:'bottom'});
    //$('[data-rel=popover]').popover({html:true});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

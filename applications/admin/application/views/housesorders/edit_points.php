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
                        <a href="/orders">订单管理</a>
                    </li>
                    <li class="active">新建订单</li>
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
                        <?php if(isset($info['id'])) { echo "编辑"; } else { echo "新建"; }?><?php echo $order_type_text[$order_type];?>订单
                        <a href="/orders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                    <span class="text-warning bigger-110 orange">
                        <i class="icon-warning-sign"></i>
                        注：投放中的订单只允许修改点位，移除的点位则该订单以及该订单下最近的一次换画订单对应的点位也将移除，移除的这些点位将释放出来，新增加的点位需重新上传该站台验收图片。
                    </span>
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

                                        <div class="form-group" id="order_info">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2">订单信息：</label>
                                            <div class="col-sm-10" style="padding:0">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单类型 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_type"><?php echo $order_type_text[$info['order_type']];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 总价 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="price"><?php echo $info['total_price'].'元';?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 客户 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="customer_name"><?php echo $info['customer_name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_name"><?php echo $info['sales']['name'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 业务员手机号 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="sales_mobile"><?php echo $info['sales']['phone_number'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 投放时间 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="release_time"><?php echo $info['release_start_time'].'至'.$info['release_end_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 广告性质 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['adv_nature'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 制作公司 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['make_company'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 制作完成时间 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click" id="order_time"><?php echo $info['make_complete_time'];?>&nbsp;</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 广告小样 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['is_sample'] == 1) { echo '是('.$info['sample_color'].')'; } else { echo '否'; } ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 制作要求 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['make_requirement']) { echo $info['make_requirement']; } else { echo '无'; } ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 委托内容 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['leave_content']) { echo C('order.leave_content')[$info['leave_content']]; } else { echo '无'; } ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 安装类型 </div>
                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"> <?php if($info['install_type']) { echo C('order.install_type')[$info['install_type']]; } else { echo '无'; } ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单日期 </div>

                                                        <div class="profile-info-value">
                                                            <span class="editable editable-click"><?php echo $info['create_time'];?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> 订单状态</div>

                                                        <div class="profile-info-value">
                                                            <?php 
                                                                switch ($info['order_status']) {
                                                                    case '1':
                                                                        $class = 'badge-yellow';
                                                                        break;
                                                                    case '2':
                                                                        $class = 'badge-pink';
                                                                        break;
                                                                    case '3':
                                                                        $class = 'badge-success';
                                                                        break;
                                                                    case '4':
                                                                        $class = 'badge-warning';
                                                                        break;
                                                                    case '5':
                                                                        $class = 'badge-danger';
                                                                        break;
                                                                    case '6':
                                                                        $class = 'badge-info';
                                                                        break;
                                                                    case '7':
                                                                        $class = 'badge-purple';
                                                                        break;
                                                                    case '8':
                                                                        $class = 'badge-grey';
                                                                        break;
                                                                }
                                                            ?>
                                                            <span class="badge <?php echo $class; ?>">
                                                                <?php echo C('order.order_status.text')[$info['order_status']];?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放点位： </label>
                                            <div class="col-sm-10">
                                                <div class="widget-box">
                                                    <div class="widget-header">
                                                        <h4>选择点位</h4>
                                                        <span class="widget-toolbar">
                                                            共<span id="all_points_num">0</span>个点位
                                                        </span>
                                                    </div>
                                                    <div class="widget-body" style="height:580px">
                                                        <div class="widget-main">
                                                            <div class="form-group">
                                                                <div class="col-sm-7">
                                                                    <label class="col-sm-3 control-label" for="form-field-1"> 媒体： </label>
                                                                    <div class="col-sm-9" style="padding:0">
                                                                        <select name="media_id" id="media_id" class="select2 media-sel">
                                                                            <option value="">请选择媒体</option>
                                                                            <?php foreach($media_list as $val):?>
                                                                            <option value="<?php echo $val['id'];?>" <?php if(isset($info['media_id']) && $val['id'] == $info['media_id']){ echo "selected"; }?>><?php echo $val['name'].'('.$val['code'].')';?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="padding-left:0"> 状态： </label>
                                                                    <div class="col-sm-9" style="padding:0">
                                                                        <select class="input-medium" id="point_status">
                                                                            <option value="1">空闲</option>
                                                                            <option value="2">预定</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="scrollTable">
                                                                <div class="div-thead">
                                                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-sm-2">点位编号</th>
                                                                                <th class="col-sm-3">媒体名称</th>
                                                                                <th class="col-sm-3">规格</th>
                                                                                <?php if($order_type == 1 || $order_type == 2):?>
                                                                                <th class="col-sm-2">
                                                                                    面数
                                                                                    <select name="make_num">
                                                                                        <option value="1">单面</option>
                                                                                        <option value="2" selected>双面</option>
                                                                                        <?php if($order_type == 2):?><option value="3">三面</option><?php endif;?>
                                                                                    </select>
                                                                                </th>
                                                                                <?php endif;?>
                                                                                <th class="col-sm-2"><button class="btn btn-xs btn-info select-all" type="button" data-id="3">选择全部<i class="icon-arrow-right icon-on-right"></i></button></th>
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
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <?php if(isset($info['id'])):?>
                                                <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                                <input type="hidden" name="order_code" value="<?php echo $info['order_code'];?>" />
                                                <input type="hidden" name="customer_id" value="<?php echo $info['customer_id'];?>" />
                                                <input type="hidden" name="point_ids_old" value="<?php echo $info['point_ids'];?>" />
                                                <?php endif;?>

                                                <input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
                                                <input type="hidden" name="point_ids" value="<?php if(isset($info['point_ids'])) { echo $info['point_ids']; } ?>" />
                                                <?php if(isset($info['id'])):?>
                                                    <?php foreach ($points_make_num as $key => $value): ?>
                                                    <input type="hidden" name="make_num[<?php echo $value['point_id'];?>]" value="<?php echo $value['make_num'];?>" />
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                <button class="btn btn-info" type="submit">
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
                                    已选择<span id="selected_points_num"><?php if(isset($selected_points)) { echo count($selected_points);} else { echo 0; }?></span>个点位（总价：<span id="total_price"><?php if(isset($info['total_price'])) { echo $info['total_price']; } else { echo "0.00"; } ?></span>元）
                                </span>
                            </div>

                            <div class="widget-body" style="height:1538px">
                                <div class="widget-main">
                                    <div id="scrollTable">
                                        <div class="div-thead">
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-sm-2">点位编号</th>
                                                        <th class="col-sm-3">媒体名称</th>
                                                        <th class="col-sm-3">规格</th>
                                                        <th class="col-sm-2">面数</th>
                                                        <th class="col-sm-2"><button class="btn btn-xs btn-info remove-all" type="button" data-id="3">移除全部<i class="fa fa-remove" aria-hidden="true"></i></button></th>
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
                                                            <td class="col-sm-2"><?php echo $value['points_code'];?></td>
                                                            <td class="col-sm-3"><?php echo $value['media_name'].'('.$value['media_code'].')';?></td>
                                                            <td class="col-sm-3"><?php echo $value['size'].'('.$value['specification_name'].')';?></td>
                                                            <?php if($order_type == 1 || $order_type == 2):?>
                                                            <td class="col-sm-2"><?php echo $value['make_num'];?></td>
                                                            <?php endif;?>
                                                            <td class="col-sm-2"><button class="btn btn-xs btn-info do-sel" type="button" data-id="<?php echo $value['id'];?>">移除<i class="fa fa-remove" aria-hidden="true"></i></button></td>
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
<script src="<?php echo css_js_url('order.js','admin');?>"></script>
<script type="text/javascript">
    var order_type = "<?php echo $order_type;?>";
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

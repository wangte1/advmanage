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
                        <a href="#">订单管理</a>
                    </li>
                    <li>
                        <a href="/orders">订单列表</a>
                    </li>
                    <li class="active">订单详情</li>
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
                    <h1>订单详情</h1>
                </div>

                <div class="row">
                   <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                        <li class="active">
                                            <a data-toggle="tab" href="#basic">基本信息</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#points">投放点位</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#adv_img">广告画面</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#inspect_img">验收图片</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#change_pic_record">换画记录</a>
                                        </li>
                                        <?php if($info['order_type'] == 1):?>
                                        <li>
                                            <a data-toggle="tab" href="#change_points_record">换点记录</a>
                                        </li>
                                        <?php endif;?>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="basic" class="tab-pane in active">
                                            <div class="profile-user-info profile-user-info-striped">
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单编号 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click" id="order_code"><?php echo $info['order_code'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单总价 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['total_price'];?> 元</span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 客户 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['customer_name'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 业务员 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['salesman']['name'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 业务员手机号 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['salesman']['phone_number'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 投放时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['release_start_time'].'至'.$info['release_end_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 广告性质 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['adv_nature'];?></span>
                                                    </div>
                                                </div>

                                                <?php if($info['order_type'] == 3 || $info['order_type'] == 4): ?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 广告频次 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['adv_frequency']) { echo $info['adv_frequency']; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <?php if($info['order_type'] == 1 || $info['order_type'] == 2): ?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 制作公司 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['make_company']) { echo $info['make_company']; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 制作完成时间 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['make_complete_time']) { echo date('Y年m月d日H时' , strtotime($info['make_complete_time'])); } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

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

                                                <?php if($info['order_type'] == 1 || $info['order_type'] == 2):?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 委托内容 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['leave_content']) { echo C('order.leave_content')[$info['leave_content']]; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

                                                <?php if($info['order_type'] == 2):?>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 安装类型 </div>
                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"> <?php if($info['install_type']) { echo C('order.install_type')[$info['install_type']]; } else { echo '无'; } ?></span>
                                                    </div>
                                                </div>
                                                <?php endif;?>

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
                                                            <?php echo $status_text[$info['order_status']];?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 联系单与确认函</div>

                                                    <div class="profile-info-value">
                                                        <a href="/orders/contact_list/<?php echo $info['id'];?>" target="_blank">查看联系单</a>

                                                        <?php if($info['order_status'] > 6):?>
                                                        <a href="/orders/confirmation/<?php echo $info['id'];?>" target="_blank">查看确认函</a>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="points" class="tab-pane">
                                            <?php if($info['order_status'] != 8 && ($info['order_type'] == '1' || $info['order_type'] == '2')):?>
                                            <a href="javascript:;" class="btn btn-xs btn-info btn-export" data-id="<?php echo $info['id'];?>" data-type="<?php echo $info['order_type'];?>" style="margin-bottom:10px">
                                                <i class="fa fa-download out_excel" aria-hidden="true"></i> 导出投放点位
                                            </a>
                                            <?php endif;?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">点位编号</th>
                                                        <th>媒体名称</th>
                                                        <th class="hidden-xs">规格</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['selected_points'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['points_code'];?></td>
                                                        <td><?php echo $value['media_name'].'('.$value['media_code'].')';?></td>
                                                        <td><?php echo $value['size'];?><?php if($info['order_type'] == 1) { echo '('.$value['specification_name'].')'; }?></td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="adv_img" class="tab-pane">
                                            <?php if(count($info['adv_img']) > 0):?>
                                                <?php foreach ($info['adv_img'] as $value) : ?>
                                                    <a href="<?php echo $value;?>" target="_blank">
                                                        <img src="<?php echo $value;?>" style="width:300px; height:200px" />
                                                    </a>
                                                <?php endforeach;?>
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 您还未上传广告画面！
                                                    </strong>
                                                    <a class="btn btn-xs btn-info" href="/orders/upload_adv_img/<?php echo $info['id'];?>">
                                                        立即上传
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </a>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        <div id="inspect_img" class="tab-pane">
                                            <?php if(count($info['inspect_img']) > 0):?>
                                                <?php if($info['order_status'] != 8):?>
                                                <a class="btn btn-xs btn-info" href="/orders/check_upload_img/<?php echo $info['id'];?>" style="margin-bottom:10px">
                                                    修改验收图片
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </a>
                                                <?php endif;?>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-2 center">媒体名称</th>
                                                            <?php if($info['order_type'] == '3' || $info['order_type'] == '4'):?>
                                                            <th class="col-xs-5 center">第一张正面图</th>
                                                            <th class="col-xs-5 center">第二章正面图</th>
                                                            <?php else:?>
                                                            <th class="col-xs-5 center">正面图</th>
                                                            <th class="col-xs-5 center">背面图</th>
                                                            <?php endif;?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($info['inspect_img'] as $key => $value) :?>
                                                        <?php 
                                                            $str = '';
                                                            if ($info['order_type'] == 1) {
                                                                $str .= ' '.$value['media_code'].'（'.$number[$value['media_id']].'套）';
                                                            } elseif ($info['order_type'] == 2) {
                                                                $str .= ' '.$value['media_code'];
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['media_name'].$str;?></td>
                                                            <td class="center">
                                                                <a href="<?php echo $value['front_img'];?>" target="_blank" title="点击查看原图">
                                                                    <img style="width: 215px; height: 150px" src="<?php echo $value['front_img'];?>">
                                                                </a>
                                                            </td>
                                                            <td class="center">
                                                                <a href="<?php echo $value['back_img'];?>" target="_blank" title="点击查看原图">
                                                                    <img style="width: 215px; height: 150px" src="<?php echo $value['back_img'];?>">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            <?php else:?>
                                                <div class="alert alert-warning center" style="width:400px">
                                                    <strong>
                                                        <i class="icon-warning-sign bigger-120"></i> 您还未上传验收图片！
                                                    </strong>
                                                    <?php if($info['order_status'] == 6):?>
                                                    <a class="btn btn-xs btn-info" href="/orders/check_upload_img/<?php echo $info['id'];?>">
                                                        立即上传
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </a>
                                                    <?php endif;?>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                        <div id="change_pic_record" class="tab-pane">
                                            <?php if($info['change_pic_record']):?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">换画日期</th>
                                                        <th class="center">换画点位</th>
                                                        <th class="center">订单状态</th>
                                                        <th class="center">操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['change_pic_record'] as $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo date('Y-m-d', strtotime($value['create_time']));?></td>
                                                        <td class="center"><?php echo count(explode(',', $value['point_ids']));?>个点位</td>
                                                        <td class="center">
                                                            <?php 
                                                                switch ($value['order_status']) {
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
                                                                <?php echo $status_text[$value['order_status']];?>
                                                            </span>
                                                        </td>
                                                        <td class="center">
                                                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                                <a class="green tooltip-info" href="/changepicorders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                                                                    <i class="icon-eye-open bigger-130"></i>
                                                                </a> 
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="alert alert-warning center" style="width:400px">
                                                <strong>
                                                    <i class="icon-warning-sign bigger-120"></i> 该订单没有换画记录！
                                                </strong>
                                                <?php if($info['order_status'] == 7):?>
                                                <a class="btn btn-xs btn-info" href="/changepicorders/add/<?php echo $info['order_type'];?>/<?php echo $info['order_code'];?>">
                                                    立即添加
                                                    <i class="icon-arrow-right icon-on-right"></i>
                                                </a>
                                                <?php endif;?>
                                            </div>
                                            <?php endif;?>
                                        </div>

                                        <?php if($info['order_type'] == 1):?>
                                        <div id="change_points_record" class="tab-pane">
                                            <?php if($info['change_points_record']):?>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center">换点日期</th>
                                                        <th class="center">换下点位</th>
                                                        <th class="center">换上点位</th>
                                                        <th class="center">换点之前验收函</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($info['change_points_record'] as $key => $value):?>
                                                    <tr>
                                                        <td class="center"><?php echo $value['operate_time'];?></td>
                                                        <td class="center"><?php echo $value['remove_points'];?></td>
                                                        <td class="center"><?php echo $value['add_points'];?></td>
                                                        <td class="center">
                                                            <a href="/orders/last_confirmation/<?php echo $value['id'];?>" target="_blank">查看</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="alert alert-warning center" style="width:400px">
                                                <strong>
                                                    <i class="icon-warning-sign bigger-120"></i> 该订单没有换点记录！
                                                </strong>
                                            </div>
                                            <?php endif;?>
                                        </div>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <?php if($info['order_status'] < 8):?>
                                <div class="table-responsive">
                                    <div class="page-header" style="margin-top: 50px">
                                        <h1>订单状态跟踪</h1>
                                    </div>
                                    <div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
                                        <ul class="wizard-steps">

                                            <?php
                                            $n = 1;
                                            foreach($status_text as $key=>$val){

                                            ?>
                                            <li data-target="#step1" class="<?php if($key <= $info['order_status']){ echo "active";}?>">

                                                <?php
                                                    $order_status = $info['order_status'];

                                                    if( $info['order_type'] == 3 || $info['order_type'] == 4){
                                                       if($key == 4){
                                                           $order_status = 4;
                                                        }

                                                    ?>
                                                   <span data-status="<?php echo $key;?>" class="step <?php if($key <= $order_status+1){ echo "status-step";}?>" style="cursor: pointer"><?php echo $n;;?></span>
                                                <?php }else{?>
                                                   <span data-status="<?php echo $key;?>" class="step <?php if($key <= $info['order_status']+1){ echo "status-step";}?>" style="cursor: pointer"><?php echo $n;;?></span>
                                                <?php }?>

                                                <span class="title"><?php echo $val;?></span>
                                                <?php
                                                    if(isset($operate_remark)){
                                                ?>
                                                <span class="title" style="color:#CACACA; font-size: 12px ">操作时间:<?php echo @$time[$key];?></span>
                                                <span class="title" style="color:#CACACA; font-size: 12px ">操作备注:<?php echo @$operate_remark[$key];?></span>
                                                <?php }?>
                                            </li>
                                            <?php $n++; } ?>
                                        </ul>
                                    </div>
                                    <input type="hidden" value="<?php echo $id;?>" id="order_id">
                                </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 220px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">更新状态</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                    <label for="message-text" class="control-label">备注:</label>
                    <textarea style="width: 90%; height: 80px" id="remark"></textarea>
               </div>
            </div>
            <div class="modal-footer">
                <span class="error_msg" style="color: red"></span>
                <input type="hidden" value="" id="point-id">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="lock-add">更新</button>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script>
    var adv_img_count = "<?php echo count($info['adv_img']); ?>";
    var inspect_img_count = "<?php echo count($info['inspect_img']); ?>";
    $('[data-rel=tooltip]').tooltip();

    //导出投放点位
    $(".btn-export").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        window.location.href = '/orders/export/' + id + '/' + type;
    });

    //更新订单状态
    $(".status-step").click(function(){
        var order_id = $("#order_id").val();
        var status =$(this).attr("data-status");
        if (adv_img_count == 0) {
            var d = dialog({
                title: '提示信息',
                content: '请先上传广告画面！',
                okValue: '立即上传',
                ok: function () {
                    window.location.href = '/orders/upload_adv_img/<?php echo $info["id"];?>';
                },
            });
            d.width(320);
            d.showModal();
            return false;
        }

        if (status == 7 && inspect_img_count == 0) {
            var d = dialog({
                title: '提示信息',
                content: '您还没有上传验收图片进行验收！',
                okValue: '立即上传',
                ok: function () {
                    window.location.href = '/orders/check_upload_img/<?php echo $info["id"];?>';
                },
            });
            d.width(320);
            d.showModal();
            return false;
        }

        $("#exampleModal").modal('show');
        $("#lock-add").click(function(){
            var remark = $("#remark").val();
            $.ajax( {
                url:'/orders/ajax_update_status',
                data: {
                    'id':order_id,
                    'status':status,
                    'remark':remark,
                    'order_code':$("#order_code").html()
                },
                type:'POST',
                dataType:'json',
                beforeSend:function(){},
                success:function(data) {
                    if(data.status == 0){
                       window.location.reload();
                    } else {
                        $(".error_msg").html(data.msg);
                        return false;
                    }
                    $("#exampleModal").modal('hide');
                }
            });
        });
    });

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

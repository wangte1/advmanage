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
                        <a href="/scheduledorders">预定订单列表</a>
                    </li>
                    <li class="active">预定订单详情</li>
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
                    <h1>预定订单详情</h1>
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
                                            <a data-toggle="tab" href="#points">锁定点位</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="basic" class="tab-pane in active">
                                            <div class="profile-user-info profile-user-info-striped">
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单类型 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $order_type_text[$info['order_type']];?></span>
                                                    </div>
                                                </div> 

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 预定客户 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['customer_name'];?></span>
                                                    </div>
                                                </div> 

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 锁定开始时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['lock_start_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 锁定结束时间 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['lock_end_time'];?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 备注 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php if($info['remark']) { echo $info['remark']; } else { echo '无'; };?></span>
                                                    </div>
                                                </div>

                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单日期 </div>

                                                    <div class="profile-info-value">
                                                        <span class="editable editable-click"><?php echo $info['create_time'];?></span>
                                                    </div>
                                                </div>
                                                <div class="profile-info-row">
                                                    <div class="profile-info-name"> 订单状态 </div>

                                                    <div class="profile-info-value">
                                                        <?php 
                                                            switch ($info['order_status']) {
                                                                case '1':
                                                                    $class = 'badge-yellow';
                                                                    break;
                                                                case '2':
                                                                    $class = 'badge-warning';
                                                                    break;
                                                                case '3':
                                                                    $class = 'badge-grey';
                                                                    break;
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $class; ?>">
                                                            <?php echo $status_text[$info['order_status']];?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="points" class="tab-pane">
                                            <a href="javascript:;" class="btn btn-xs btn-info btn-export" data-id="<?php echo $info['id'];?>" data-type="<?php echo $info['order_type'];?>" style="margin-bottom:10px">
                                                <i class="fa fa-download out_excel" aria-hidden="true"></i> 导出预定点位
                                            </a>
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

<script type="text/javascript">
    //导出预定点位
    $(".btn-export").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        window.location.href = '/scheduledorders/export/' + id + '/' + type;
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

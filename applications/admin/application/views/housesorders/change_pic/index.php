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
                    <li class="active">换画订单</li>
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
                    <a href="/houseschangepicorders/order_type" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新建换画</a>
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
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单编号 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="order_code" class="form-control" value="<?php echo $order_code;?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单类型 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_type" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($order_type_text as $key => $value):?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $order_type){ echo "selected"; }?>><?php echo $value;?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户 </label>
                                                <div class="col-sm-9">
                                                    <select name="customer_id" class="select2">
                                                        <option value="">全部</option>
                                                        <?php foreach($customers as $val):?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['name'];?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单状态 </label>
                                                <div class="col-sm-9">
                                                    <select name="order_status">
                                                        <option value="">全部</option>
                                                        <?php foreach($status_text as $key => $val):?>
                                                        <option value="<?php echo $key;?>" <?php if($key == $order_status){ echo "selected"; }?>><?php echo $val;?></option>
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
                                                <th>订单编号</th>
                                                <th width="7%">订单类型</th>
                                                <th width="7%">换画点位</th>
                                                <th>总价（元）</th>
                                                <th>客户</th>
                                                <th width="6%">业务员</th>
                                                <th>手机号</th>
                                                <th>投放时间</th>
                                                <th width="7%">换画日期</th>
                                                <th>订单状态</th>
                                                <th>创建人</th>
                                                <th width="10%">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list as $key => $value) : ?>
                                            <tr>
                                                <td><a href="/houseschangepicorders/detail/<?php echo $value['id'];?>"><?php echo $value['order_code'];?></a></td>
                                                <td><?php echo $order_type_text[$value['order_type']];?></td>
                                                <td><?php echo $value['point_ids'] ? count(explode(',', $value['point_ids'])) : 0;?>个点位</td>
                                                <td><?php echo $value['total_price'];?></td>
                                                <td><?php echo $value['customer_name'];?></td>
                                                <td><?php echo $value['sales_name'];?></td>
                                                <td><?php echo $value['sales_mobile'];?></td>
                                                <td><?php echo $value['release_start_time'].'至'.$value['release_end_time'];?></td>
                                                <td><?php echo $value['create_time'];?></td>
                                                <td>
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

                                                    <?php if($value['order_status'] == 7):?>
                                                        <?php if((date('Y-m-d') < $value['release_end_time']) && diff_days(date('Y-m-d'), $value['release_end_time']) <= 7):?>
                                                        <span class="badge badge-danger">
                                                            即将到期
                                                        </span>
                                                        <?php elseif(date('Y-m-d') > $value['release_end_time']):?>
                                                        <span class="badge badge-pink">
                                                            已到期，未下画
                                                        </span>
                                                        <?php endif;?>
                                                    <?php endif;?>
                                                </td>
                                                <td><?php echo $admins[$value['create_user']];?></td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a class="green tooltip-info" href="/houseschangepicorders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a> 
                                                        <?php if($value['order_status'] == 1): ?>
                                                            <a class="green tooltip-info" href="/houseschangepicorders/edit/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                            <a class="green tooltip-info" href="/houseschangepicorders/upload_adv_img/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="广告画面">
                                                                <i class="fa fa-file-image-o bigger-130"></i>
                                                            </a>
                                                            <a class="green tooltip-info" href="/houseschangepicorders/contact_list/<?php echo $value['id'];?>" target="_blank" data-rel="tooltip" data-placement="top" title="" data-original-title="生成联系单">
                                                                <i class="fa fa-building-o bigger-130"></i>
                                                            </a>
                                                        <?php endif;?>
                                                        <?php if($value['order_status'] == 6 || $value['order_status'] == 7):?>
                                                        <a class="green tooltip-info" href="/houseschangepicorders/check_upload_img/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="验收图片">
                                                            <i class="fa fa-picture-o bigger-130"></i>
                                                        </a>
                                                        <?php endif;?>
                                                        <?php if($value['order_status'] == 6): ?>
                                                        <a class="green tooltip-info" href="/houseschangepicorders/confirmation/<?php echo $value['id'];?>" target="_blank" data-rel="tooltip" data-placement="top" title="" data-original-title="生成确认函">
                                                            <i class="fa fa-clone bigger-130"></i>
                                                        </a>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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

<script type="text/javascript">
    $(".select2").css('width','240px').select2({allowClear:true});
    $('[data-rel=tooltip]').tooltip();
    $('.del-spa').click(function(){
        var _self = $(this);
        var d = dialog({
            title: "提示",
            content: '确定删除该制作公司吗？',
            okValue: '确定',
            ok: function () {
                window.location.href = '/makecompany/del/' + _self.attr('data-id') + '/' + _self.attr('data-del');
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(320);
        d.showModal();
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

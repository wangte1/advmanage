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
                    <li class="active">订单列表</li>
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
                    <a href="/orders/order_type" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新建订单</a>
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
                                                        <option value="<?php echo $val['id'];?>" <?php if($val['id'] == $customer_id){ echo "selected"; }?>><?php echo $val['customer_name'];?></option>
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

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 即将到期 </label>
                                                <div class="col-sm-9">
                                                    <label>
                                                        <input type="checkbox"  name="expire_time" class="ace" value="1" <?php if($expire_time == 1){ echo "checked";}?> />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 已到期未下画 </label>
                                                <div class="col-sm-9">
                                                    <label>
                                                        <input type="checkbox"  name="overdue" class="ace" value="1" <?php if($overdue == 1){ echo "checked";}?> />
                                                        <span class="lbl"></span>
                                                    </label>
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
                                                <th width="7%">投放点位</th>
                                                <th>总价（元）</th>
                                                <th>客户</th>
                                                <th width="6%">业务员</th>
                                                <th>手机号</th>
                                                <th>投放时间</th>
                                                <th width="7%">订单日期</th>
                                                <th>订单状态</th>
                                                <th>创建人</th>
                                                <th width="10%">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list as $key => $value) : ?>
                                            <tr>
                                                <td>
                                                    <a href="/orders/detail/<?php echo $value['id'];?>"><?php echo $value['order_code'];?></a>
                                                    <?php if($value['order_status'] == 7):?>
                                                    <a class="green tooltip-info" href="/changepicorders/add/<?php echo $value['order_type'];?>/<?php echo $value['order_code'];?>"  data-rel="tooltip" data-placement="top" data-original-title="新建换画">
                                                        <i class="fa fa-plus-square bigger-130" aria-hidden="true"></i>
                                                    </a>
                                                    <?php endif;?>
                                                </td>
                                                <td><?php echo $order_type_text[$value['order_type']];?></td>
                                                <td><?php echo $value['point_ids'] ? count(explode(',', $value['point_ids'])) : 0;?>个点位</td>
                                                <td>
                                                    <span class="span-price<?php echo $value['id'];?>"><?php echo $value['total_price'];?></span>
                                                    <?php if($value['order_status'] < 8):?>
                                                    <input type="text" name="total_price<?php echo $value['id'];?>" style="width:100px; display: none" value="<?php echo $value['total_price'];?>" />
                                                    <a class="green tooltip-info edit-price" href="javascript:;" data-price="<?php echo $value['total_price'];?>" data-id="<?php echo $value['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改总价">
                                                        <i class="icon-pencil bigger-130"></i>
                                                    </a>
                                                    <?php endif;?>
                                                </td>
                                                <td><?php echo $value['customer_name'];?><?php if(isset($project[$value['project_id']])) { echo '-'.$project[$value['project_id']]; } ?></td>
                                                <td><?php echo $value['sales_name'];?></td>
                                                <td><?php echo $value['sales_mobile'];?></td>
                                                <td>
                                                    <?php echo $value['release_start_time'].'至'.$value['release_end_time'];?>
                                                    <?php
                                                            $release_end_time =  strtotime($value['release_end_time']);
                                                            $today_time = strtotime(date("Y-m-d"));
                                                            $between_time =  60*60*24*7;
                                                    if(($value['order_status'] == 7 && ($release_end_time - $today_time) >= 0 && ($release_end_time - $today_time) <= $between_time)
                                                        || ($value['order_status'] == 7 && ($release_end_time < $today_time))){
                                                    ?>
                                                    <span class="label label-sm label-primary arrowed arrowed-right expire-time" data-id="<?php echo $value['id'];?>"  data-order="<?php echo $value['order_code'];?>" style="cursor: pointer" title="续期">续期</span>
                                                    <?php }?>
                                                </td>
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
                                                        <?php if((date('Y-m-d') <= $value['release_end_time']) && diff_days(date('Y-m-d'), $value['release_end_time']) <= 7):?>
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
                                                        <a class="green tooltip-info" href="/orders/detail/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="详情">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a> 
                                                        
                                                        <a class="green tooltip-info" onclick="deleteOrder(<?php echo $value['id'];?>);" href="javascript:void(0);"  data-rel="tooltip" data-placement="top" title="" data-original-title="删除">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a> 

                                                        <?php if($value['order_status'] == 1): ?>
                                                            <a class="green tooltip-info" href="/orders/edit/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                            <a class="green tooltip-info" href="/orders/upload_adv_img/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="广告画面">
                                                                <i class="fa fa-file-image-o bigger-130"></i>
                                                            </a>
                                                            <a class="green tooltip-info" href="/orders/contact_list/<?php echo $value['id'];?>" target="_blank" data-rel="tooltip" data-placement="top" title="" data-original-title="生成联系单">
                                                                <i class="fa fa-building-o bigger-130"></i>
                                                            </a>
                                                        <?php endif;?>
                                                        <?php if($value['order_status'] == 6 || $value['order_status'] == 7):?>
                                                        <a class="green tooltip-info" href="/orders/check_upload_img/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="验收图片">
                                                            <i class="fa fa-picture-o bigger-130"></i>
                                                        </a>
                                                        <?php endif;?>
                                                        <?php if($value['order_status'] == 6):?>
                                                        <a class="green tooltip-info" href="/orders/confirmation/<?php echo $value['id'];?>" target="_blank" data-rel="tooltip" data-placement="top" title="" data-original-title="生成确认函">
                                                            <i class="fa fa-clone bigger-130"></i>
                                                        </a>
                                                        <?php endif;?>

                                                        <!-- 1公交，2高杆 -->
                                                        <?php if($value['order_status'] == 7  && ( in_array($value['order_type'], [1,2]) ) ): ?>
                                                            <a class="green tooltip-info" href="/orders/edit_points/<?php echo $value['id'];?>"  data-rel="tooltip" data-placement="top" title="" data-original-title="修改点位">
                                                                <i class="icon-pencil bigger-130"></i>
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
                                    <div class="row">
                                        <div class="col-md-12" style="color: #A0A0A0; padding: 10px; margin-top: 50px; background: #F5F5F5">
                                            注:即将到期表示订单7天内即将到期的订单！
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document" style="margin-top: 150px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">投放结束时间</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group" style="margin-bottom: 20px">
                        <label for="message-text" class="control-label" style="float: left; padding-top: 8px">请选择投放结束时间:</label>
                        <div class="input-group date datepicker" style="width: 280px; float: left; padding-left: 5px">
                            <input class="form-control date-picker release_end_time" value="<?php echo date("Y-m-d",strtotime("+15 day"))?>" id="id-date-picker-1" type="text" data-date-format="dd-mm-yyyy">
                            <span class="input-group-addon">
                                <i class="icon-calendar bigger-110"></i>
                            </span>
                        </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer" id="info-msg" style="background: #fff; text-align: center;font-size: 13px; color: #428bca"></div>
            <div class="modal-footer">
                <span class="error_msg" style="color: red"></span>
                <input type="hidden" value="" id="point-id">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="expire-add">保存</button>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script type="text/javascript">
    $('[data-rel=popover]').popover({html:true});

    $(".expire-time").click(function(){
        $("#exampleModal").modal('show');
        var _self = $(this);
        var id = _self.attr("data-id");
        var order = _self.attr("data-order");
        $("#expire-add").click(function(){
            var release_end_time = $(".release_end_time").val();
            $.ajax( {
                url:'/orders/extend_time',
                data: {
                    'id':id,
                    'order_code':order,
                    'release_end_time':release_end_time
                },
                type:'POST',
                dataType:'json',
                success:function(data) {
                    if(data.status == 0){
                        $('.error_msg').html("续期成功！");
                        setInterval(function(){
                            window.location.reload();
                        },2000);
                    } else {
                        $('.error_msg').html(data.msg);
                        setInterval("delInfo('.error_msg')",2000);
                        return false;
                    }

                },
                error : function() {

                }
            });
        });

    });

    $(".select2").css('width','240px').select2({allowClear:true});

    //修改订单总价
    $('.edit-price').click(function(){
        var _self = $(this); 
        if (_self.children().hasClass('icon-pencil')) {
            _self.children().removeClass('icon-pencil bigger-130').addClass('fa fa-check bigger-130');
            $('input[name="total_price'+_self.attr('data-id')+'"]').show();
            $('.span-price'+_self.attr('data-id')).hide();
        } else {
            _self.children().removeClass('fa fa-check bigger-130').addClass('icon-pencil bigger-130');
            $('input[name="total_price'+_self.attr('data-id')+'"]').hide();
            $('.span-price'+_self.attr('data-id')).show();

            if ($('input[name="total_price'+_self.attr('data-id')+'"]').val() != _self.attr('data-price')) {
                $.post(
                    '/orders/edit_price', 
                    {
                        id: _self.attr('data-id'),
                        total_price: $('input[name="total_price'+_self.attr('data-id')+'"]').val(),
                    }, 
                    function(data){
                        if (data.flag) {
                            $('.span-price'+_self.attr('data-id')).html(data.total_price);
                        } else {
                            var d = dialog({
                                title: "提示",
                                content: data.msg,
                                okValue: '确定',
                                ok: function () {
                                },
                            });
                            d.width(320);
                            d.showModal();
                        }
                    }
                );
            }
        }
    });

    //删除提示信息
    function delInfo(obj) {
        $(obj).html('');
    }

    function deleteOrder(id) {
    	layer.prompt({title: '请输入删除口令！', formType: 1}, function(pass, index){
        		if(pass == 'adminnn123') { //等时间充裕以后改到后台获取 yangxiong 2017-0-14
        			$.ajax({
                        url:'/orders/del_order',
                        data: {
                            'id':id
                        },
                        type:'POST',
                        dataType:'json',
                        success:function(data) {
                            if(data) {
                            	layer.alert(data.msg);
                            	location.reload();
                            }
                            
                        }
                       
                    });
            	}
    		  //layer.close(index);
    		});
    }

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

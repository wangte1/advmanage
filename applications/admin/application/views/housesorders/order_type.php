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
                        选择订单类型
                        <a href="/corders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal">
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label no-padding-right" for="form-field-2"> 订单类型： </label>
                                <div class="col-sm-8">
                                    <?php foreach ($order_type_text as $key => $value):?>
                                        <label class="blue">
                                            <input name="order_type" value="<?php echo $key;?>" required type="radio" class="ace" <?php if($key == 1) { echo "checked"; }?>/>
                                            <span class="lbl"> <?php echo $value;?></span>
                                        </label>
                                        &nbsp;
                                    <?php endforeach;?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label no-padding-right" for="form-field-2"> 投放行业： </label>
                                <div class="col-sm-8">
                                	<select name="put_trade">
                                		<option value="0">无</option>
                                		<?php foreach (C('housespoint.put_trade') as $k => $v):?>
                                			<option value="<?php echo $k;?>"><?php echo $v;?></option>
                                   		<?php endforeach;?>
                                	</select>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-4 col-md-8">
                                    <button class="btn btn-success btn-next" data-last="Finish " type="button">
                                        下一步
                                        <i class="icon-arrow-right icon-on-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script type="text/javascript">
    $(".btn-next").click(function(){
        var order_type = $("input[name='order_type']:checked").val();
        var put_trade = $("select[name='put_trade']").val();
        window.location.href = '/housesorders/add/' + order_type+'/'+put_trade;
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

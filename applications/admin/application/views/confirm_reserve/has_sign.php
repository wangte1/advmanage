<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('webuploader.css', 'common');?>" rel="stylesheet" />
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
<style>
    #uploader_cover_img img, .add-pic{ width: 150px; height: 150px;}
    .ace-thumbnails>li {

       border: 1px solid #333;
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
                        <a href="#">意向订单管理</a>
                    </li>
                    <li>
                        <a href="/confirm_reserve">预定订单待确认列表</a>
                    </li>
                    <li>
                        <a href="/confirm_reserve/detail/<?php echo $order_id;?>">已确认点位</a>
                    </li>
                    
                    <li class="active">客户确认预约订单</li>
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
                                                              客户确认预约订单
                        <a href="/houseswantorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                          
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 客户： </label>
                                            <div class="col-sm-10">
                                            	<span><?php echo $customer['name']?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 联系人： </label>
                                            <div class="col-sm-10">
                                                <span><?php echo $customer['contact_person']?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span><?php if(isset($orderInfo['lock_start_time'])){ echo $orderInfo['lock_start_time'];}?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span><?php if(isset($orderInfo['lock_end_time'])){ echo $orderInfo['lock_end_time'];}?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        	<label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 点位签字照片： </label>
                                            <div class="col-sm-5">
                                            	<div class="row-fluid">
                                                    <ul class="ace-thumbnails" id="uploader_cover_img" data='0'>
                                                        <?php if(isset($orderInfo['confirm_img']) && !empty($orderInfo['confirm_img'])):?>
                                                        <?php foreach (explode(';', $orderInfo['confirm_img']) as $k => $v):?>
                                                        <li id="uploader_cover_img_<?php echo $k;?>" style="float: left;width: 150px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                                            <a href="<?php echo $v;?>" target="_blank" class="up-img">
                                                            	<img style="height:100%;width:100%;" src="<?php echo $v;?>">
                                                            </a>
                                                    	</li>
                                                        <?php endforeach;?>
                                                        <?php endif;?>
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <p class="form-control" name="remarks" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($orderInfo['remarks'])) { echo $orderInfo['remarks'];}?></p>
                                            </div>
                                        </div>
                                    </form>
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
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

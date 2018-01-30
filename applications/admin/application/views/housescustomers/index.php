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
                        <a href="#">社区资源管理</a>
                    </li>
                    <li class="active">客户管理</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search" method="get" action="#">
                        <span class="input-icon">
                            <input type="text" placeholder="请输入客户名称..."  value="<?php echo $name;?>" name="name" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housescustomers/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增客户</a>
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
                                    <form class="form-horizontal" role="form" method="get" action="#">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户名称 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" name="name"  value="<?php echo $name;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 类型： </label>
                                                <div class="col-sm-9">
                                                    <select class="col-xs-7 " name="type" id="form-field-select-1" >
                                                        <option value="">全部</option>
                                                        <?php foreach($customer_type as $key=>$val){ ?>
                                                            <option <?php if($key == $type){ echo "selected";} ?>   value="<?php echo $key;?>" ><?php echo $val;?></option>
                                                        <?php } ?>
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
                                                <th>编号</th>
                                                <th>客户名称</th>
                                                <th>联系人</th>
                                                <th>联系人手机号</th>
                                                <th>联系人微信</th>
                                                <th>联系人QQ</th>
                                                <th>联系人email</th>
                                                <th>类型</th>
                                                <th>客户地址</th>
                                                <th>说明</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:;"><?php echo $val['id'];?></a>
                                                    </td>
                                                    <td><?php echo $val['name'];?></td>
                                                    <td><?php echo $val['contact_person'];?></td>
                                                    <td><?php echo $val['contact_tel'];?></td>
                                                    <td><?php echo $val['weixin'];?></td>
                                                    <td><?php echo $val['qq'];?></td>
                                                    <td><?php echo $val['email'];?></td>
                                                    <td><?php echo $customer_type[$val['type']];?></td>
                                                    <td>
                                                        <?php echo $val['addr'];?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['remarks'];?>
                                                    </td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housescustomers/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                            <a class="red tooltip-info customer-del" href="javascript:;" data-url="/housescustomers/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } }?>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="dataTables_info" id="sample-table-2_info">
                                                共<a class="blue"><?php echo $data_count;?></a>条记录，当前显示第&nbsp;<a class="blue"><?php echo $page;?>&nbsp;</a>页
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="dataTables_paginate paging_bootstrap">

                                                <ul class="pagination">
                                                    <?php echo $pagestr;?>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- 分页 -->
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
    $('[data-rel=tooltip]').tooltip();
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

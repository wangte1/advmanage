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
                    <li class="active">app管理</li>
                </ul>

            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housesapp/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增版本</a>
                </div> 
                
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>编号</th>
                                                <th>版本号</th>
                                                <th>下载地址</th>
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
                                                    <td><?php echo $val['version'];?></td>
                                                    <td><?php echo $val['url'];?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housesapp/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                            <!--a class="red tooltip-info customer-del" href="javascript:;" data-url="/housesapp/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>"  data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a-->
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

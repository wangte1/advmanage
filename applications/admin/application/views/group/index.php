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
                        <a href="#">管理员管理</a>
                    </li>
                    <li class="active">角色管理</li>
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
                        <a href="/admingroup/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加角色</a>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>编号</th>
                                            <th>角色名</th>
                                            <th>描述</th>
                                            <th class="hidden-480">管理员数量</th>
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
                                                        <a href="#"><?php echo $val['id'];?></a>
                                                    </td>
                                                    <td><?php echo $val['name'];?></td>
                                                    <td><?php echo $val['describe'];?></td>

                                                    <td class="hidden-480">
                                                        <span class="label label-sm label-info arrowed arrowed-righ"><?php echo $val['admin_count'];?></span>
                                                    </td>

                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green" href="/admingroup/edit/<?php echo $val['id'];?>" title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>

                                                            <a class="green" href="/admingroup/purview/<?php echo $val['id'];?>" title="分配权限">
                                                                <i class="icon-cog bigger-130"></i>
                                                            </a>

                                                            <a class="red" href="/admingroup/del/<?php echo $val['id'];?>" title="删除">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a>
                                                        </div>


                                                    </td>
                                                </tr>
                                            <?php } }?>

                                        </tbody>
                                    </table>
                                    <!--分页start-->
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
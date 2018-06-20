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
                        <a href="../">首页</a>
                    </li>
                    <li>
                        <a href="#">管理员管理</a>
                    </li>
                    <li class="active">管理员列表</li>
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
                    <a  href="/admin/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加管理员</a>
                	<a href="/admin/partition" class="btn btn-sm btn-primary"><i class="ace-icon glyphicon glyphicon-edit" aria-hidden="true"></i> 分配人员区域</a>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 登录名 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>" class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 姓名 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="fullname" value="<?php echo $fullname;?>" class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
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
                                                <th class="center">
                                                    <label>
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </th>
                                                <th>编号</th>
                                                <th>登录名</th>
                                                <th>姓名</th>
                                                <th class="hidden-480">Email</th>
                                                <th>角色</th>
                                                <th class="hidden-480">创建者</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($admin_list){
                                                foreach($admin_list as $key=>$val){
                                            ?>
                                            <tr>
                                                <td class="center">
                                                    <label>
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <a href="#"><?php echo $val['id'];?></a>
                                                </td>
                                                <td><?php echo $val['name'];?></td>
                                                <td><?php echo $val['fullname'];?></td>
                                                <td><?php echo $val['email'];?></td>
                                                <td class="hidden-480">
                                                    <span class="label label-sm label-info arrowed arrowed-righ"><?php echo @$groups[$val['group_id']];?></span>
                                                </td>
                                                <td><?php echo @$admins[$val['create_admin']];?></td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a class="green" href="/admin/read/<?php echo $val['id'];?>" title="查看">
                                                            <i class="icon-eye-open bigger-130"></i>
                                                        </a>
                                                        <a class="green" href="/admin/edit/<?php echo $val['id'];?>" title="修改">
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>
                                                        <a class="green" href="/admin/purview/<?php echo $val['id'];?>" title="分配权限">
                                                            <i class="icon-cog bigger-130"></i>
                                                        </a>
                                                        <a class="red" href="/admin/del/<?php echo $val['id'];?>" title="删除">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } }?>
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

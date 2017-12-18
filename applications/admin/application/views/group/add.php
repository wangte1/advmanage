<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('chosen.css', 'admin');?>" rel="stylesheet" />

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
                    <li>
                        <a href="#">角色管理</a>
                    </li>
                    <li class="active">添加角色</li>
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
                        添加角色
                        <a  href="/admingroup" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">角色列表</a>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">

                           <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 角色名： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="name" required id="form-field-1" placeholder="请输入角色名" class="col-xs-10 col-sm-5">
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" style="color: red">*</span>
									</span>
                                </div>
                            </div>

                           <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">  描述： </label>

                                <div class="col-sm-9">
                                    <textarea id="form-field-11" name="describe" class="autosize-transition col-xs-10 col-sm-5" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"></textarea>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="submit">
                                        <i class="icon-ok bigger-110"></i>
                                        添加
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
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


    </div>
    
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

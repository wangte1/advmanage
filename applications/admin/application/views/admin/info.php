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
                    <li class="active">管理员详情</li>
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
                        <small>
                            管理员详情
                        </small>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6 col-sm-6 pricing-box">
                                    <div class="widget-box">
                                        <div class="widget-header header-color-blue">
                                            <h5 class="bigger lighter"><?php echo $info['fullname'];?>-基本信息</h5>
                                        </div>

                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <ul class="list-unstyled spaced2">
                                                    <li class="li-hr">
                                                        <a class="green">角色:</a>
                                                        <?php echo $groups[$info['group_id']];?>
                                                    </li>
                                                    <li class="li-hr">
                                                        <a class="green">登录名:</a>
                                                        <?php echo $info['name'];?>
                                                    </li>

                                                    <li class="li-hr">
                                                        <a class="green">姓名:</a>
                                                        <?php echo $info['fullname'];?>
                                                    </li>
                                                    <li class="li-hr">
                                                        <a class="green">Email:</a>
                                                        <?php echo $info['email'];?>
                                                    </li>
                                                    <li class="li-hr">
                                                        <a class="green">手机:</a>
                                                        <?php echo $info['tel'];?>
                                                    </li>

                                                    <li class="li-hr">
                                                        <a class="green">描述:</a>
                                                        <?php echo $info['describe'];?>
                                                    </li>

                                                </ul>
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
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>社区媒体|媒介管理系统</title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- basic styles -->

    <link href="<?php echo css_js_url('bootstrap.min.css', 'admin');?>" rel="stylesheet" />
    <link href="<?php echo css_js_url('font-awesome.min.css', 'admin');?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo css_js_url('font-awesome.min.css', 'admin');?>" type="text/css" />
    <link href="<?php echo css_js_url('ui-dialog.css', 'admin');?>" rel="stylesheet" />

    <!--[if IE 7]>

    <link href="<?php echo css_js_url('font-awesome-ie7.min.css', 'admin');?>" rel="stylesheet" />
    <![endif]-->
    <!-- fonts -->

    <link href="<?php echo css_js_url('ace-fonts.css', 'admin');?>" rel="stylesheet" />

    <!-- ace styles -->

    <link href="<?php echo css_js_url('ace.min.css', 'admin');?>" rel="stylesheet" />
    <link href="<?php echo css_js_url('ace-rtl.min.css', 'admin');?>" rel="stylesheet" />
    <!--[if lte IE 8]>
    <link href="<?php echo css_js_url('ace-ie.min.css', 'admin');?>" rel="stylesheet" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="<?php echo css_js_url('html5shiv.js','admin');?>"></script>
    <script src="<?php echo css_js_url('respond.min.js','admin');?>"></script>


    <![endif]-->
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="icon-leaf green"></i>
                           <span class="white">媒介管理系统</span>
                        </h1>
                        <h4 class="blue">&copy; 腾讯房产</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon-coffee green"></i>
                                       请输入用户名和密码
                                    </h4>

                                    <div class="space-6"></div>

                                    <form>
                                        <fieldset>
                                            <label class="block clearfix">
												<span class="block input-icon input-icon-right">
													<input type="text" class="form-control loginuser" placeholder="用户名" />
													<i class="icon-user"></i>
												</span>
                                            </label>

                                            <label class="block clearfix">
												<span class="block input-icon input-icon-right">
													<input type="password" class="form-control loginpwd" placeholder="密码" />
													<i class="icon-lock"></i>
												</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input type="checkbox" class="ace" />
                                                    <span class="lbl"> 自动登录</span>
                                                </label>

                                                <button type="button" class="width-35 pull-right btn btn-sm btn-primary loginbtn">
                                                    <i class="icon-key"></i>
                                                    登录
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>

                                    <div class="social-or-login center">
                                        <span class="bigger-110">Or Login Using</span>
                                    </div>
                                </div>
                                <div class="toolbar clearfix white" style="padding: 5px 2px;">
                                   Copyright ©2016-2017大视传媒wesogou.com版权所有
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("common/footer");?>

<script type="text/javascript">
    function show_box(id) {
        jQuery('.widget-box.visible').removeClass('visible');
        jQuery('#'+id).addClass('visible');
    }
</script>
<script src="<?php echo css_js_url('login.js','admin');?>"></script>
<script src="<?php echo css_js_url('dialog.js','admin');?>"></script>
</body>
</html>

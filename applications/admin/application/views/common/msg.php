<?php $this->load->view("common/header");?>
<style>
    .main{ padding-left: 120px;background: #ffffff; padding-bottom: 15px;}
    p{ font-size: 13px;}
    a{ text-decoration: none; }
    i{font-style: normal;}
    li{ list-style: none}
    .btm{ height: 50px; text-align: center; color: #9CACAF; font-size: 12px; background: #F4FAFB; margin-top: 15px; line-height: 50px}
</style>
<?php $this->load->view("common/top");?>

<div class="main-container" id="main-container">
   <div class="main-container-inner">
       <!-- 左边导航菜单 -->
       <?php $this->load->view('common/left');?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="/home">Home</a>
                    </li>

                    <li>
                        <a href="#">信息提示</a>
                    </li>

                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        信息提示
                     </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="main page-header">
                            <h1><?php echo $message;?></h1>
                            <p style="padding-top: 10px">如果您不做出选择，将在 <i id="spanSeconds"><?php echo $waitSecond;?></i>秒钟跳转</p>
                            <p><a href="<?php echo $jumpUrl;?>" >如果您的浏览器没有跳转请点这里</a></p>
                        </div>

                        <div class="col-xs-12 btm">管理中心</div>
                    </div><!-- /.col -->
                </div><!-- /.row -->

            </div><!-- /.page-content -->
        </div><!-- /.main-content -->



    </div><!-- /.main-container -->



    <!-- basic scripts -->

    <!-- 加载尾部公用js -->
    <?php $this->load->view("common/footer");?>


    <script language="JavaScript">
        <!--
        var seconds = <?php echo $waitSecond;?>;
        var defaultUrl = "<?php echo $jumpUrl;?>";


        onload = function()
        {
            if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
            {
                document.getElementById('redirectionMsg').innerHTML = '';
                return;
            }

            window.setInterval(redirection, 1000);
        }
        function redirection()
        {
            if (seconds <= 0)
            {
                window.clearInterval();
                return;
            }

            seconds --;
            document.getElementById('spanSeconds').innerHTML = seconds;

            if (seconds == 0)
            {
                location.href = defaultUrl;
            }
        }
        //-->
    </script>
    <!-- 底部 -->
    <?php $this->load->view("common/bottom");?>




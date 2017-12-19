<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<style>
    #uploader_cover_img img{ width: 200px; height: 150px;}
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
                        <a href="/orders">订单管理</a>
                    </li>
                    <li class="active">上传广告画面</li>
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
                    <h1>上传广告画面</h1>
                    <a  href="javascript:history.back(-1);" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回上一页</a>

                </div>

                <div class="row">
                    <form action="" method="post">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row-fluid">
                            <ul class="ace-thumbnails" id="uploader_cover_img">

                             <?php
                                if($adv_img){
                                foreach($adv_img as $val){
                                ?>
                                    <li data-id="4545">
                                        <a href="<?php echo $val;?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                            <img alt="150x150" width="150px" height="150px" src="<?php echo $val;?>">

                                        </a>
                                        <div class="tools">
                                            <a href="#">
                                                <i class="icon-remove red"></i>
                                            </a>
                                        </div>
                                        <input type="hidden" name="cover_img[]" value="<?php echo $val;?>"/>
                                    </li>
                                <?php }}?>

                                <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                    <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="col-xs-12">
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-5 col-md-7">
                                <button class="btn btn-info" type="submit" id="subbtn">
                                    <i class="icon-ok bigger-110"></i>
                                    保存
                                </button>
                            </div>
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
<script type="text/javascript">
    var baseUrl = "<?php echo $domain['admin']['url'];?>";
    var staticUrl = "<?php echo $domain['static']['url']?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('jquery.swfupload.js', 'common');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('swfupload.js', 'admin')?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('admin_upload.js', 'admin');?>"></script>
<script>
    $(function(){
        var colorbox_params = {
            reposition:true,
            scalePhotos:true,
            scrolling:false,
            previous:'<i class="icon-arrow-left"></i>',
            next:'<i class="icon-arrow-right"></i>',
            close:'&times;',
            current:'{current} of {total}',
            maxWidth:'100%',
            maxHeight:'100%',
            onOpen:function(){
                document.body.style.overflow = 'hidden';
            },
            onClosed:function(){
                document.body.style.overflow = 'auto';
            },
            onComplete:function(){
                $.colorbox.resize();
            }
        };

        $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
        $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

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
                        <a href="/orders">订单管理</a>
                    </li>
                    <li class="active">上传验收图片</li>
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
                        上传验收图片
                        <a  href="/changepicorders" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表</a>
                    </h1>
                </div>

                <div class="row">
                    <form action="" method="post">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <div class="row-fluid">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-2 center">媒体名称</th>
                                            <?php if($order_type == '3' || $order_type == '4'):?>
                                            <th class="col-xs-5 center">第一张正面图</th>
                                            <th class="col-xs-5 center">第二章正面图</th>
                                            <?php else:?>
                                            <th class="col-xs-5 center">正面图</th>
                                            <th class="col-xs-5 center">背面图</th>
                                            <?php endif;?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key => $value) :?>
                                        <tr>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['name'].'（'.$value['code'].'）';?></td>
                                            <td>
                                                <ul class="ace-thumbnails" media-id="<?php echo $value['id'];?>" id="uploader_front_img<?php echo $key;?>">
                                                    <?php if(isset($value['image']['front_img'])): ?>
                                                    <li>
                                                        <a href="<?php echo $value['image']['front_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                            <img style="width: 215px; height: 150px" src="<?php echo $value['image']['front_img'];?>">
                                                        </a>
                                                        <div class="tools">
                                                            <a href="#">
                                                                <i class="icon-remove red"></i>
                                                            </a>
                                                        </div>
                                                        <input type="hidden" name="<?php echo $value['id'];?>[front_img]" value="<?php echo $value['image']['front_img'];?>"/>
                                                    </li>
                                                    <?php endif;?>
                                                    <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                                        <a href="javascript:;" class="up-img"  id="file_front_img<?php echo $key;?>"><span>+</span><br>添加照片</a>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="ace-thumbnails" media-id="<?php echo $value['id'];?>" id="uploader_back_img<?php echo $key;?>">
                                                    <?php if(isset($value['image']['back_img'])): ?>
                                                    <li>
                                                        <a href="<?php echo $value['image']['back_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                            <img style="width: 215px; height: 150px" src="<?php echo $value['image']['back_img'];?>">
                                                        </a>
                                                        <div class="tools">
                                                            <a href="#">
                                                                <i class="icon-remove red"></i>
                                                            </a>
                                                        </div>
                                                        <input type="hidden" name="<?php echo $value['id'];?>[back_img]" value="<?php echo $value['image']['back_img'];?>"/>
                                                    </li>
                                                    <?php endif;?>
                                                    <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                                        <a href="javascript:;" class="up-img"  id="file_back_img<?php echo $key;?>"><span>+</span><br>添加照片</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
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
    var mediaNum = "<?php echo count($list);?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('jquery.swfupload.js', 'common');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('swfupload.js', 'admin')?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('inspectimg_upload.js', 'admin');?>"></script>
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

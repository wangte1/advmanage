<!-- 加载公用css -->
<?php $this->load->view('common/header');?>



            <div class="page-content">

                <div class="row">
                    <form action="" method="post">
                        <div class="col-xs-12">
                            <div class="row-fluid">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                        	<th class="center" nowrap>点位编号</th>
                                        	<th class="center" nowrap>楼盘</th>
                                            <th class="center" nowrap>组团</th>
                                            <th class="center" nowrap>楼栋</th>
                                            <th class="center" nowrap>单元</th>
                                            <th class="center" nowrap>楼层</th>
                                            <th class="center" nowrap>点位位置</th>
                                            <th class="center" nowrap>图片</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($list))foreach ($list as $key => $value) :?>
                                        <tr>
                                        	<td style="text-align: center;vertical-align: middle;"><?php echo $value['code'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                            <td style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                            <td class="center">
                                                <ul class="ace-thumbnails" media-id="<?php echo $value['id'];?>" id="uploader_front_img<?php echo $key;?>">
                                                    <?php if(isset($value['image']) && count($value['image']) > 0): ?>
                                                        <?php foreach($value['image'] as $val):?>
                                                        <li>
                                                            <a href="<?php echo $val['front_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                                <img style="width: 215px; height: 150px" src="<?php echo $val['front_img'];?>">
                                                            </a>
                                                            <div class="tools">
                                                                <a href="javascript:;">
                                                                    <i class="icon-remove red"></i>
                                                                </a>
                                                            </div>
                                                            <input type="hidden" name="<?php echo $value['id'];?>[front_img][]" value="<?php echo $val['front_img'];?>"/>
                                                        </li>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                    <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                                        <a href="javascript:;" class="up-img"  id="file_front_img<?php echo $key;?>"><span>+</span><br>添加照片</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                
                                <!-- 分页 -->
                                <?php $this->load->view('common/page');?>
                            </div>
                        </div>
						
                        <div class="col-xs-12" style="position: fixed; bottom: 0">
                            <div class="clearfix form-actions" style="padding: 0; margin-bottom: 0">
                                <div class="col-md-offset-5 col-md-7">
                                    <button class="btn btn-info" type="submit" id="subbtn">
                                        <i class="icon-ok bigger-110"></i>
                                        保存
                                    </button>
                                    <font style="color:red;">注：只能保存当前分页的上传</font>
                                </div>
                            </div>
                        </div>
                    </form>

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

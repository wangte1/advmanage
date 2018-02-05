<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
            <div class="page-content">

                <div class="row">
                        <div class="col-xs-12">
                            <div class="row-fluid">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                        	<th class="center phone-hide" >点位编号</th>
                                        	<th class="center phone-hide" >楼盘</th>
                                            <th class="center phone-hide" >组团</th>
                                            <th class="center phone-hide" >楼栋</th>
                                            <th class="center phone-hide" >单元</th>
                                            <th class="center phone-hide" >楼层</th>
                                            <th class="center phone-hide" >点位位置</th>
                                            <th class="center phone-show" >地址</th>
                                            <th class="center"  >图片</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($list))foreach ($list as $key => $value) :?>
                                        <tr>
                                        	<td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['code'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                            <td class="phone-show" style="text-align: center;vertical-align: middle;">
                                            	<?php echo $value['houses_name'];?><br>
                                            	<?php echo $value['houses_area_name'];?><br>
                                            	<?php echo $value['ban'];?><br>
                                            	<?php echo $value['unit'];?><br>
                                            	<?php echo $value['floor'];?><br>
                                            	<?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?>
                                            </td>
                                            <td class="center">
                                            	<?php if(isset($value['image']) && count($value['image']) > 0): ?>
                                                <ul class="ace-thumbnails" media-id="<?php echo $value['id'];?>" id="uploader_front_img<?php echo $key;?>">
                                                    
                                                        <?php foreach($value['image'] as $val):?>
                                                        <li style="margin:0 auto;">
                                                            <a href="<?php echo $val['front_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                                <img style="width: 150px; height: 100px" src="<?php echo $val['front_img'];?>">
                                                            </a>
                                                            <input type="hidden" name="<?php echo $value['id'];?>[front_img][]" value="<?php echo $val['front_img'];?>"/>
                                                        </li>
                                                        <?php endforeach;?>
                                                    
                                                </ul>
                                                <?php else:?>
                                                    <font style="color:red;">还未上传</font>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                
                                <!--分页start-->
                                <?php $this->load->view('common/page');?>
                            </div>
                        </div>


                </div>
            </div>
    

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript">
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

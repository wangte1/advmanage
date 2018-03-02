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
                        <a href="#">资源管理</a>
                    </li>
                    <li class="active">编辑点位</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        <h1>
                            编辑点位
                            <a  href="javascript:history.back(-1);" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回上一页</a>
                        </h1>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="#" method="post" class="form-horizontal" id="add_form" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属媒体： </label>

                                <div class="col-sm-9" style="padding-top: 4px">
                                    <label><?php echo $info['media_name'];?></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属规格： </label>

                                <div class="col-sm-9 specification-group">
                                    <select id="guige" name="specification_id" required="required" class="select2" data-placeholder="Click to Choose...">
                                        <?php
                                        foreach($specifications as $key=>$val){
                                            ?>
                                            <option <?php if($info['specification_id'] == $val['id']){ echo "selected";}?>  value="<?php echo $val['id'];?>"><?php echo $val['name'];?>(<?php echo $val['size'];?>)</option>
                                        <?php } ?>

                                    </select>
                                    <a href="javascript:;" id="xinjian">新建规格</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属城市： </label>

                                <div class="col-sm-9" id="address-info">
                                    <select id="state1" required="required" name="province" class="select2" data-id="state2" data-placeholder="Click to Choose...">
                                        <option value="" >省</option>
                                        <?php
                                        foreach($province as $key=>$val){
                                            ?>
                                            <option <?php if($address[0] == $val['area_name']){ echo "selected";}?> data-code="<?php echo $val['id'];?>" value="<?php echo $val['area_name'];?>"><?php echo $val['area_name'];?></option>
                                        <?php } ?>

                                    </select>
                                    <select id="state2" required="required" name="city" data-id="state3"  class="select2 " data-placeholder="Click to Choose...">
                                        <option value="" >市</option>
                                    </select>
                                    <select id="state3" required="required" name="area" data-id="state4" class="select2" data-placeholder="Click to Choose...">
                                        <option value="" >区</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group" id="tishi" style="display: none">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

                                <div class="col-sm-9" id="address-info">
                                  <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color:red"></span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  街道地址： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="street_address" value="<?php echo isset($address[3])?$address[3]:"";?>" id="address" placeholder="请输入街道地址" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">请输入详细的街道地址</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  坐标（经纬度）： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="coordinate" value="<?php echo $info['coordinate'];?>"  id="coordinate" placeholder="" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">注：坐标必须以逗号“,”分割填写，如果获取不到，<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">点击这里</a>手动拾取坐标</span>
									</span>


                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  腾讯地图坐标（经纬度）： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="tx_coordinate" value="<?php echo $info['tx_coordinate']?>" placeholder="" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle"><a href="http://lbs.qq.com/tool/getpoint/index.html" target="_blank">点击这里</a>手动拾取坐标</span>
									</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  腾讯街景地图id： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="tx_jiejingid" value="<?php echo $info['tx_jiejingid']?>" placeholder="" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle"><a href="http://lbs.qq.com/tool/streetview/index.html" target="_blank">点击这里</a>手动拾取街景id</span>
									</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 点位图： </label>
                                <div class="col-sm-9">

                                    <ul class="ace-thumbnails" id="uploader_cover_img">
                                        <?php if(isset($info['images']) && !empty($info['images'])):?>
                                        <?php foreach (explode(';', $info['images']) as $k => $v):?>
                                        <li id="SWFUpload_0_0" class="pic pro_gre" style="margin-right: 20px; clear: none">
                                            <a data-rel="colorbox" class="cboxElement" href="<?php echo $v?>">
                                            <img src="<?php echo $v?>" style="width: 215px; height: 150px"></a> 
                                            <div class="tools"> 
                                                <a href="javascript:;"> <i class="icon-remove red"></i> </a>
                                            </div>
                                            <input type="hidden" name="cover_img[]" value="<?php echo $v?>">
                                        </li>
                                        <?php endforeach;?>
                                        <?php endif;?>
                                        <li class="pic pic-add add-pic" id="<?php if(isset($info['seal_img'])&&!empty($info['seal_img'])){ echo 'hidden-div';}?>" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                            <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>

                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  点位编号： </label>

                                <div class="col-sm-8">
                                    <input type="text" required="required"  value="<?php echo $info['points_code'];?>"  name="points_code" required="required"  id="points_code" placeholder="请输入点位编号" class="col-xs-10 col-sm-5">
                                    <input type="hidden"  id="points_code_type" value="0">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle"  id="points_code_msg">*</span>
									</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  展现形式： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="show_method" value="<?php echo $info['show_method']?>" placeholder="" class="col-xs-10 col-sm-5">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  广告材质： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="texture" placeholder="" value="<?php echo $info['texture']?>" class="col-xs-10 col-sm-5">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  点位价格： </label>

                                <div class="col-sm-4">
                                    <input type="text" name="price" required="required" value="<?php echo $info['price'];?>"  id="price" placeholder="请输入点位价格" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">元/年 <i id="price_msg" style="font-style: normal"></i></span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>

                                <div class="col-sm-9">
                                    <textarea id="form-field-11" name="remark" placeholder="（选填）备注信息。最多300字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"><?php echo $info['remark'];?></textarea>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="button" id="subbtn">
                                        <i class="icon-ok bigger-110"></i>
                                        保存
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">新增规格</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">规格类型:</label>
                        <select class="form-control" id="type">
                            <option value="">请选择规格类型</option>
                            <?php foreach(C('public.media_type') as $key=>$val){ ?>
                            <option value="<?php echo $key;?>" <?php if(isset($info['type']) && $key == $info['type']) { echo "selected"; }?>><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">规格名称:</label>
                        <input type="text" class="form-control" id="specifications-name" placeholder="请输入规格名称">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">规格大小:</label>
                        <input type="text" class="form-control" id="specifications-size" placeholder="请输入规格大小">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="add">添加</button>
            </div>
        </div>
    </div>
</div>


<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

    <script type="text/javascript">
        $(function(){
            var province_id = '<?php echo $province_id;?>';
            var city_id = '<?php echo $city_id;?>';
            var area_id = '<?php echo $area_id;?>';

            $("#subbtn").click(function(){
                var points_code_type = parseInt($("#points_code_type").val());
                if(points_code_type == 1){
                    $("#points_code").css("border-color","red");
                    $("#points_code_msg").css("color","red").html("编号已经存在,请确保编号唯一性！");
                    return false;
                }

                //判断用户是否选择城市和地区
                if($("#state1").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择身份!");
                    return false;
                }

                if($("#state2").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择城市!");
                    return false;
                }
                if($("#state3").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择地区!");
                    return false;
                }

                if($("#points_code").val() == ""){
                    $("#points_code").css("border-color","red");
                    $("#points_code_msg").css("color","red").html("点位编号不能为空！");
                    return false;
                }

                if($("#price").val() == ""){
                    $("#price").css("border-color","red");
                    $("#price_msg").css("color","red").html("点位价格不能为空！");
                    return false;
                }



                $("#add_form").submit();
            });

            $("#xinjian").click(function(){
                $("#exampleModal").modal('show');
            });


            $(".select2").css('width','230px').select2({allowClear:true})
                .on('change', function(){
                    var _obj = $(this);
                    var str = "----请选择----";
                    getinfo(_obj,str);
                });

            function getinfo(_obj,str){
                var id = _obj.attr("data-id");
                $.ajax( {
                    url:"/points/get_area",
                    data: {
                        'id': _obj.find("option:selected").attr("data-code")
                    },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        html = "";
                        html += '<option value="">'+str+'</option>';

                        if(data.status == 0){
                            for(var i= 0; i<data.data.length;i++){
                                html += ' <option data-code="'+data.data[i]['id']+'" value="'+data.data[i]['area_name']+'">'+data.data[i]['area_name']+'</option>';
                            }

                            $("#"+id).html(html);

                        }
                        else{
                            $("#"+id).html(html);
                        }

                    }

                });
            }

            getinfo1($('#state2'), province_id, city_id);
            getinfo1($('#state3'), city_id, area_id);

            function getinfo1(_obj,id, childid){
                $.ajax( {
                    url:"/points/get_area",
                    data: {
                        'id': id
                    },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        html = "";
                        html += '<option value="">----请选择----</option>';

                        if(data.status == 0){
                            for(var i= 0; i<data.data.length;i++){
                                if (data.data[i]['id'] == childid) {
                                    html += ' <option data-code="'+data.data[i]['id']+'" value="'+data.data[i]['area_name']+'" selected>'+data.data[i]['area_name']+'</option>'; 
                                } else {
                                    html += ' <option data-code="'+data.data[i]['id']+'" value="'+data.data[i]['area_name']+'">'+data.data[i]['area_name']+'</option>';
                                }
                            }
                            _obj.html(html);

                        }
                        else{
                            _obj.html(html);
                        }
                        _obj.css('width','230px').select2({allowClear:true});
                    }

                });
            }
        });
    </script>
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

        // 删除照片
        $("#uploader_cover_img").on("click",'.icon-remove',function(){
            $(this).parents("li").remove();
            $(".add-pic").show();
        });
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>



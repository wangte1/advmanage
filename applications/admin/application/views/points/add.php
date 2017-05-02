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
                    <li class="active">新增点位</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        <h1>
                            新增点位
                            <a  href="/points" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表</a>
                        </h1>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="/points/add" method="post" class="form-horizontal" id="add_form" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属媒体： </label>
                                <div class="col-sm-9">
                                    <select name="media_type" class="select2" data-placeholder="Click to Choose...">
                                        <option value="">请选择媒体类型</option>
                                        <?php foreach(C('public.media_type') as $key=>$val){ ?>
                                        <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php } ?>
                                    </select>
                                    <select id="state" name="media_id" required="required" class="select2" data-placeholder="Click to Choose...">
                                        <option value="">请选择媒体名称</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属规格： </label>

                                <div class="col-sm-9 specification-group">
                                    <select id="guige" name="specification_id" required="required" class="select2" data-placeholder="Click to Choose...">
                                        <option value="">请选择规格</option>
                                    </select>
                                    <a href="javascript:;" id="xinjian">新建规格</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属城市： </label>

                                <div class="col-sm-9" id="address-info">
                                    <select id="state1" required="required" name="province" class="select2 address" data-id="state2" data-placeholder="Click to Choose...">
                                        <option value="" >省</option>
                                        <?php
                                        foreach($province as $key=>$val){
                                            ?>
                                            <option <?php if($val['id'] == "35560"){ echo "selected";}?>  data-code="<?php echo $val['id'];?>" value="<?php echo $val['area_name'];?>"><?php echo $val['area_name'];?></option>
                                        <?php } ?>

                                    </select>
                                    <select id="state2" required="required" name="city" data-id="state3"  class="select2 address" data-placeholder="Click to Choose...">
                                        <option value="" >市</option>
                                        <?php
                                        foreach($city as $key=>$val){
                                            ?>
                                        <option <?php if($val['id'] == "35561"){ echo "selected";}?>  data-code="<?php echo $val['id'];?>" value="<?php echo $val['area_name'];?>"><?php echo $val['area_name'];?></option>
                                        <?php } ?>
                                     </select>
                                    <select id="state3" required="required" name="area" data-id="state4" class="select2 address" data-placeholder="Click to Choose...">
                                        <option value="" >区</option>
                                        <?php
                                        foreach($area as $key=>$val){
                                            ?>
                                            <option  data-code="<?php echo $val['id'];?>" value="<?php echo $val['area_name'];?>"><?php echo $val['area_name'];?></option>
                                        <?php } ?>
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
                                    <input type="text" name="street_address"  id="address" placeholder="请输入街道地址" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">请输入详细的街道地址</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  坐标（经纬度）： </label>

                                <div class="col-sm-8">
                                    <input type="text" name="coordinate"  id="coordinate" placeholder="" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">注：坐标必须以逗号“,”分割填写，如果获取不到，<a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">点击这里</a>手动拾取坐标</span>
									</span>


                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  点位编号： </label>

                                <div class="col-sm-8">
                                    <input type="text" required="required"  name="points_code" required="required"  id="points_code" placeholder="请输入点位编号" class="col-xs-10 col-sm-5">
                                    <input type="hidden"  id="points_code_type" value="0">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle"  id="points_code_msg">*</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  点位价格： </label>

                                <div class="col-sm-4">
                                    <input type="text" name="price" required="required"  id="price" placeholder="请输入点位价格" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">元/日 <i id="price_msg" style="font-style: normal"></i></span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>

                                <div class="col-sm-9">
                                    <textarea id="form-field-11" name="remark" placeholder="（选填）备注信息。最多300字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"></textarea>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="button" id="subbtn">
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


            $(".select2").css('width','230px').select2({allowClear:true});

            $(".address").on('change', function(){
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

            $("select[name='media_type']").change(function(){
                $.post('/points/get_media_spec', {media_type: $(this).val()}, function(data){
                    if (data.flag) {
                        $('select[name="media_id"]').empty();
                        $('select[name="media_id"]').append(data.media_option);

                        $('select[name="specification_id"]').empty();
                        $('select[name="specification_id"]').append(data.spec_option);
                    }
                });
            });
        });
    </script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>



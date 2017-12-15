<!-- 加载公用css -->
<?php $this->load->view('common/header');?>


<div class="main-container" id="main-container">

            <!-- <div class="page-content"> -->
                <!-- <div class="page-header">
                    <a href="/points/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新建点位</a>
                    <a href="javascript:;" class="btn btn-sm btn-primary btn-export"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出</a>
                </div> -->

                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>筛选条件</h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="icon-chevron-up"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">
                                    <form id="search-form" class="form-horizontal" role="form" method="get" action="">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体类型 </label>
                                                <div class="col-sm-9">
                                                    <select id="media_type" name="media_type"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="all" <?php if($media_type == 'all'){ echo "selected"; }?>>全部</option>
                                                        <?php foreach(C('public.media_type') as $key=>$val){ ?>
                                                            <option value="<?php echo $key;?>" <?php if($media_type != 'all' && ($key == $media_type || $key == 1)) { echo "selected"; }?>><?php echo $val;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体名称 </label>
                                                <div class="col-sm-9">
                                                    <select id="media_id" name="media_id"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="">全部</option>
                                                        <?php foreach($medias as $key=>$val){ ?>
                                                            <option <?php if($val['id'] == $media_id){ echo "selected";} ?> value="<?php echo $val['id'];?>"><?php echo $val['name']."(".$val['code'].")";?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 规格 </label>
                                                <div class="col-sm-9">
                                                    <select multiple="" id="spec_id" name="spec_id[]" class="width-60 chosen-select tag-input-style"  data-placeholder="请选择规格">
                                                        <option value="" <?php if(count($spec_id) == 0) { echo 'selected'; }?>>全部</option>
                                                        <?php foreach($specifications as $key=>$val){ ?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($spec_id && in_array($val['id'],  $spec_id)){ echo "selected";} ?> ><?php echo $val['name'];?>(<?php echo $val['size'];?>)</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 占用客户 </label>
                                                <div class="col-sm-9">
                                                    <select id="customer_id" name="customer_id" class="select2" data-placeholder="Click to Choose...">
                                                        <option value="">全部</option>
                                                        <?php
                                                        foreach($customers as $key=>$val){
                                                            ?>
                                                            <option value="<?php echo $val['id'];?>"  <?php if($val['id'] == $customer_id){ echo "selected";} ?> ><?php echo $val['customer_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>
                                                <div class="col-sm-9">
                                                    <select id="point_status" name="point_status"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="">全部</option>
                                                        <?php foreach(C("public.points_status") as $key=>$val){ ?>
                                                            <option <?php if($key == $point_status){ echo "selected";} ?>   value="<?php echo $key;?>" ><?php echo $val;?></option>
                                                        <?php } ?>
                                                     </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 锁定状态 </label>
                                                <div class="col-sm-9">
                                                    <select name="is_lock" class="select2">
                                                        <option value="" <?php if($is_lock == ''){ echo "selected"; }?>>全部</option>
                                                        <option value="0" <?php if($is_lock != '' && $is_lock == 0){ echo "selected"; }?>>未锁定</option>
                                                        <option value="1" <?php if($is_lock != '' && $is_lock == 1){ echo "selected"; }?>>已锁定</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-4 lock-customer" style="<?php if(!isset($_GET['is_lock']) || $_GET['is_lock'] != '1'):?>display:none<?php endif;?>">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 锁定客户 </label>
                                                <div class="col-sm-9">
                                                    <select id="lock_customer_id" name="lock_customer_id" class="select2" data-placeholder="Click to Choose...">
                                                        <option value="">全部</option>
                                                        <?php
                                                        foreach($customers as $key=>$val){
                                                            ?>
                                                            <option value="<?php echo $val['id'];?>"  <?php if($val['id'] == $lock_customer_id){ echo "selected";} ?> ><?php echo $val['customer_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位地址 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="address" value="<?php echo $address;?>" />
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> 即将到期投放点位 </label>
                                                <div class="col-sm-8">
                                                    <label>
                                                        <input type="checkbox"  name="expire_time" class="ace" value="1" <?php if($expire_time == 1) { echo "checked"; }?>/>
                                                        <span class="lbl"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
                                                <button class="btn" type="reset">
                                                    <i class="icon-undo bigger-110"></i>
                                                    重置
                                                </button>
                                                
                                                <button class="btn btn-primary" type="button" onclick="returnVal();">
                                                    <i class="icon-save bigger-110"></i>
                                                    确定
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                            	<th><input type="checkbox"></th>
                                                <th width="8%">点位编号</th>
                                                <th width="8%">媒体新编号</th>
                                                <th width="12%">媒体名称</th>
                                                <th width="8%">媒体类型</th>
                                                <th width="12%">占用客户</th>
                                                <th>规格</th>
                                                <th>点位地址</th>
                                                <th width="9%">锁定状态</th>
                                                <th width="10%">投放时间</th>
                                                <th>状态</th>
                                                <!-- <th width="8%">操作</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($list as $key=>$val){?>
                                            <tr>
                                            	<td><input class="m-check" type="checkbox" value="<?php echo $val['id'];?>"></td>
                                                <td><a href="javascript:;"><?php echo $val['points_code'];?></a></td>
                                                <td><a href="javascript:;"><?php echo $val['new_code'];?></a></td>
                                                <td><?php echo $val['media_name'].'('.$val['media_code'].')';?></td>
                                                <td><?php echo C('public.media_type')[$val['media_type']];?></td>
                                                <td><?php echo $val['customer_name'];?><?php if(isset($project[$val['project_id']])) { echo '-'.$project[$val['project_id']]; } ?></td>
                                                <td><?php echo $val['spec_name'].'（'.$val['size'].'）';?></td>
                                                <td><?php echo $val['address'];?></td>
                                                <td>
                                                    <?php
                                                        if($val['is_lock'] == 1){
                                                            echo "<span class='badge badge-danger'>已锁定</span><br/>（客户：".$customer_name[$val['lock_customer_id']]."）";
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php if($val['release_start_time'] && $val['release_end_time']) { echo $val['release_start_time'].'至'.$val['release_end_time']; } ?></td>
                                                <td>
                                                    <?php 
                                                        switch ($val['point_status']) {
                                                            case '1':
                                                                $class = 'badge-success';
                                                                break;
                                                            case '2':
                                                                $class = 'badge-warning';
                                                                break;
                                                            case '3':
                                                                $class = 'badge-danger';
                                                                break;
                                                        }
                                                    ?>
                                                    <span class="badge <?php echo $class; ?>">
                                                        <?php echo C('public.points_status')[$val['point_status']];?>
                                                    </span>
                                                </td>
                                                <!-- <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a class="green tooltip-info" href="/points/edit/<?php echo $val['id'];?>?per_page=<?php echo $page;?>"  data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>
                                                        <a class="red tooltip-info potints_del" href="javascript:;" data-url="/points/del/<?php echo $val['id'];?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td> -->
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <!--分页-->
                                    <?php $this->load->view("common/page");?>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- </div> -->
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('chosen.jquery.min.js','admin');?>"></script>
<script type="text/javascript">
    $(function(){
        $(".select2").css('width','230px').select2({allowClear:true});
        $(".chosen-select").chosen();
        $('[data-rel=tooltip]').tooltip();
        $(".btn-export").click(function(){
            var media_type = $("select[name='media_type']").val();
            if (media_type == 1 || media_type == 2) {
                $("#search-form").attr('action', '/points/out_excel');
                $("#search-form").submit();
                $("#search-form").attr('action', '');
            } else {
                var d = dialog({
                    title: '提示信息',
                    content: '只允许导出公交和高杆点位表',
                    okValue: '确定',
                    ok: function () {
                    }
                });
                d.width(300);
                d.showModal();
            }
        });

        $("#media_type").change(function(){
            $.post('/points/get_media_spec', {media_type: $(this).val(), search: 1}, function(data){
                if (data.flag) {
                    $('select[name="media_id"]').empty();
                    $('select[name="media_id"]').append(data.media_option);

                    $('#spec_id').html(""); ;
                    $('#spec_id').chosen("destroy");
                    $('#spec_id').empty();
                    $('#spec_id').append(data.spec_option);
                    $("#spec_id").chosen();
                }
            });
        });

        $("select[name='is_lock']").change(function(){
            if ($(this).val() == 1) {
                $(".lock-customer").show();
            } else {
                $(".lock-customer").hide();
                $("select[name='lock_customer_id']").val('');
            }
        });
    });

    function returnVal() {
		var points = [];
		var i = 0;
		$('.m-check:checked').each(function() {
			points[i] = new Object(); 
			points[i].id = $(this).val(); 
			points[i].code = $(this).parent().nextAll('td:eq(0)').text();
			points[i].name = $(this).parent().nextAll('td:eq(2)').text(); 
			points[i].format = $(this).parent().nextAll('td:eq(5)').text(); 
			i++;
		});

		parent.setTable(points);
		var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
		parent.layer.close(index);
// 		console.log(points);
    }
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

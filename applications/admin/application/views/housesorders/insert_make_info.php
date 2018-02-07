<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('bootstrap-timepicker.css', 'admin');?>" rel="stylesheet" />
<style type="text/css">
    #scrollTable table {
      margin-bottom: 0;
    }
    #scrollTable .div-thead {
    }
    #scrollTable .div-tbody{
      width:100%;
      height:450px;
      overflow:auto;
    }
    .table thead>tr>th, .table tbody>tr>th, .table tfoot>tr>th, .table thead>tr>td, .table tbody>tr>td, .table tfoot>tr>td {
        padding: 4px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
        text-align: center;
    }
</style>


<div class="main-container" id="main-container">
    			<div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="form-group page-make-company">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作公司： </label>
                                            <div class="col-sm-9">
                                                <select name="make_company_id" class="select2" required>
                                                    <option value="">请选择制作公司</option>
                                                    <?php foreach($make_company as $val):?>
                                                    <option value="<?php echo $val['id'];?>" <?php if(isset($info['make_company_id']) && $val['id'] == $info['make_company_id']) { echo "selected"; }?>><?php echo $val['company_name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <span class="help-inline form-field-description-block">
                                                   <span class="middle" style="color: red">*</span>
                                                   <?php
                                                        $html = '';
                                                        foreach ($make_company as $value) {
                                                            $html .= '<b>'.$value['company_name'].'</b>:';
                                                            $html .= $value['business_scope'].'<br/>';
                                                        }
                                                   ?>
                                                   <a href="javascript:;" data-rel="popover" title="如何选择制作公司？" data-trigger="hover" data-content="<?php echo $html; ?>"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作要求： </label>
                                            <div class="col-sm-8">
                                            	<textarea class="form-control" name="make_requirement" rows="5" placeholder="请填写制作要求"><?php if(isset($info['make_requirement'])) { echo $info['make_requirement'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group page-make-fee">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 制作费用： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="make_fee" rows="5" placeholder="请填写制作费用要求"><?php if(isset($info['make_fee'])) { echo $info['make_fee'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group page-make-complete-time">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 制作完成时间： </label>
                                            <div class="col-sm-8">
                                                <div class="col-sm-6 input-group date datepicker" style="padding-left: 0">
                                                    <input class="form-control date-picker" type="text" name="make_complete_time" value="<?php if(isset($info['make_complete_time'])){ echo date('Y-m-d', strtotime($info['make_complete_time']));} else { echo date('Y-m-d',strtotime('+1 day')); }?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                                <div class="col-sm-6 input-group bootstrap-timepicker">
                                                    <input id="timepicker1" type="text" class="form-control" value="<?php if(isset($info['make_complete_time'])){ echo date('H:i:s', strtotime($info['make_complete_time']));}?>" />
                                                    <span class="input-group-addon">
                                                        <i class="icon-time bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 是否打小样： </label>
                                            <div class="col-sm-9">
                                                <label class="blue">
                                                    <input disabled="disabled" name="is_sample" value="1" type="radio" class="ace" <?php if((isset($info['is_sample']) && $info['is_sample'] == 1)){ echo "checked"; }?> />
                                                    <span class="lbl"> 是</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input disabled="disabled" name="is_sample" value="0" type="radio" class="ace" <?php if(isset($info['is_sample']) && $info['is_sample'] == 0 || !isset($info['is_sample'])){ echo "checked"; }?>>
                                                    <span class="lbl"> 否</span>
                                                </label>
                                                &nbsp;
                                                <input type="text" name="sample_color" placeholder="请填写小样颜色" value="<?php if(isset($info['sample_color'])) { echo $info['sample_color']; }?>" <?php if(isset($info['is_sample']) && $info['is_sample'] == 0):?> style="display: none" <?php endif;?> />
                                            </div>
                                        </div>
                                        
                                        <!-- <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 委托内容： </label>
                                            <div class="col-sm-9">
                                                <label class="blue">
                                                    <input name="leave_content" value="1" type="radio" class="ace" <?php if((isset($info['leave_content']) && $info['leave_content'] == 1) || !isset($info['leave_content'])){ echo "checked"; }?> />
                                                    <span class="lbl"> 仅制作</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input name="leave_content" value="2" type="radio" class="ace" <?php if((isset($info['leave_content']) && $info['leave_content'] == 2) || (!isset($info['leave_content']))){ echo "checked"; }?>>
                                                    <span class="lbl"> 制作及安装</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group page-is-sample">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 安装类型： </label>
                                            <div class="col-sm-9">
                                                <label class="blue">
                                                    <input name="install_type" value="1" type="radio" class="ace" <?php if((isset($info['install_type']) && $info['install_type'] == 1) || !isset($info['install_type'])){ echo "checked"; }?> />
                                                    <span class="lbl"> 覆盖</span>
                                                </label>
                                                &nbsp;
                                                <label class="blue">
                                                    <input name="install_type" value="2" type="radio" class="ace" <?php if((isset($info['install_type']) && $info['install_type'] == 2) || (!isset($info['install_type']))){ echo "checked"; }?>>
                                                    <span class="lbl"> 替换原画</span>
                                                </label>
                                            </div>
                                        </div> -->

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remark" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div style="text-align:center;">
                                                <button class="btn btn-info btn-save" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    保 存
                                                </button>
                                              
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<!-- <script src="<?php echo css_js_url('order.js','admin');?>"></script> -->
<script type="text/javascript">
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});
    
    $('#timepicker1').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime: '18:00:00'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
	
  	
  	//保存
    $(".btn-save").click(function(){
        var point_ids = $("input[name='point_ids']").val();
        if (point_ids == '') {
            var d = dialog({
                title: '提示信息',
                content: '您还没有选择点位哦！',
                okValue: '确定',
                ok: function () {

                }
            });
            d.width(320);
            d.showModal();
            return false;
        }
    });

    
})
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

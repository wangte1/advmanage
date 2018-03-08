<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('webuploader.css', 'common');?>" rel="stylesheet" />
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
    .close{
	    display: block;
        background: url("/static/admin/images/close.png") no-repeat;
        width: 22px;
        height: 22px;
        position: absolute;
        top: 6px;
        right: 8px;
    	z-index:99;
    }
</style>
<style>
    #uploader_cover_img img, .add-pic{ width: 150px; height: 150px;}
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
                        <a href="#">意向订单管理</a>
                    </li>
                    <li>
                        <a href="/confirm_reserve">预定订单待确认列表</a>
                    </li>
                    <li>
                        <a href="/confirm_reserve/detail/<?php echo $order_id;?>">已确认点位</a>
                    </li>
                    
                    <li class="active">客户确认预约订单</li>
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
                                                              客户确认预约订单
                        <a href="/houseswantorders" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                          
                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <div class="space-4"></div>
										<?php if(isset($orderInfo)):?>
                                        <input type="hidden" name="id" value="<?php echo $orderInfo['id']?>" />
                                        <?php endif;?>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 客户： </label>
                                            <div class="col-sm-10">
                                            	<span><?php echo $customer['name']?></span>
                                                <input type="hidden" name="lock_customer_id" value="<?php echo $customer['id']?>" readonly class="required">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 联系人： </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="contact_person" value="<?php echo $customer['contact_person']?>" readonly class="required">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放开始时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" type="text" name="lock_start_time" value="<?php if(isset($orderInfo['lock_start_time'])){ echo $orderInfo['lock_start_time'];}?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 投放结束时间： </label>
                                            <div class="col-sm-5">
                                                <div class="input-group date datepicker">
                                                    <input class="form-control date-picker" type="text" name="lock_end_time" value="<?php if(isset($orderInfo['lock_end_time'])){ echo $orderInfo['lock_end_time'];}?>" data-date-format="dd-mm-yyyy" required>
                                                    <span class="input-group-addon">
                                                        <i class="icon-calendar bigger-110"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        	<label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"> 点位签字照片： </label>
                                            <div class="col-sm-5">
                                            	<div class="row-fluid">
                                                    <ul class="ace-thumbnails" id="uploader_cover_img" data='0'>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        	<label class="col-sm-2 control-label no-padding-right" for="form-input-readonly"></label>
                                        	<div style="margin-left: 5%;" id="file_cover_img">选择图片</div>
                                        </div>
                                        

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 备注： </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="remarks" rows="5" placeholder="备注信息，最多300个字"><?php if(isset($orderInfo['remarks'])) { echo $orderInfo['remarks'];}?></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix form-actions">
                                            <div style="text-align: center;">
                                                <button class="btn btn-info btn-save" type="submit">
                                                    <i class="icon-ok bigger-110"></i>
                                                    	提交
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
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<script src="<?php echo css_js_url('bootstrap-timepicker.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('webuploader.js','common');?>"></script>
<script type="text/javascript"> 
$(function(){
	$('#timepicker1').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime: '18:00:00'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
});
$("#distpicker1").distpicker({
	province: '贵州省',
	city: '贵阳市',
	//district: ''
});

function get_checkbox(){
	
    obj = document.getElementsByName("s_houses_type");
    check_val = [];
    for(k in obj){
        if(obj[k].checked)
            check_val.push(obj[k].value);
    }
	$('#houses_type').val(check_val.toString());
    return check_val.toString();
    
}
                                                    
$(function(){
	
	$('.popover-lock').popover({html:true, placement:'bottom'});
    $('[data-rel=popover]').popover({html:true});

    $(".select2").css('width','220px').select2({allowClear:true});

	
	$('#province, #city, #area, .m-checkbox, #begin_year, #end_year, #put_trade, .m-radio').change(function(){

		var province = $('#province').val();
		var city = $('#city').val();
		var area = $('#area').val();
		var houses_type = get_checkbox();	//获取楼盘类型
		var begin_year = $('#begin_year').val();
		var end_year = $('#end_year').val();
		var put_trade = $('#put_trade').val();
		var order_type = $('.m-radio:checked').val();

		var postData = {province:province, city:city, area:area, houses_type:houses_type, begin_year:begin_year, end_year:end_year, put_trade:put_trade,order_type:order_type};

		$.post('/houseswantorders/get_points', postData, function(data){
			if(data.flag == true && data.count > 0) {
				var pointStr = '';
				$("#all_points_num").text(data.count);
				for(var i = 0; i < data.houses_lists.length; i++) {
					pointStr += "<tr><td width='20%'>"+(i+1)+"</td>";
					pointStr += "<td width='60%'>"+data.houses_lists[i]['houses_name']+"</td>";
					pointStr += "<td width='20%'>"+data.houses_lists[i]['count']+"</td>";
				}
			}else{
				alert('暂无空闲点位');
			}

			$("#points_lists").html('');
			$("#points_lists").html(pointStr);
		});
		
	});

  	//保存
    $(".btn-save").click(function(){
    	if($('#customer_id').val() == '') {
			alert('请选择客户');
			return;
        }

    	if($('#points_count').val() == '') {
			alert('请输入预定点位数量');
			return;
        }
    });

    function alert(msg){
    	var d = dialog({
            title: '提示信息',
            content: msg,
            okValue: '确定',
            ok: function () {

            }
        });
        d.width(320);
        d.showModal();
    }
});

$('body').on('click', '.close', function(){
	$(this).parent().remove();
});


var baseUrl = "<?php echo $domain['admin']['url'];?>";
//初始化Web Uploader
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // 文件接收服务端。
    server: baseUrl+"/file/upload?dir=image",
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick:{
        id:$("#file_cover_img"), // id
        multiple: false  // false  单选 
    },
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    },
    method:'POST',
});

var _this;
var html;
//当有文件添加进来的时候
uploader.on( 'fileQueued', function( file ) {
    uploader.makeThumb( file, function( error, src ) {
        if ( error ) {
            layer.alert('不能预览');
            return;
        }
        html = "";//初始化
        _this = parseInt($('#uploader_cover_img').attr('data'));
        
        html += '<li id="uploader_cover_img_'+ _this +'" style="float: left;width: 150px;height: 150px;clear:none; border: 1px solid #f18a1b">';
		html +=     '<a class="close" href="javascript:;"></a>'
        html +=		'<a href="javascript:;" class="up-img">';
        html +=			'<img style="position: absolute;top: 0;" src="'+src+'">';
        html += 	'</a>';
    	html += '</li>';
    	
		$('#uploader_cover_img').append(html);
		$('#uploader_cover_img').attr('data', (_this+1));
    }, 150, 150 );
});
//文件上传成功
uploader.on( 'uploadSuccess', function( file, res ) {
	if(res.error == 0){
		$('#uploader_cover_img_'+_this).append('<input type="hidden" name="confirm_img[]" value="'+res.url+'">');
		$('#uploader_cover_img').append();
	}else{
		$('#uploader_cover_img_'+_this).remove();
		layer.msg('图片上传失败');
	}
});

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

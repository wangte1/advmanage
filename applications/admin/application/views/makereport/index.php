<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>
<style>
#table th,td{
	 text-align:center; /*设置水平居中*/
     vertical-align:middle;/*设置垂直居中*/
     cursor:pointer
}

/*#table td:hover{
	 background-color: green;
}*/

.font-red {
	color: red;
}

.row div {
	margin-top: 10px;
}

.m-label {
	padding-top: 15px;
	text-align: right;
}

.m-img img{
	width:100px;
	heigh:80px;
}


</style>


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
                        <a href="#">户外资源管理</a>
                    </li>
                    <li class="active">生成验收报告</li>
                </ul>

            </div>
			
            <div class="page-content">
            	<form id="m-form" method="post" enctype="multipart/form-data" action="makereport/submit" >
            	<div class="page-header">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="doSubmit();"><i class="fa fa-download" aria-hidden="true"></i> 生成验收报告</a>
                </div>
            
            	<div class="row">
            		
                    <div class="col-sm-6">
	                    <label class="col-sm-3 control-label no-padding-right m-label" for="form-field-1"> 甲方： </label>
	                    <div class="col-sm-9">
		                    <input type="text" class="form-control" id="first" name="first">
	                    </div>
                    </div>
                    
                    <div class="col-sm-6">
	                    <label class="col-sm-3 control-label no-padding-right m-label" for="form-field-1"> 乙方： </label>
	                    <div class="col-sm-9">
		                    <input type="text" class="form-control" id="second" name="second" value="贵阳大视传媒有限公司">
	                    </div>
                    </div>
                    
                    <div class="col-sm-6">
	                    <label class="col-sm-3 control-label no-padding-right m-label" for="form-field-1"> 媒体类型： </label>
	                    <div class="col-sm-9">
		                    <select id="media_type" name="media_type"  class="form-control" onchange="setRemark(this);">
			                    <option value="1">公交灯箱</option>
			                    <option value="2">户外高杆</option>
			                    <option value="3">机场LED</option>
			                    <option value="4">火车站LED</option>
		                    </select>
	                    </div>
                    </div>
                    
                    <div class="col-sm-6">
	                    <label class="col-sm-3 control-label no-padding-right m-label" for="form-field-1"> 选择点位： </label>
	                    <div class="col-sm-9">
						    <button class="btn btn-sm btn-default" type="button" onclick="selectPoints();"><i class="icon-search"></i></button>
	                    </div>
                    </div>
                    
                    <div class="col-xs-6">
                    	<label class="col-sm-3 control-label no-padding-right m-label" for="form-field-1"> 备注： </label>
	                    <div class="col-sm-9">
		                    <textarea class="form-control" rows="4" id="remark" name="remark">备注：本次甲方共选XX套公交站台灯箱广告，其中中灯箱XX套，小灯箱XX套。我司按照双方签订的户外广告发布合同要求于XXX年XX月XX日开始制作、安装广告画面，于XXXX年XX月XX日按时按量完成XX套公交站台灯箱广告的发布，投放时间为XXXX.XX.XX-XXXX.XX.XX，现将验收照片发给甲方确认。</textarea>
	                    </div>
                    </div>
                    
                    <div class="col-xs-12">
                    	<table id="table" class="table table-striped table-bordered table-hover">
                        	<thead>
                            	<tr>
                            		<th>序号</th>
	                                <th>点位编号</th>
	                                <th>媒体名称</th>
	                                <th>规格</th>
	                                <th>正面图片</th>
	                                <th>背面图片</th>
	                                <th>操作</th>
                            	</tr>
                        	</thead>
                            <tbody id="t-content">
                            </tbody>
                        </table>
                    	
                    </div>
            
                </div>
                
                </form>
            </div>
        </div>
    </div>
</div>

<script>

var parent_points;

function selectPoints() {
	$typeVal = $('#media_type').val();
	layer.open({
		  type: 2,
		  title: '选择点位',
		  shadeClose: true,
		  shade: 0.8,
		  area: ['1000px', '600px'],
		  content: 'selectpoints/index?media_type='+$typeVal //iframe的url
		}); 
	
}

function setTable(points) {
	if(parent_points) {
		parent_points = $.merge(parent_points, points);
	}else {
		parent_points = points;
	}
	
	var contentStr = '';
	var j = $('#t-content').children().length;
	
	for(var i = 0; i < points.length; i++) {
		contentStr += '<tr><td>'+(i+j+1)+'</td>';
		contentStr += '<td>'+points[i].code+'</td>';
		contentStr += '<td><input name="name[]" type="text" value="'+points[i].name+'">'+points[i].name+'</td>';
		contentStr += '<td><input name="format[]" type="text" value="'+points[i].format+'">'+points[i].format+'</td>';
		contentStr += '<td><div class="m-img" id="fpreview_'+(i+j)+'"></div><input class="myfile" name="fimg[]" type="file" onchange="preview(1, this, '+(i+j)+')"></td>';
		contentStr += '<td><div class="m-img" id="bpreview_'+(i+j)+'"></div><input class="myfile" name="bimg[]" type="file" onchange="preview(2, this, '+(i+j)+')"></td>';
		contentStr += '<td><button class="btn btn-danger btn-xs" onclick="del(this,'+points[i].id+');">删除</button></td></tr>';
	}

	$('#t-content').append(contentStr);
	console.log(parent_points);
 	
}

function del(obj,id) {
	$(obj).parent().parent().remove();
	for(var i = 0; i < parent_points.length; i++) {
		if(parent_points[i].id == id) {
			parent_points.splice(i,1);//清空数组 
			break;
		}
	}
	
	console.log(parent_points);
}

function preview(num, file, i) {
	if(num == 1) {
		var prevDiv = document.getElementById('fpreview_'+i);
	}else {
		var prevDiv = document.getElementById('bpreview_'+i);
	}
    
    if (file.files && file.files[0]) {
      var reader = new FileReader();
      reader.onload = function(evt) {
        prevDiv.innerHTML = '<img src="' + evt.target.result + '" />';
      }
      reader.readAsDataURL(file.files[0]);
    } else {
      prevDiv.innerHTML = '<div class="img" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';
    }
}

function setRemark(obj) {

		
	var mediaType = $(obj).val();
	var remarkStr = '';
	
	switch(mediaType) {
		case '1':
			remarkStr = '备注：本次甲方共选XX套公交站台灯箱广告，其中大灯箱XX套。我司按照双方签订的户外广告发布合同要求于XXXX年XX月XX日开始制作、安装广告画面，于XXXX年XX月XX日按时按量完成XX套公交站台灯箱广告的发布，投放时间为XXXX.XX.XX-XXXX.XX.XX，现将验收照片发给甲方确认。';
			break;
		case '2':
			remarkStr = '备注：本次甲方共选XX根户外高杆。我司按照双方签订的户外广告发布合同要求于XXXX年XX月XX日开始制作、安装广告画面，于XXXX年XX月XX日按时按量完成XX根户外高杆的广告投放，投放时间为XXXX.XX.XX-XXXX.XX.XX，现将验收照片发给甲方确认。';
			break;
		default:
			remarkStr = '备注：我司按照约定要求于XXXX年XX月XX日在机场大屏为甲方发布广告，投放时间为XXXX.XX.XX-XXXX.XX.XX，并将验收照片发给甲方确认。';
			break;
	}

	$('#remark').text(remarkStr);
}

function doSubmit() {
	
	var first = $('#first').val();
	if($.trim(first) == '') {
		alert('甲方不能为空');
		return;
	}

	var second = $('#second').val();
	if($.trim(second) == '') {
		alert('乙方不能为空');
		return;
	}

	var remark = $('#remark').val();
	if($.trim(remark) == '') {
		alert('备注不能为空');
		return;
	}

	var tcontent = $('#t-content').html();
	if($.trim(tcontent) == '') {
		alert('请选择点位');
		return;
	}

	var flag = false;
	$(".myfile").each(function(){
	    　　if($(this).val() == "") {
	    	flag = true;
	    　　} 
	}); 

	if(flag == true) {
		alert('请上传正面或背面图片');
	    return;
	}
	
	$('form').submit();
}
</script>
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
<script>
</script>

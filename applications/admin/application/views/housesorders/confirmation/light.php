<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>灯箱广告验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css"> 
        html, body {width: 100%; height: 90%; margin: 0; padding: 0; font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;background: "#fff"}
        .content {width: 1000px; margin: 0 auto; padding: 10px;}
        .title {font-size: 38px; font-weight: bold; margin-top: 10px;clear: both}
        .page-p {font-size:24px;height:20px}
        .mid-p {height: 100px;}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info tr td, .detail-info th {border: 1px solid black;height: 40px;font-size: 18px;padding-left: 20px;text-align: center;}

        .detail-info-print {width: 1000px; border-collapse: collapse;border-spacing: 0;}
        .detail-info-print tr td, .detail-info-print th {border: 1px solid black;height: 40px;font-size: 18px;padding-left: 20px;text-align: center; border-top: 0}
        
        .header {padding:10px; width: 984px}
        .header .logo {width:167px; float: left}
        .header .contact-text {height: 60px; border-left: 3px solid #A7C0DE; float: left; padding-left: 20px; color: #A7C0DE; font-weight: bolder}
        .header p {height: 12px;}
        .footer {margin-top: 10px; padding: 10px; clear: both; color:#D2D2D2;width: 984px}
        .footer p{height: 10px;}

        .btn-print {width: 100%;margin-top: 50px; text-align: right; position: fixed; bottom: 0;left: 0;}
        .btn-print2 {width: 100%;margin-top: 50px; text-align: right; position: fixed; bottom: 50px;left: 0;}
        .btn-print button  {border-radius: 3px; width: 100px; height: 30px;}
        .btn-print2 button  {border-radius: 3px; width: 100px; height: 30px;}

        .hide{display:none}
        @media print { 
            .mid-p {height: 200px;}
            .sign-h {height: 900px;}
            .set-top {border-top: 1px solid black;}
            .pageBreak{ page-break-after: always;} /*强制换页的关键*/
            .noprint{display:none;}
            div.page{page-break-after: always;/*page-break-inside: avoid;*/}
            .hide{display:table-header-group !important}
            .page-h{height: 1250px}
            .first-h,.last-h{height: 1200px}
        }
    </style>
    
    <script src="<?php echo css_js_url('jquery-2.0.3.min.js','admin');?>"></script> 
	<script src="<?php echo css_js_url('html2canvas.js','admin');?>"></script>
	<script src="<?php echo css_js_url('jsPdf.debug.js','admin');?>"></script>
	<script> 
	  
	$(function(){ 
		var count = parseInt('<?php echo ceil(count($points)/100);?>');
        $("#pdf-btn").click(function(){
            	window.open("/housesorders/confirmations?id=<?php echo $id;?>");return;
                html2canvas($('#pic-panel'), { 
                    onrendered: function(canvas) {
                    	var imgData = canvas.toDataURL('image/jpeg');
                        var img = new Image();
                        img.src = imgData;
                        //根据图片的尺寸设置pdf的规格，要在图片加载成功时执行，之所以要*0.225是因为比例问题
                        img.onload = function() {
                            //此处需要注意，pdf横置和竖置两个属性，需要根据宽高的比例来调整，不然会出现显示不完全的问题
                            if (this.width > this.height) {
                            	var doc = new jsPDF('l', 'mm', [this.width * 0.225, this.height * 0.225]);
                            } else {
                            	var doc = new jsPDF('p', 'mm', [this.width * 0.225, this.height * 0.225]);
                            }
                            doc.addImage(imgData, 'jpeg', 0, 0, this.width * 0.225, this.height * 0.225);
                            //根据下载保存成不同的文件名
                            doc.save('report_pdf_.pdf');
                        }
                      },
                      background: "#fff",
                      //这里给生成的图片默认背景，不然的话，如果你的html根节点没设置背景的话，会用黑色填充。
                      allowTaint: true //避免一些不识别的图片干扰，默认为false，遇到不识别的图片干扰则会停止处理html2canvas
                      
                });
        }); 
	}); 
	</script> 
    
</head>
<body>
    <div class="content" id="container" style="background-color:#fff;">
        <!-- 第一页 -->
        <div class="page" id="page">
            <center class="title"><?php echo $order_type_text[$info['order_type']];?>广告验收报告</center>
            <p class="page-p"><span style="font-weight: bolder;">甲方（委托方）：<?php echo $info['customer_name'];?></span></p>
            <p class="page-p"><span style="font-weight: bolder">乙方（承办方）：</span><span style="border-bottom: 1px solid black;">贵州大视传媒有限公司</span></p>
            <p class="page-p">广告牌上画发布地点、数量及规格：</p>
            <table class="detail-info">
                <thead>
                    <th width="20%">编号</th>
                    <th width="40%">点位地址</th>
                    <th width="40%">广告规格</th>
                </thead>
            </table>
            <?php $num = 1;?>
            <?php foreach($points as $key => $value):?>
            <?php  //if($key < 25): ?>
            <table class="detail-info-print">
                <tbody>
                    <tr>
                        <td width="20%"><?php echo $num ++;?></td>
                        <td width="40%"><?php echo $value['houses_name'].$value['houses_area_name'].$value['ban'].$value['unit'].$value['floor'].'楼'?></td>
                        <td width="40%"><?php echo $value['size'];?></td>
                    </tr>
                </tbody>
            </table>
            <?php //endif;?>
            <?php endforeach;?>

            <!-- 第一页的点位条数不足17条时，备注和签名放在本页打印 -->
            <?php if(count($points) > 0 && count($points) <= 17):?>
            <p class="page-p" style="line-height: 40px">备注：本次甲方共选<?php echo count($points);?>套<?php echo $order_type_text[$info['order_type']];?>广告，其中<?php //echo $str;?>。我司按照双方签订的户外广告发布合同要求于<?php echo date('Y年m月d日', strtotime($info['make_complete_time']));?>开始制作、安装广告画面，于<?php echo $complete_date;?>按时按量完成<?php echo count($points);?>套<?php echo $order_type_text[$info['order_type']];?>广告的发布，投放时间为<?php echo date('Y.m.d', strtotime($info['release_start_time']));?>-<?php echo date('Y.m.d', strtotime($info['release_end_time']));?>，现将验收照片发给甲方确认。</p>
            <p class="mid-p"></p>
            <p class="page-p"><span style="font-weight:bolder">甲方（盖章）：</span><span style="font-weight:bolder;margin-left:400px">乙方（盖章）：</span></p>
            <p class="page-p"><span style="font-weight:bolder">确认人（签字）：</span><span style="font-weight:bolder;margin-left:376px">确认人（签字）：</span></p>
            <p class="page-p"><span style="font-weight:bolder">日期：</span><span style="font-weight:bolder;margin-left:496px">日期：</span></p>
            <?php endif;?>
        </div>

        <!-- 验收图片 -->
        <div id="pic-panel" style="background-color:#fff;">
		<table class="detail-info">
			<thead>
             	<th width="10%">序号</th>
             	<th width="10%">点位编号</th>
               	<th width="30%">点位地址</th>
               	<th width="50%">广告图</th>
			</thead>
		</table>
 		<?php $num = 1;?>
     	<?php foreach($points as $key => $value):?>
       	<table class="detail-info-print">
           	<tbody>
               	<tr>
            		<td width="10%"><?php echo $num ++;?></td>
            		<td width="10%"><?php  echo $value['code'];?></td>
           			<td width="30%"><?php echo $value['houses_name'].$value['houses_area_name'].$value['ban'].$value['unit'].$value['floor']?></td>
         			<?php if(!empty($value['img'])):?>
         			<td width="50%"><img style="width:450px;height:300px;" src="<?php echo $value['img'];?>"></td>
           			<?php else:?>
           			<td width="50%"></td>
           			<?php endif;?>
           		</tr>
        	</tbody>
        </table>
        <?php endforeach;?>
        </div>
        <div class="noprint btn-print2"><button type="button" onclick="document.getElementById('pic-panel').style.display='none';javascript: window.print();document.getElementById('pic-panel').style.display='block';">打印文字报告</button></div>
        <div class="noprint btn-print"><button id="pdf-btn" type="button" >导出图片报告</button></div>
    </div>
</body>
</html>

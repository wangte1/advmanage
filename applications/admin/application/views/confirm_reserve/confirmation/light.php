<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>灯箱广告验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css"> 
        html, body {width: 100%; height: 90%; margin: 0; padding: 0; font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;}
        .content {width: 1000px; margin: 0 auto; padding: 10px;}
        .title {font-size: 34px; font-weight: bold; margin: 0 10px;clear: both}
        .page-p {font-size:24px;height:20px}
        .mid-p {height: 100px;}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info tr td, .detail-info th {border: 1px solid black;height: 40px;font-size: 18px;padding:0 10px;text-align: center;}

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
	  $("#pdf-btn").click(function(){ 
	    html2canvas($("#pic-panel"), { 
	      onrendered: function(canvas) { 
	    	  var contentWidth = canvas.width;
	          var contentHeight = canvas.height;

	          //一页pdf显示html页面生成的canvas高度;
	          var pageHeight = contentWidth / 592.28 * 841.89;
	          //未生成pdf的html页面高度
	          var leftHeight = contentHeight;
	          //页面偏移
	          var position = 0;
	          //a4纸的尺寸[595.28,841.89]，html页面生成的canvas在pdf中图片的宽高
	          var imgWidth = 595.28;
	          var imgHeight = 592.28/contentWidth * contentHeight;

	          var pageData = canvas.toDataURL('image/jpeg', 1.0);

	          var pdf = new jsPDF('', 'pt', 'a4');

	          //有两个高度需要区分，一个是html页面的实际高度，和生成pdf的页面高度(841.89)
	          //当内容未超过pdf一页显示的范围，无需分页
	          if (leftHeight < pageHeight) {
	          pdf.addImage(pageData, 'JPEG', 0, 0, imgWidth, imgHeight );
	          } else {
	              while(leftHeight > 0) {
	                  pdf.addImage(pageData, 'JPEG', 0, position, imgWidth, imgHeight)
	                  leftHeight -= pageHeight;
	                  position -= 841.89;
	                  //避免添加空白页
	                  if(leftHeight > 0) {
	                    pdf.addPage();
	                  }
	              }
	          }

	          pdf.save('<?php echo $info['customer_name'];?>-<?php echo $order_type_text[$info['order_type']];?>广告验收报告.pdf');
	      } 
	    }); 
	  }); 
	}); 
	</script> 
    
</head>
<body>
    <div class="content" id="container" style="background-color:#fff;">
    	<table class="detail-info">
            <tbody>
            	<tr>
            		<td colspan="4"><center class="title">大视传媒社区广告定版单</center></td>
            	</tr>
            	<tr>
            		<td nowrap><label style="padding: 0 40px;">客户单位</label></td>
            		<td><?php echo $info['customer_name'];?></td>
            		<td>委托单位</td>
            		<td>贵州时代纵广传媒有限公司</td>
            	</tr>
            	<tr>
            		<td>投放位置</td>
            		<td rowspan="2" colspan="3" style="padding:0;">
            			<table class="detail-info" style="margin:0;padding:0;border:none;">
				            <tbody>
				            	<tr>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">广告形式</td>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">规格</td>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">数量</td>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">投放日期</td>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">天数</td>
				            		<td style="border-left:none;border-top:none;border-bottom:none;">刊例单价（元）</td>
				            		<td style="border:none;">实收金额</td>
				            	</tr>
				            	
				            	<tr>
				            		<td style="border-left:none;border-bottom:none;">冷光灯箱</td>
				            		<td style="border-left:none;border-bottom:none;">45cm*45cm</td>
				            		<td style="border-left:none;border-bottom:none;">20</td>
				            		<td style="border-left:none;border-bottom:none;">2018.3.5-2018.4.5</td>
				            		<td style="border-left:none;border-bottom:none;">30</td>
				            		<td style="border-left:none;border-bottom:none;">9000/块/月</td>
				            		<td style="border-left:none;border-right:none;border-bottom:none;">130000</td>
				            	</tr>
				            </tbody>
				       </table>
            		</td>
            	</tr>
            	
            	<tr>
            		<td nowrap>灯箱广告</td>
            	</tr>
            	
            	<tr>
            		<td>广告发布金额</td>
            		<td colspan="3"></td>
            	</tr>
            	
            	<tr>
            		<td>实际付款金额</td>
            		<td colspan="3"></td>
            	</tr>
            	
            	<tr>
            		<td>付款方式</td>
            		<td></td>
            		<td>付款日期</td>
            		<td></td>
            	</tr>
            	
            	<tr>
            		<td>委托单位经办人</td>
            		<td></td>
            		<td>联系电话</td>
            		<td></td>
            	</tr>
            	
            	<tr>
            		<td>定版单位签字（盖章）</td>
            		<td></td>
            		<td>定版日期</td>
            		<td></td>
            	</tr>
            	
            	<tr>
            		<td>备注</td>
            		<td colspan="3"></td>
            	</tr>
            	
            	<tr>
            		<td rowspan="6">广告说明</td>
            		<td colspan="3" style="text-align: left;">1.下单方必须如实完整填写并确认订单内容，并承担因此产生的法律责任。</td>
            	</tr>
            	
            	<tr>
            		<td colspan="3" style="text-align: left;">2.《点位表》作为该表的附件，下单方也必须确认，并承担因此产生的法律责任。</td>
            	</tr>
            	
            	<tr>
            		<td colspan="3" style="text-align: left;">3.广告内容必须符合《中华人名共和国广告法》及有关的行政法规。广告内容如有不妥之处发布方有权拒绝投放。</td>
            	</tr>
            	
            	<tr>
            		<td colspan="3" style="text-align: left;">4.如遇重大事件，在无法通知客户的情况下，发布方有权对该广告进行位置调整或停止投放。</td>
            	</tr>
            	
            	<tr>
            		<td colspan="3" style="text-align: left;">5.该定版单原件、复印件和传真件同等有效。</td>
            	</tr>
            	
            	<tr>
            		<td colspan="3" style="text-align: left;">6.素材截止事件为投放日前3-5个工作日，请及时提交素材。因素材提交延迟而影响广告投放，责任自负。</td>
            	</tr>
            	
            	
            </tbody>
       </table>
    
        
        <div class="noprint btn-print2"><button type="button" onclick="document.getElementById('pic-panel').style.display='none';javascript: window.print();document.getElementById('pic-panel').style.display='block';">下载定版单</button></div>
    </div>
</body>
</html>

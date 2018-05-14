<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>灯箱广告验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
	<!-- 加载公用css -->
	<?php $this->load->view('common/header');?>
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
        .detail-info,.detail-info-print{width:100%;}
        .mypage{cursor: pointer;}
    </style>
    
    <script src="<?php echo css_js_url('jquery-2.0.3.min.js','admin');?>"></script> 
	<script src="<?php echo css_js_url('html2canvas.js','admin');?>"></script>
	<script src="<?php echo css_js_url('jsPdf.debug.js','admin');?>"></script>
	<script> 
	  
	$(function(){ 

        $("#pdf-out").click(function(){
            	var id = '<?php echo $page;?>';
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
                            doc.save('<?php echo $info["customer_name"];?>-<?php echo $order_type_text[$info["order_type"]];?>广告验收报告-'+id+'.pdf');
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
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <?php $num = ceil(count($points_lists));?>
            <?php for ($i=1; $i<=$num; $i++):?>
            <li <?php if($i==$page){echo 'class="active"';}?>><a href="/housesorders/confirmations?id=<?php echo $id;?>&page=<?php echo $i?>" class="mypage" data="<?php echo $i;?>">第<?php echo $i;?>份</a></li>
            <?php endfor;?>
          </ul>
        </nav>
        <button id="pdf-out" data="<?php echo $page?>" type="button" >导出第<?php echo $page;?>份图片报告</button>
        <!-- 验收图片 -->
        <?php foreach ($points_lists as $k => $v):?>
        <?php if($k == ($page-1)):?>
        <div id="pic-panel" style="background-color:#fff;">
		<table class="detail-info">
			<thead>
             	<th width="10%">序号</th>
             	<th width="10%">点位编号</th>
               	<th width="30%">点位地址</th>
               	<th width="50%">广告图</th>
			</thead>
		</table>
 		<?php $num = ($k*100)+1;?>
     	<?php foreach($v as $key => $value):?>
       	<table class="detail-info-print">
           	<tbody>
               	<tr>
            		<td width="10%"><?php echo $num ++;?></td>
            		<td width="10%"><?php  echo $value['code'];?></td>
           			<td width="30%"><?php echo $value['houses_name'].$value['houses_area_name'].$value['ban'].$value['unit'].$value['floor']?></td>
         			<?php if(isset($done_inspect_images[$value['id']])):?>
         			<td width="50%"><img style="width:450px;height:300px;" src="<?php echo $done_inspect_images[$value['id']];?>"></td>
           			<?php else:?>
           			<td width="50%"></td>
           			<?php endif;?>
           		</tr>
        	</tbody>
        </table>
        <?php endforeach;?>
        </div>
        <?php endif;?>
        <?php endforeach;?>
    </div>
</body>
</html>
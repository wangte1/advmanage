<html lang="en">
    <head>

    <meta charset="utf-8">
    <title>灯箱广告验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- basic styles -->
        <link href="http://adv.wesogou.com/static/admin/css/bootstrap.min.css?v=201605161353" rel="stylesheet" />
        <link href="http://adv.wesogou.com/static/admin/css/font-awesome.min.css?v=201605161353" rel="stylesheet" />
        <link href="http://adv.wesogou.com/static/font-awesome/css/font-awesome.min.css?v=201607201012" rel="stylesheet" />
        <link href="http://adv.wesogou.com/static/admin/css/chosen.css?v=201605161353" rel="stylesheet" />

        <link href="http://adv.wesogou.com/static/admin/css/select2.css?v=201605161353" rel="stylesheet" />

        <link href="http://adv.wesogou.com/static/admin/css/colorbox.css?v=201605161353" rel="stylesheet" />
        <link href="http://adv.wesogou.com/static/admin/css/jquery-ui-1.10.3.full.min.css?v=201605161353" rel="stylesheet" />


        <link href="http://adv.wesogou.com/static/admin/css/ui-dialog.css?v=201605161353" rel="stylesheet" />



        <!--[if IE 7]>
          <link href="http://adv.wesogou.com/static/admin/css/font-awesome-ie7.min.css?v=201605161353" rel="stylesheet" />
        <![endif]-->

        <link href="http://adv.wesogou.com/static/admin/css/ace.min.css?v=201605161353" rel="stylesheet" />

        <!--[if lte IE 8]>
          <link href="http://adv.wesogou.com/static/admin/css/ace-ie.min.css?v=201605161353" rel="stylesheet" />
        <![endif]-->

        <!-- datepicker -->
        <link href="http://adv.wesogou.com/static/common/css/datepicker3.css?v=201601121059" rel="stylesheet" />

        <link href="http://adv.wesogou.com/static/admin/css/public.css?v=201605161353" rel="stylesheet" />
    </head>

    <style type="text/css"> 
        html, body {width: 100%; height: 90%; margin: 0; padding: 0; font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;background: "#fff"}
        .content {width: 1000px; margin: 0 auto; padding: 10px;}
        .title {font-size: 38px; font-weight: bold; margin-top: 10px;clear: both}
        .page-p {font-size:24px;height:20px}
        .mid-p {height: 100px;}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info tr td, .detail-info th {border: 1px solid black;height: 40px;font-size: 18px;text-align: center;}

        .detail-info-print {width: 1000px; border-collapse: collapse;border-spacing: 0;}
        .detail-info-print tr td, .detail-info-print th {border: 1px solid black;height: 40px;font-size: 18px;text-align: center; border-top: 0}
        
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
    <script type="text/javascript">
    $(function(){
    	$('#pdf-out').on('click', function(){
			console.log(1);
			var page = $(this).attr('data');
			var url = '/housesorders/confirmations?id=<?php echo $id;?>&page='+page+"&load=1";
			window.location.href = url;
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
       	<table class="detail-info-print" style="border-top:1px solid;padding-top:50px;">
       		<thead>
             	<th width="10%">序号</th>
             	<th width="10%">点位编号</th>
               	<th width="30%">点位地址</th>
               	<th width="50%">广告图</th>
			</thead>
           	<tbody>
           		<?php $num = ($k*80)+1;?>
     			<?php foreach($v as $key => $value):?>
     			<?php if($key < 10):?>
               	<tr>
            		<td width="10%"><?php echo $num ++;?></td>
            		<td width="10%"><?php  echo $value['code'];?></td>
           			<td width="30%"><?php echo $value['houses_name'].$value['houses_area_name'].$value['ban'].$value['unit'].$value['floor']?></td>
         			<?php if(!empty($value['img'])):?>
         			<td width="50%"><img style="width:100%;" src="<?php echo $value['img'];?>"></td>
           			<?php else:?>
           			<td width="50%"></td>
           			<?php endif;?>
           		</tr>
           		<?php endif;?>
           		<?php endforeach;?>
        	</tbody>
        </table>
        
        </div>
        <?php endif;?>
        <?php endforeach;?>
    </div>
</body>
</html>
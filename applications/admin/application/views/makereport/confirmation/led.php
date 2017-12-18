<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>户外广告验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css"> 
        html, body {width: 100%; height: 90%; margin: 0; padding: 0; font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;}
        .content {width: 1000px; margin: 0 auto; padding: 10px;}
        .title {font-size: 38px; font-weight: bold; margin-top: 10px;clear: both}
        .page-p {font-size:24px;height:20px}
        .mid-p {height: 100px;}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info tr td, .detail-info th {border: 1px solid black;height: 40px;font-size: 18px;padding-left: 20px;text-align: center;}
        
        .header {padding:10px;}
        .header .logo {width:167px; float: left}
        .header .contact-text {height: 60px; border-left: 3px solid #A7C0DE; float: left; padding-left: 20px; color: #A7C0DE; font-weight: bolder}
        .header p {height: 12px;}
        .footer {margin-top: 10px; padding: 10px; clear: both; color:#D2D2D2}
        .footer p{height: 10px;}

        .btn-print {width: 100%;margin-top: 50px; text-align: right; position: fixed; bottom: 0;left: 0;}
        .btn-print button {border-radius: 3px; width: 90px; height: 30px;}

        @media print { 
            .mid-p {height: 300px;}
            .noprint{display:none;}
        }
    </style>
</head>
<body>
    <div class="content">
        <center class="title"><?php echo $info['order_type'] == 3 ? '机场' : '元和国际';?>LED验收报告</center>
        <p class="page-p"><span style="font-weight: bolder;">甲方（委托方）：<?php echo $info['customer_name'];?></span></p>
        <p class="page-p"><span style="font-weight: bolder">乙方（承办方）：</span><span style="border-bottom: 1px solid black;"><?php echo $info['sponsor'];?></span></p>
        <p class="page-p">广告牌上画发布地点、数量及规格：</p>
        <table class="detail-info">
            <thead>
                <th>位置</th>
                <th>数量</th>
                <th>规格</th>
            </thead>
            <tbody>
                <?php foreach($points as $value):?>
                <tr>
                    <td><?php echo $info['order_type'] == 3 ? '机场' : '元和国际';?>LED</td>
                    <td>1</td>
                    <td><?php echo $value['size'];?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <table style="margin-top: 100px">
            <tbody>
                <?php foreach($inspect_images as $value):?>
                <tr>
                    <td style="padding:0"><img src="<?php echo $value['front_img'];?>" style="width: 500px; height: 280px" /></td>
                    <td style="padding:0"><img src="<?php echo $value['back_img'];?>" style="width: 500px; height: 280px" /></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <!-- <p class="page-p" style="line-height: 40px; margin-top: 60px;">备注：我司按照约定要求于<?php echo $complete_date;?>在<?php echo $info['order_type'] == 3 ? '机场' : '元和国际';?>大屏为甲方发布广告，投放时间为<?php echo date('Y.m.d', strtotime($info['release_start_time']));?>-<?php echo date('Y.m.d', strtotime($info['release_end_time']));?>，并将验收照片发给甲方确认。</p>-->
        <p class="page-p" style="line-height: 40px"><?php echo $info['remark']?></p>
        <p class="mid-p"></p>
        <p class="page-p"><span style="font-weight:bolder">甲方（盖章）：</span><span style="font-weight:bolder;margin-left:400px">乙方（盖章）：</span></p>
        <p class="page-p"><span style="font-weight:bolder">确认人（签字）：</span><span style="font-weight:bolder;margin-left:376px">确认人（签字）：</span></p>
        <p class="page-p"><span style="font-weight:bolder">日期：</span><span style="font-weight:bolder;margin-left:496px">日期：</span></p>
        <div class="noprint btn-print"><button type="button" onclick="javascript: window.print();">打 印</button></div>
    </div>
</body>
</html>

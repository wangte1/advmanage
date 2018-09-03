<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>户外高杆验收报告</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css"> 
        html, body {width: 100%; height: 90%; margin: 0; padding: 0; font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;}
        .content {width: 1000px; margin: 0 auto; padding: 10px;}
        .title {font-size: 38px; font-weight: bold; margin-top: 10px; clear: both;}
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
        .btn-print button {border-radius: 3px; width: 90px; height: 30px;}

        .hide{display:none}
        @media print { 
            .mid-p {height: 500px;}
            .set-top {border-top: 1px solid black;}
            .pageBreak{ page-break-after: always;} /*强制换页的关键*/
            .noprint{display:none;}
            div.page{page-break-after: always;/*page-break-inside: avoid;*/}
            .hide{display:table-header-group !important}
            .page-h{height: 1250px}
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="page">
            <center class="title">户外高杆验收报告</center>
            <p class="page-p"><span style="font-weight: bolder;">甲方（委托方）：<?php echo $info['customer_name'];?></span></p>
            <p class="page-p"><span style="font-weight: bolder">乙方（承办方）：</span><span style="border-bottom: 1px solid black;"><?php echo $info['sponsor'];?></span></p>
            <p class="page-p">广告牌上画发布地点、数量及规格：</p>
            <table class="detail-info">
                <thead>
                    <th width="20%">编号</th>
                    <th width="40%">高杆位置</th>
                    <th width="40%">高杆规格</th>
                </thead>
            </table>
            <?php foreach($points as $value):?>
            <table class="detail-info-print">
                <tbody>
                    <tr>
                        <td width="20%"><?php echo $value['media_code'];?></td>
                        <td width="40%"><?php echo $value['media_code'].' '.$value['media_name'];?></td>
                        <td width="40%"><?php echo $value['size'];?></td>
                    </tr>
                </tbody>
            </table>
            <?php endforeach;?>

            <?php if(count($points) < 25):?>
            <p class="page-p" style="line-height: 40px">
            备注：本次甲方共选<?php echo $total_num;?>根户外高杆。我司按照双方签订的户外广告发布合同要求于<?php echo date('Y年m月d日', strtotime($info['make_complete_time']));?>开始制作、安装广告画面，于<?php echo $complete_date;?>按时按量完成<?php echo $total_num;?>根户外高杆的广告投放，投放时间为<?php echo date('Y.m.d', strtotime($info['release_start_time']));?>-<?php echo date('Y.m.d', strtotime($info['release_end_time']));?>，现将验收照片发给甲方确认。</p>
            <p class="mid-p"></p>
            <p class="page-p"><span style="font-weight:bolder">甲方（盖章）：</span><span style="font-weight:bolder;margin-left:400px">乙方（盖章）：</span></p>
            <p class="page-p"><span style="font-weight:bolder">确认人（签字）：</span><span style="font-weight:bolder;margin-left:376px">确认人（签字）：</span></p>
            <p class="page-p"><span style="font-weight:bolder">日期：</span><span style="font-weight:bolder;margin-left:496px">日期：</span></p>
            <?php endif;?>
        </div>

        <?php if(count($points) >= 25):?>
        <?php 
        $num = 1;
        for ($i = 0; $i < ceil((count($points) - 25)/30); $i++) { 
        ?> 
        <div class="page">
            <div class="hide">
                <?php $this->load->view('orders/common/header_nocontact');?>
            </div>
            <div class="page-h">
                <?php 
                    $num1 = $num - 1;
                    for ($j = $num1; $j < count($points); $j++) { 
                        $class = "";
                        if ($j == $num1) {
                            $class = "set-top";
                        }

                        if ($j > $num1 && (($j - 25) % 30) == 0) {
                            break;
                        }
                ?>
                <table class="detail-info-print <?php echo $class;?>">
                    <tbody>
                        <tr>
                            <td width="20%"><?php echo $points[$i]['media_code'];?></td>
                            <td width="40%"><?php echo $points[$i]['media_code'].' '.$points[$i]['media_name'];?></td>
                            <td width="40%"><?php echo $points[$i]['size'];?></td>
                        </tr>
                    </tbody>
                </table>
                <?php }?>

                <?php if($i == ceil((count($points) - 25)/30) - 1):?>
                <p class="page-p" style="line-height: 40px">
                    备注：本次甲方共选<?php echo $total_num;?>根户外高杆。我司按照双方签订的户外广告发布合同要求于<?php echo date('Y年m月d日', strtotime($info['make_complete_time']));?>开始制作、安装广告画面，于<?php echo $complete_date;?>按时按量完成<?php echo $total_num;?>套公交站台灯箱广告的发布，投放时间为<?php echo date('Y.m.d', strtotime($info['release_start_time']));?>-<?php echo date('Y.m.d', strtotime($info['release_end_time']));?>，现将验收照片发给甲方确认。</p>
                <p class="mid-p"></p>
                <p class="page-p"><span style="font-weight:bolder">甲方（盖章）：</span><span style="font-weight:bolder;margin-left:400px">乙方（盖章）：</span></p>
                <p class="page-p"><span style="font-weight:bolder">确认人（签字）：</span><span style="font-weight:bolder;margin-left:376px">确认人（签字）：</span></p>
                <p class="page-p"><span style="font-weight:bolder">日期：</span><span style="font-weight:bolder;margin-left:496px">日期：</span></p>
                <?php endif;?>
            </div>
        </div>
        <?php }?>
        <?php endif;?>

        <?php 
        $img_num = 1;
        for ($i = 0; $i < ceil(count($inspect_images)/4); $i++) { 
        ?> 
        <div class="page">
            <div class="page-h">
                <?php 
                    $img_num1 = $img_num - 1;
                    for ($j = $img_num1; $j < count($inspect_images); $j++) { 
                        $class = "";
                        if ($j == $img_num1) {
                            $class = "set-top";
                        }

                        if ($j > $img_num1 && ($j % 4) == 0) {
                            break;
                        }
                ?>
                <table class="detail-info-print <?php echo $class;?>" <?php if($j == 0) { echo 'style="border-top: 1px solid black;"'; }?>>
                    <tbody>
                        <tr>
                            <td><?php echo $inspect_images[$j]['media_code'].' '.$inspect_images[$j]['media_name'];?> 正面</td>
                            <td><?php echo $inspect_images[$j]['media_code'].' '.$inspect_images[$j]['media_name'];?> 背面</td>
                        </tr>
                        <tr>
                            <td style="padding:0"><img src="<?php echo $inspect_images[$j]['front_img'];?>" style="width: 427px; height: 260px" /></td>
                            <td style="padding:0"><img src="<?php echo $inspect_images[$j]['back_img'];?>" style="width: 427px; height: 260px" /></td>
                        </tr>
                    </tbody>
                </table>
                <?php 
                        $img_num++;
                    }
                ?>
            </div>
        </div>
        <?php }?>
        <div class="noprint btn-print"><button type="button" onclick="javascript: window.print();">打 印</button></div>
    </div>
</body>
</html>

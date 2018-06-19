<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>

<style>
.padding0 {
	padding: 0;
}

.padding-right0 {
	padding-right: 0;
}
</style>
<div class="main-container" id="main-container">
        <div class="main-container-inner">
            <?php $this->load->view("common/left");?>

        </div>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="/housespoints">楼盘点位管理</a>
                    </li>
                    <li class="active">自定义区域设定</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                
                <div class="row">
                     <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <caption style="border-bottom: 1px solid #ddd;"><span style="font-size:18px">总共为您查询到<b>213</b>条记录，列表如下：</span></caption>
                    <thead>
                    <tr>
                        <th>楼盘</th>
                        <th>组团</th>
                        <th>区域</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>楼盘</td>
                            <td>组团</td>
                            <td>区域</td>
                        </tr>
                                            
                     </tbody>
                </table>
            </div>
                </div><!-- /.row -->

            </div><!-- /.page-content -->
        </div><!-- /.main-content -->



</div><!-- /.main-container -->



<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>


<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
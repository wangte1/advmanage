<?php $this->load->view('common/header');?>
<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">手工位管理</a></li>
        <li><a href="#">手工位名称</a></li>
        <li><a href="#">添加</a></li>
    </ul>
</div>

<form class="formbody" method="post" id="form">
    <div id="usual1" class="usual"> 
      	<div id="tab1" class="tabson">
            <ul class="forminfo">
            <li><label>名称</label><input name="web_type" type="text" class="dfinput" required/></li>
			<li><label>&nbsp;</label><input  type="submit" class="btn" value="保 存"/></li>
			</ul>
        </div>
    </div> 
</form> 
<?php $this->load->view("common/footer");?>
	</body>
</html>
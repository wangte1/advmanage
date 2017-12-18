$(function(){
	//日期控件参数设置
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN',
        autoclose: true,
        todayHighlight:true,
    });

    $('[data-rel=tooltip]').tooltip();
});
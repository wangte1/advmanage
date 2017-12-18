/**
*区域打印
*/
define(function(require, exports, module){
	window.jQuery = window.$ = require("jquery");
	
	require('jqprint');
	module.exports = {
			//打印
			area_print:function(){
				$('#print_btn').click(function(){
					$("#print_area").jqprint();
				})
			}
	}
});
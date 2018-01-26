/**
 * 补贴中心上传图片
 */
define(function(require, exports, module){
	window.jQuery = window.$ = require("jquery");
	require('jqueryswf');
	require('swfupload');
//	alert($('#filePicker', this).html());
	module.exports = {
		upload:function(session_id){
			var config = {
			        upload_url: baseUrl+"/file/upload",
		            file_size_limit : "10240",
		            file_types : "*.jpg;*.png;*.gif",
		            file_types_description : "All Files",
		            file_upload_limit : "0",
		            flash_url : staticUrl+"/common/css/swfupload.swf",
		            button_image_url : staticUrl+'/www/images/add_pic.png',
		            button_width : 232,
		            button_height : 175,
		            button_placeholder : $('#filePicker'),
		            debug: false
			    };
			var event = {
					swfuploadLoaded: function(event){
			            $('.log', this).append('<li>Loaded</li>');
			        },
			        fileQueued: function(event, file){
			            $('.log', this).append('<li>File queued - '+file.name+'</li>');
			            // start the upload once it is queued
			            // but only if this queue is not disabled
			            if (!$('input[name=disabled]:checked', this).length) {
			                $(this).swfupload('startUpload');
			            }
			        },
			        fileQueueError: function(event, file, errorCode, message){
			            $('.log', this).append('<li>File queue error - '+message+'</li>');
			        },
			        fileDialogStart: function(event){
			            $('.log', this).append('<li>File dialog start</li>');
			        },
			        fileDialogComplete: function(event, numFilesSelected, numFilesQueued){
			            $('.log', this).append('<li>File dialog complete</li>');
			        },
			        uploadStart: function(event, file){
			            $('.log', this).append('<li>Upload start - '+file.name+'</li>');
			            // don't start the upload if this queue is disabled
			            if ($('input[name=disabled]:checked', this).length) {
			                event.preventDefault();
			            }
			            var html = "<li id='"+file.id+"' class='pic pro_gre' style='margin-right: 20px; clear:none;'>"+
		                        "<div class='proCont'>"+
		                        "<p class='progress'>0%</p>"+
		                        "<div class='pro_pic'>"+
		                            "<i  class='pro_cont' style='width:0%'></i>"+
		                        "</div>"+
		                    "</div></li>";
			            $(".add-pic").before(html);
			        },
			        uploadProgress: function(event, file, bytesLoaded){
			            $('.log', this).append('<li>Upload progress - '+bytesLoaded+'</li>');
			            var value = parseInt(bytesLoaded/file.size)+'%';
			            $("#"+file.id).find(".progress").html(value);
			            $("#"+file.id).find(".pro_cont").css({'width':value});
			        },
			        uploadSuccess: function(event, file, serverData){
			            $('.log', this).append('<li>Upload success - '+file.name+'</li>');
			            var data = $.parseJSON(serverData);
			            if(data.status == 0){
			                var html = '';
		                    html += "<a class='close del-pic' href='javascript:;'></a>";
		                    html += "<a href='"+data.data.url+"' target='_blank'><img src='"+data.data.url+"' style='width:100%;height:100%'/></a>";
		                    html += "<input type='hidden' name='protocol_img[]' value='"+data.data.file_sub_name+"'/>";
			            }else{
			                var html =     "<p>"+file.name+"上传异常</p>"
			            }
			            $("#"+file.id).html(html);
			        },
			        uploadComplete: function(event, file){
			            $('.log', this).append('<li>Upload complete - '+file.name+'</li>');
			            // upload has completed, lets try the next one in the queue
			            // but only if this queue is not disabled
			            if (!$('input[name=disabled]:checked', this).length) {
			                $(this).swfupload('startUpload');
			            }
			        },
			        uploadError: function(event, file, errorCode, message){
			        	$('.log', this).append('<li>Upload error - '+message+'</li>');
			        	console.log('Upload error - '+message)
			        	/*var html =     "<p>"+file.name+"上传异常:"+message+"</p>";
			        	$("#"+file.id).html(html);*/
			            
			        },
			}
			$("#file_upload").bindAll(event);
			$("#file_upload").each(function(){
				$(this).swfupload({
					upload_url: baseUrl+"/file/upload",
		            file_size_limit : "10240",
		            file_types : "*.jpg;*.png;*.gif",
		            file_types_description : "All Files",
		            file_upload_limit : "0",
		            flash_url : staticUrl+"/common/css/swfupload.swf",
		            button_image_url : staticUrl+'/www/images/add_pic.png',
		            button_width : 232,
		            button_height : 175,
		            button_placeholder : $('#filePicker', this)[0],
		            debug: false,
		            post_params:{
		            	"session_id":session_id,
		            },
				});
			})
			    
			    
		},
		remove_img:function(){
			$('#file_upload').on('click','.del-pic', function(){
				$(this).parent().remove();
			})
		}
	}
	jQuery.fn.bindAll = function(options) {
	    var $this = this;
	    jQuery.each(options, function(key, val){
	        $this.bind(key, val);
	    });
	    return this;
	}
})
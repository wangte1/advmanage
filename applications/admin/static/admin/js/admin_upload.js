jQuery.fn.bindAll = function(options) {
    var $this = this;
    jQuery.each(options, function(key, val){
        $this.bind(key, val);
    });
    return this;
}

$(function(){
    
    var listeners = {
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
            var html = "<li id='"+file.id+"' class='pic pro_gre' style='margin-right: 20px; clear: none'>"+
                        "<div class='proCont'>"+
                        "<p class='progress'>0%</p>"+
                        "<div class='pro_pic'>"+
                            "<i  class='pro_cont' style='width:0%'></i>"+
                        "</div>"+
                    "</div></li>";
            $(this).find(".add-pic").before(html);
        },
        uploadProgress: function(event, file, bytesLoaded){
            $('.log', this).append('<li>Upload progress - '+bytesLoaded+'</li>');
            var value = parseInt(bytesLoaded/file.size)+'%';
            $("#"+file.id).find(".progress").html(value);
            $("#"+file.id).find(".pro_cont").css({'width':value});
        },
        uploadSuccess: function(event, file, serverData){
            $('.log', this).append('<li>Upload success - '+file.name+'</li>');
            if(this.id == 'uploader_cover_img'){
                var name = 'cover_img';
            } 
            var data = $.parseJSON(serverData);
            if(data.error == 0){
                var html = '';
                html += "<a data-rel='colorbox' class='cboxElement' href='"+data.url+"'>";
                html += "<img src='"+data.url+"' style='width: 215px; height: 150px' />";
                html += "</a>";
                html += ' <div class="tools"> <a href="javascript:;"> <i class="icon-remove red"></i> </a>  </div>';
                html += "<input type='hidden' name='"+name+"[]' value='"+data.url+"'/>";
            }else{
                var html =     "<p>"+file.name+"上传异常</p>"
            }
           $("#"+file.id).html(html);

        },
        uploadComplete: function(event, file){
            $('.log', this).append('<li>Upload complete - '+file.name+'</li>');

            if (!$('input[name=disabled]:checked', this).length) {
                $(this).swfupload('startUpload');
            }
        },
        uploadError: function(event, file, errorCode, message){
        	$('.log', this).append('<li>Upload error - '+message+'</li>');
        	/*var html =     "<p>"+file.name+"上传异常:"+message+"</p>";
        	$("#"+file.id).html(html);*/
            
        }
    };

    $('#uploader_cover_img').bindAll(listeners);
});
        
$(function(){
    var object =[
        {"obj":"#uploader_cover_img", "btn":"#file_cover_img"},                  //广告画面
    ];

    $.each(object,function(key,value) {   
        $(value.obj).swfupload({
            upload_url: baseUrl+"/file/upload?dir=image",
            file_post_name: "imgFile",
            file_size_limit : "10240",
            file_types : "*.jpg;*.png;*.gif",
            file_types_description : "All Files",
            file_upload_limit : "0",
            flash_url : staticUrl+"/common/css/swfupload.swf",
            button_image_url : staticUrl+'/admin/images/add_pic.png',
            button_width : 232,
            button_height : 175,
            button_placeholder : $(value.btn)[0],
            debug: false
        });

        $(value.obj).on('click', '.icon-remove', function(){
            $(this).parents("li").remove();
        });
    });  
});    

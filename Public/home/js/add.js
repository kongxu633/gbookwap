function showToast() {
    var $toast = $('#toast');
    if ($toast.css('display') != 'none') {
        return;
    }

    var $loadingToast = $('#loadingToast');
    if ($loadingToast.css('display') != 'none') {
        $loadingToast.hide();
    }

    $toast.show();
    setTimeout(function () {
        $toast.hide();
    }, 1000);
}
function showLoadingToast() {
    var $loadingToast = $('#loadingToast');
    if ($loadingToast.css('display') != 'none') {
        return;
    }
    $loadingToast.show();
}

function mysubmit(){
    if($('#con').val()==""){
        $('#dialog').show();
    }
    else{

        $('#mysubmit').removeAttr('href');
        $('#mysubmit').addClass('weui_btn_disabled');
        $('#myform').submit();
    }
}


$(document).ready(function() {

    $("#content").on('keyup',function(){
        var maxlen = 500;
        var len = $(this).val().length;
        if(len >= maxlen){
            $(this).val($(this).val().substring(0,maxlen));
            $(".weui_textarea_counter span").text(maxlen);
        }else {
            $(".weui_textarea_counter span").text(len);
        }
    });

    $(".weui_uploader_input").on('change', function () {

        //正在处理图片
        showLoadingToast();


        lrz(this.files[0])
            .then(function (result) {

                var clearBase64 = result.base64.substr(result.base64.indexOf(',') + 1);
                $.ajax({

                    type: "POST",
                    url: UPLOAD_HANDLE,
                    data: {base64_string: clearBase64},
                    dataType: "json",
                    success: function (data) {
                        if (0 == data.status) {
                            alert(data.content);
                            return false;
                        } else {
                            var attstr = '<li class="weui_uploader_file" style="background-image:url(' + UPLOAD_PATH + data.url + ')"></li>';
                            //attstr += '<p><a href="' + UPLOAD_PATH + data.url + '" target="_blank">' + UPLOAD_PATH + data.url + '</a><img src="' + UPLOAD_PATH + data.url + '"></p>';
                            $(".weui_uploader_files").append(attstr);

                            var picstr = '<input name="pic[]" value="' + data.url + '">';
                            $("#pics").append(picstr);

                            return false;
                        }
                    },
                    beforeSend: function () {
                        //准备上传
                    },
                    complete: function (XMLHttpRequest, textStatus) {
                        //当请求完成之后调用这个函数，无论成功或失败。传入 XMLHttpRequest 对象，以及一个包含成功或错误代码的字符串。
                        showToast();

                        //检查是否5个图片了
                        maxnum = 5;
                        picnum = $(".weui_uploader_file").length;
                        if(picnum >= maxnum){
                            $(".weui_uploader_input_wrp").hide();
                            $(".weui_cell_ft span").text(maxnum);
                        }else{
                            $(".weui_cell_ft span").text(picnum);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) { //上传失败
                        //alert(XMLHttpRequest.status);
                        //alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
                });

            });
    });

});

jQuery(document).ready(function($){

    var progressElem = $('#progressCounter');
    $('#loading').hide();

    $(document).on('click','input[name=featured]',function(e){

        e.preventDefault();
        // console.log("click");
        $('input[name=featured_upload]').click();

    });

    $(document).on('change','input[name=featured_upload]',function(){

        $('input[name=featured]').addClass('hidden');
        $(this).parent().submit();

    });

    $(document).on('submit','.ajax-form.hidden',function(e) {

        e.preventDefault();
        var fd = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            xhr: function() {

                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                    $('#loading').show();
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        progressElem.html(Math.round(percentComplete * 100) + "%");
                        progressElem.css('width',Math.round(percentComplete * 100) + "%");
                    }
                }, false);
                xhr.addEventListener("progress", function (evt) {
                    $('#loading').show();
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        progressElem.html(Math.round(percentComplete * 100) + "%");
                        progressElem.css('width',Math.round(percentComplete * 100) + "%");
                    }
                }, false);
                return xhr;

            },
            type: 'post',
            processData: false,
            contentType: false,
            data: fd,
            beforeSend : function() {
                $('#loading').show();
            },
            complete : function() {
                $('#loading').hide();
            },
            success: function(data) {

                $('#url_imagem').val(data.path);
                $('input[name=featured]').remove();
                $('.bootstrap-filestyle').remove();
                $('#imagem').remove();
                $('.uploaded-image').attr('src',data.path).removeClass('hidden');

            },
            error: function() {

                $('#url_imagem').val('erro');
                $('input[name=featured]').remove();
                $('.bootstrap-filestyle').remove();
                $('#imagem').remove();
                $('.uploaded-image').attr('src','erro').removeClass('hidden');

            }
        });

    });

    $(document).on('change','#state',function(){

        var value = $(this).val();
        $('input[name=state_request]').val(value).parent().submit();

    });

    $(document).on('submit','.ajax-request.hidden',function(e) {

        e.preventDefault();
        var fd = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            xhr: function() {

                var xhr = new XMLHttpRequest();
                return xhr;

            },
            type: 'post',
            processData: false,
            contentType: false,
            data: fd,
            dataType: "json",
            success: function(data) {

                $('#city').find('option').each(function(){
                    $(this).remove();
                });

                var cities = data.cities;

                jQuery.each(cities, function(index, value){

                    var city = $(this);
                    var option = $('<option/>');
                    option.val(index).text(value);
                    $('#city').append(option);

                });

            }
        });

    });

});
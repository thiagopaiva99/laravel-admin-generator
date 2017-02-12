@extends('layouts.app')

@section('content')

    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-danger">

            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Novo usuário</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <div class="row">

                    {!! Form::open(['route' => 'admin.users.store', 'role' => 'form', 'data-toggle' => 'validator', 'id' => 'form_new_profile']) !!}

                        @include('admin.users.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

{!! Form::open(["url" => url("upload/image"), 'class' => 'ajax-form hidden', 'files' => true]) !!}
<input type="file" name="featured_upload" id="featured_upload" value="" />
{!! Form::close() !!}

@section("scripts")
    <script>
        jQuery(document).ready(function($){

            var progressElem = $('#progressCounter');
            $('#loading').hide();

            $(document).on('click','input[name=featured]',function(e){

                e.preventDefault();
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

                        $('#image_src').val(data.path);
                        $('input[name=featured]').remove();
                        $('#imagem').remove();
                        $('.uploaded-image').attr('src',data.path).removeClass('hidden');

                    },
                    error: function() {

                        $('#image_src').val('erro');
                        $('input[name=featured]').remove();
                        $('#imagem').remove();
                        $('.uploaded-image').attr('src','erro').removeClass('hidden');

                    }
                });

            });
        });
    </script>
@endsection

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{ asset('assets/site/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/AdminLTE.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/skins/_all-skins.min.css">

        <title>Contato via app</title>
    </head>
    <body style="background: #fafafa;">
        <br>
        <div class="container-fluid">
            {!! Form::open(["url" => 'contato-app/', 'method' => 'POST']) !!}

                <div class="col-md-12" id="return_message">

                </div>

                    <div class="form-group">
                        {!! Form::label('', 'Selecione o motivo do contato:') !!}
                        {!! Form::select('select_motivo', [1 => 'Bug no aplicativo', 2 => 'Reclamação', 3 => 'Informações'], null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('', 'Faça cum comentário sobre seu contato:') !!}
                        {!! Form::textarea('textarea_coment', null, ['class' => 'form-control', 'placeholder' => 'Faça um comentário também!']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::button('Enviar Contato', ['class' => 'btn btn-success btn-block bg-green', 'id' => 'btn_contato_app']) !!}
                    </div>

            {!! Form::close() !!}
        </div>

        <script   src="https://code.jquery.com/jquery-2.2.4.js"   integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="   crossorigin="anonymous"></script>
        <script>
            $("#btn_contato_app").on('click', function(){

                var id_user = window.location.pathname.split("/");
                var motivo = $("select[name=select_motivo]").val();
                var coment = $("textarea[name=textarea_coment]").val();

                $.ajax({
                    url  : window.location.protocol + '//' + window.location.host + '/contato-app',
                    type : 'POST',
                    data : {'motivo' : motivo, 'coment' : coment, 'user' : id_user[2]},
                    success: function(data){
                        console.log(data);
                        $("#return_message").addClass("alert").css({"background" : "#616161", "color" : "#fff"}).text("Sua mensagem foi enviada com sucesso!").delay(4000).slideUp(500);
                    }
                });


            });
        </script>
    </body>
</html>
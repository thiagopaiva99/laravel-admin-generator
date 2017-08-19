<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ getenv("SITE_TITLE") }} - {{ getenv("TITLE") }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/AdminLTE.min.css">

    <!-- iCheck -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/skins/_all-skins.min.css">--}}
    <link rel="stylesheet" href="{{ asset('assets/site/css/bootstrap2-toggle.css') }}">

    <link rel="shortcut icon" type="image/png" href="{{ url("assets/site/images/favicon.ico") }}"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @include("layouts.styles")
    @include("auth.styles")

    <style>
        /*.main-login-box{*/
            {{--background: url('{{ url('assets/site/images/blur.jpg') }}') !important;--}}
            /*background-repeat: no-repeat;*/
            /*background-position: center;*/
            /*background-size: cover;*/
            /*width: 100%;*/
            /*height: 100%;*/
        /*}*/
    </style>

</head>
<body class="hold-transition login-page" style="background: #FAFAFA;">
{{--<div class="login-box">--}}
    {{--<div class="login-logo">--}}
        {{--<a href="{{ url('/home') }}"><img src="{{ asset('assets/site/images/login_logo.png') }}"  style="max-width: 80% !important;" alt="LOGO DO EMBELEZZÔ"></a><br>--}}
    {{--</div>--}}

    {{--<!-- /.login-logo -->--}}
    {{--<div class="login-box-body">--}}
        {{--<p class="login-box-msg">Faça o login para acessar o painel!</p>--}}

        {{--<form method="post" action="{{ url('/login') }}">--}}
            {{--{!! csrf_field() !!}--}}

            {{--<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">--}}
                {{--<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">--}}
                {{--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>--}}
                {{--@if ($errors->has('email'))--}}
                    {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                {{--</span>--}}
                {{--@endif--}}
            {{--</div>--}}

            {{--<div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">--}}
                {{--<input type="password" class="form-control" placeholder="Senha" name="password">--}}
                {{--<span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
                {{--@if ($errors->has('password'))--}}
                    {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('password') }}</strong>--}}
                {{--</span>--}}
                {{--@endif--}}

            {{--</div>--}}
            {{--<div class="row">--}}
                {{--<div class="col-xs-8">--}}
                    {{--<div class="">--}}
                        {{--Manter-se conectado?--}}
                        {{--<input type="checkbox" name="remember" data-toggle="toggle" data-size="normal" data-on="Sim" data-off="Não" data-onstyle="danger" data-height="30" data-width="100">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<!-- /.col -->--}}
                {{--<br>--}}
                    {{--<button type="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> Fazer login</button>--}}
                {{--<!-- /.col -->--}}
            {{--</div>--}}
        {{--</form>--}}

        {{--<hr>--}}

        {{--<a href="{{ url('/password/reset') }}" style="color: #571c36">Esqueci minha senha</a><br>--}}
    {{--</div>--}}
    {{--<!-- /.login-box-body -->--}}
{{--</div>--}}
<!-- /.login-box -->

<img src="{{ url('assets/site/images/blur.jpg') }}" class="background" alt="">

<div class="main-login-box">
    <form action="{{ url('/login') }}" method="post">
        {!! csrf_field() !!}

        {{--{{ dump($errors) }}--}}

        <div class="login">
            <div class="login-screen">
                <div class="app-title">
                    <h1>{{ getenv("TITLE") }}</h1>
                </div>

                <div class="login-form">
                    <div class="control-group">
                        <input autocomplete="false" type="text" class="login-field" name="email" value="" placeholder="username" id="login-name">
                        <label class="login-field-icon fui-user" for="login-name"></label>
                    </div>

                    <div class="control-group">
                        <input autocomplete="false" type="password" name="password" class="login-field" value="" placeholder="password" id="login-pass">
                        <label class="login-field-icon fui-lock" for="login-pass"></label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large btn-block">LOGIN</button>
                    <a class="login-link" href="#">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/site/js/bootstrap2-toggle.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>--}}

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/js/app.min.js"></script>
<script>
    $(function () {
//        $('input').iCheck({
//            checkboxClass: 'icheckbox_square-blue',
//            radioClass: 'iradio_square-blue',
//            increaseArea: '20%' // optional
//        });

        //iniciando o toggle
        $("input[type=checkbox]").bootstrapToggle();
    });
</script>
</body>
</html>

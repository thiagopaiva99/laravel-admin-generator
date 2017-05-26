<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ getenv("SITE_TITLE") }} - {{ getenv("TITLE") }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/site/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/bootstrap2-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/pickadate/classic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/pickadate/classic.date.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/pickadate/classic.time.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/calendars.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/skins/_all-skins.min.css">


    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- CSS da App -->
    <link rel="stylesheet" href="{{ elixir('css/all.css') }}">

    <link rel="shortcut icon" type="image/png" href="{{ url("assets/site/images/favicon.ico") }}"/>

    @yield('styles')

    @include("layouts.styles")
</head>

<body class="skin-red sidebar-mini {{ getenv("LAYOUT_TYPE") }}">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="#" class="logo">
                <!-- Logo Mini -->
                <span class="logo-mini">
                    {{ getenv("MINI_TITLE") }}
                </span>
                <!-- Logo Lg -->
                <span class="logo-lg">
                    <b>{{ getenv("TITLE") }}</b>
                </span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation" style="background: #571c36">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="Imagem do usuário"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header" style="background: #571c36;">
                                    <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="Imagem do usuário"/>
                                    <p>
                                        {!! Auth::user()->name !!}
                                        <small>Membro desde {!! Auth::user()->created_at->format('d/m/Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route("admin.users.show", [Auth::id()]) }}" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat">Sair</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('admin/home') !!}">
                   	Embelezzô
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('admin/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{!! url('/login') !!}">Login</a></li>
                        <li><a href="{!! url('/register') !!}">Register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Main Footer -->
<footer class="main-footer" style="max-height: 100px;text-align: center">
    <strong>Copyright © 2017 <a href="{{ getenv("COMPANY_URL") }}">{{ getenv("COMPANY") }}</a>.</strong> Todos os direitos reservados.
</footer>



    <!-- jQuery 2.1.4 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/js/app.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/site/js/jquery.datatables.js') }}"></script>
    <script src="{{ asset('assets/site/js/jquery.datatables-bootstrap.js') }}"></script>

    {{--<script src="{{ asset('assets/site/js/tableExport.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/site/js/jquery.base64.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/site/js/libs/sprintf.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/site/js/jspdf.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/site/js/libs/base64.js') }}"></script>--}}
    {{--<script src="//cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json"></script>--}}

    <!-- Calendar -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{ asset('assets/site/js/sweetalert.min.js') }}"></script>

    <link rel="stylesheet" href="http://www.prepbootstrap.com/Content/css/loadingbuttoneffects/local.css" />

    <!-- JS da App -->
    <script src="{{ elixir('js/all.js') }}"></script>

    <script src="{{ asset('assets/site/js/validator.js') }}"></script>
    <script src="{{ asset('assets/site/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/site/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/site/js/jquery.cookie.js') }}"></script>
    {{--<script src="{{ asset('assets/site/js/jquery.confirm.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/site/js/chart.min.js') }}"></script>--}}
    <script src="{{ asset('assets/site/js/bootstrap2-toggle.js') }}"></script>
    <script src="{{ asset('assets/site/js/bootstrap-filestyle.js') }}"></script>
    <script src="{{ asset('assets/site/js/pickadate/picker.js') }}"></script>
    <script src="{{ asset('assets/site/js/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('assets/site/js/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('assets/site/js/picker.js') }}"></script>
    <script src="{{ asset('assets/site/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/app-admin.js') }}"></script>
{{--    <script src="{{ asset('js/main.js') }}"></script>--}}
    <!-- Custom Scripts -->

    <!-- JS da App Gerado pelo Gulp -->
    <script src="{{ asset('js/main.js') }}"></script>

    @yield('scripts')

    @include("layouts.scripts")
</body>
</html>

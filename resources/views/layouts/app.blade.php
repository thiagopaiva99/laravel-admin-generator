<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ getenv("SITE_TITLE") }} - {{ getenv("TITLE") }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    @include("layouts.links")

    @yield('styles')

    @include("layouts.styles")
</head>

<body class="skin-red sidebar-mini">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Navbar -->
        @include("layouts.navbar")

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
@endif

@include("layouts.footer")



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
    <!-- Custom Scripts -->

    <!-- JS da App Gerado pelo Gulp -->
    <script src="{{ asset('js/main.js') }}"></script>

    @yield('scripts')

    @include("layouts.scripts")
</body>
</html>

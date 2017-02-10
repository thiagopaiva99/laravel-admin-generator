<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>docsaúde</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/materialize.min.css') }}" />
        <link rel="stylesheet" href="{{ url('assets/site/css/icons.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/responsive.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/color.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/fullcalendar.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/js/libs/chosen/chosen.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/js/libs/popSelect-master/css/jquery.popSelect.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/js/libs/popSelect-master/css/site.popSelect.css') }}" />

        <!-- REVOLUTION STYLE SHEETS -->
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/revolution/settings.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/revolution/navigation.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/site/css/revolution/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}">

        <link rel="shortcut icon" type="image/png" href="{{ url("assets/site/images/favicon.png") }}"/>
    </head>
    <body>
    <div class="theme-layout">

        <div class="pageloader">
            <div class="loader">
                <div class="loader-inner ball-scale-ripple-multiple">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div><!-- Pageloader -->

        @include('site.header')

        @yield('content')

        @include('site.footer')

    </div>


    @include('site.popup')

    <script src="{{ url('assets/site/js/jquery.min.js') }}" type="text/javascript"></script>

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/jquery.themepunch.tools.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/jquery.themepunch.revolution.min.js') }}"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.actions.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.kenburn.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.layeranimation.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.migration.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.navigation.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.parallax.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.slideanims.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.extension.video.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/revolution/revolution.initialize2.js') }}"></script>

    @yield('scripts')

    <script src="{{ url('assets/site/js/materialize.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/enscroll-0.5.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/site/js/jquery.poptrox.min.js') }}"></script>
    <script src="{{ url('assets/site/js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('assets/site/js/smoothscroll.js') }}"></script>
    <script src="{{ url('assets/site/js/uploader.js') }}"></script>
    <script src="{{ url('assets/site/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/site/js/calendar.js') }}"></script>
    <script src="{{ url('assets/site/js/pt-br.js') }}"></script>
    <script src="{{ url('assets/site/js/script.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/jquery.validate.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/jquery.maskedinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/libs/popSelect-master/src/jquery.popSelect.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/site/js/libs/chosen/chosen.jquery.min_.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            /*$('.popSelect').popSelect({
                showTitle: false,
                placeholderText: 'Plano de Saúde'
            });*/
            var chosenSelect = $(".chosen-select");
            chosenSelect.chosen();
            var selected = [];
            chosenSelect.chosen().change(
                function () {
                    var select = $(this);
                    $(this).find('option').each(function () {
                        var option = $(this);
                        if (option.is(':selected') && $.inArray(option.val(), selected) == -1) {
                            select.find('option.hp-' + option.val()).each(function () {
                                $(this).prop('selected', true);
                            });
                        } else if (!option.is(':selected') && $.inArray(option.val(), selected) != -1) {
                            select.find('option.hp-' + option.val()).each(function () {
                                $(this).prop('selected', false);
                            });
                            if (option.hasClass('hp_id')) {
                                select.find('option[value="' + (option.attr("class").replace("hp_id hp-", "")) + '"]').prop('selected', false);
                            }
                        }
                    });
                    selected = select.val();
                    chosenSelect.trigger("chosen:updated");
                }
            );
        });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function() {

            /* ============  Carousel ================*/
            $('.staff-carousel').owlCarousel({
                autoplay:true,
                autoplayTimeout:2500,
                smartSpeed:2000,
                autoplayHoverPause:true,
                loop:true,
                dots:false,
                nav:true,
                margin:0,
                mouseDrag:true,
                singleItem:true,
                items:1,
                autoHeight:true
            });
        });
    </script>

    </body>
</html>
$(window).focus(function(){
    $('.picker').appendTo('body');
}).blur(function(){
    $('.picker').appendTo('body');
});

jQuery(document).ready(function() {
    "use strict";


    /*=== Sticky Header ===*/
    if($("header").hasClass("stick")){
        var menu_bar_height = $(".menu-bar").innerHeight();
        var menu_bar_offset = $(".menu-bar").offset().top;
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll >= menu_bar_offset) {
                $(".stick").addClass("sticky");
                $(".menu-bar-height").css({
                    "height":menu_bar_height
                });
            } else {
                $(".stick").removeClass("sticky");
                $(".menu-bar-height").css({
                    "height":0
                });
            }
        });
    }


    if($("header").hasClass("stick2")){
        var full_menu_height = $(".full-menu").innerHeight();
        var full_menu_offset = $(".full-menu").offset().top;
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll >= full_menu_offset) {
                $(".stick2").addClass("sticky2");
                $(".full-menu-height").css({
                    "height":full_menu_height
                });
            } else if (scroll < full_menu_offset){
                $(".stick2").removeClass("sticky2");
                $(".full-menu-height").css({
                    "height":0
                });
            }
        });
    }



    /*=== Responsive Header ===*/
    $(".responsive-topbar-info > div").on("click",function(){
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
    });

    $(".responsive-links > ul li ul").parent().addClass("has-child");
    $(".responsive-links > ul li.has-child > a").on("click",function(){
        $(this).parent().toggleClass("open");
        $(this).next("ul").slideToggle();
        $(this).parent().siblings().removeClass("open").find("ul").slideUp();
        return false;
    });  

    $(".open-menu").on("click",function(){
        $(".responsive-links").addClass("slidein");
        return false;
    });

    $(".responsive-links > span").on("click",function(){
        $(".responsive-links").removeClass("slidein");
    });


    $("nav ul li ul").parent().addClass("has-dropdown");


    /*=== Search Toggle ===*/
    $(".search-btn").on("click",function(){
        $(".fancy-search").slideToggle();
        $(this).toggleClass("active");
        return false;
    });


    /*=== Tabs Fade Effect ===*/
    $(".staff-tabs-selectors .tabs .tab a , .doctors-timetable .tabs .tab a").on("click",function(){
        var tab_id = $(this).attr("href");
        tab_id = tab_id.replace('#', '');
        $("body").find('#' + tab_id).fadeIn(2000);
    });


    /*=================== Canvas Size ===================*/
    var canvas_size = $("#canvas").parent().width();
    $("#canvas").attr("width",canvas_size);


    /*=================== Woocommerce Functionalities  ===================*/
    /*== Add To Cart Button ==*/
    $(".product-hover > a.product-cart").on("click",function(){
        $(this).addClass("added");
        var product_count = parseInt($(this).find("span").html()); 
        $(this).addClass("loading").delay(3000).queue(function(next){
            $(this).removeClass("loading");
            $(this).find("span").html(product_count+1);        
            if(product_count == 0){
                $(this).before("<a class='view-cart' href='#' title=''><i class='fa fa-arrow-right'></i></a>");
            }
            Materialize.toast('The product is successfully added to cart!', 4000);
          next();
        });
        return false;
    });

    /*== Wishlist Button ==*/
    $(".product-hover > a.product-wishlist").on("click",function(){ 
        $(this).addClass("loading").delay(3000).queue(function(next){
            $(this).removeClass("loading");
            if($(this).hasClass("added-to-wishlist")){
                Materialize.toast('The product is successfully removed from wishlist!', 4000);
            }
            else{
                Materialize.toast('The product is successfully added to wishlist!', 4000);
            }
            $(this).toggleClass("added-to-wishlist");
            next();
        });
        return false;
    });

    /*=================== Project Hover Trigger Click On Image  ===================*/
    $(".project-hover a,.product-search").on("click",function(){
        $(this).parent().parent().find(".materialboxed").trigger("click");
        return false;
    });



    /*=================== Datepicker Independency ===================*/
    var params = {
        onOpen:function(){
            var picker = this;
            var input = this.$node;
            $('.picker').appendTo('body');
            if ($(input).hasClass('max-date')) {
                var dateArray = $('.datepicker.min-date').val().split('/');
                if (Object.prototype.toString.call(dateArray) === '[object Array]') {
                    var date = new Date(dateArray[2], (dateArray[1] - 1), dateArray[0]);
                    this.set('min', date);
                }
            }
        },
        formatSubmit: 'dd/mm/yyyy',
        format: 'dd/mm/yyyy',
        monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        weekdaysFull: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        today: 'hoje',
        clear: 'limpar',
        close: 'fechar'
    };
    var datePicker = $('.datepicker');
    datePicker.pickadate(params);



    //upload
    $("#imgInput").change(function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#view-img").css({'background' : '#fff', 'border-radius' : '3px', 'box-shadow' : '0px 0px 15px #555'}).attr('src', e.target.result).slideDown(450);
            };

            reader.readAsDataURL(this.files[0]);
        }
    });



    /*=================== Smooth Page Scrolling ===================*/
    $("a.btn-text").on("click",function(){
        $(this).parent().siblings().removeClass("active");
        $(this).parent().addClass("active");
    });


    /*=================== Popups ===================*/
    $(document).on("click",".call-popup",function(){
        $("html").addClass("popup-active");
        var popup_name = $(this).attr("class");
        popup_name = popup_name.replace('call-popup ', '').replace(' dark-btn', '');
        $(".popup-wrapper").find("." + popup_name).addClass("active");
        // console.log(popup_name);
        $(".popup").fadeIn();
        return false;
    });

    $("html").on("click",function(){
        $("html").removeClass("popup-active");
        $(".has-popup-content").removeClass("active");                
        $(".popup").fadeOut();
    });
    $(".has-popup-content").on("click",function(e){
        e.stopPropagation();
    });



    /*=================== Event Map Icons Functionalities ===================*/
    $(".map-icon").on("click",function(){
        $(this).parent().parent().toggleClass("active");
        return false;
    });
    $(".event-detail-img > span").on("click",function(){
        $(this).parent().toggleClass("active");
    });


    /*=================== Opening Timing Background Color ===================*/
    var drop =  $(".opening-shortcode .opening .timing").length;
    var opening_timing =  $(".opening-shortcode .opening .timing");
    var half_drop = Math.round(drop/2);
    var counter = 1;
    $('.opening-shortcode .opening').each(function(){
        var delay = 1;
        var decrement = 0.1;
        $(this).find(opening_timing).each(function(){
            $(this).css({"opacity": delay});
            console.log(counter);
            if(half_drop > counter) {
                delay = delay-decrement;
            }else {
                delay = delay+decrement;
            }
            counter++;
        });
    });

    /*=================== Specialist Social Icons ===================*/
    var icons = $('.specialist-img .social-icons a');
    $('.specialist').each(function(){
        var delay = 0;
        $(this).find(icons).each(function(){
        $(this).css({transitionDelay: delay+'ms'});
        delay += 50;
        });
    });



    /*=================== Accordion ===================*/
    $('.toggle .content').hide();
    $('.toggle h3:first').addClass('active').next().slideDown(500).parent().addClass("activate");
    $('.toggle h3').on("click", function() {
        if ($(this).next().is(':hidden')) {
            $('.toggle h3').removeClass('active').next().slideUp(500).removeClass('animated zoomIn').parent().removeClass("activate");
            $(this).toggleClass('active').next().slideDown(500).addClass('animated zoomIn').parent().toggleClass("activate");
        }
    });



    /*=================== Parallax ===================*/
    $('.parallax').parallax();



    /*=================== LightBox ===================*/
    var foo = $('.lightbox');
    foo.poptrox({
        usePopupCaption: true,
        usePopupNav: true,
    });



    /*=================== Materialize ===================*/
    $('select').not('.popSelect, .chosen-select').material_select();

    datePicker.pickadate({
        selectMonths:true, // Creates a dropdown to control month
        selectYears:15 // Creates a dropdown of 15 years to control year
    });    
     $('.materialboxed').materialbox();




    /*=================== Contact Form ===================*/
    $('form#contact-form').submit(function (event) {
    event.preventDefault();
    var contactform = $('form#contact-form');
    var formresult = $('#formresult');
    var button = $('form#contact-form button#submit');
    var formdata = $(contactform).serialize();

    $.ajax({
        type: 'POST',
        url: $(contactform).attr('action'),
        data: formdata,
        dataType: 'json',
        beforeSend: function () {
        $(button).attr('disabled', true);
        },
        success: function (response) {
        $(button).attr('disabled', false);
        $(formresult).empty();
        if (response.mail === false) {
            $("#formresult").slideUp();
            $(formresult).html(response.msg);
            $("#formresult").slideDown("slow");
        } else {
            $(formresult).html(response.msg);
            $(contactform).slideUp('slow');
        }
        },
    });
    return false;
    });

    $(".img-tooltip").on('click', function(){
       window.open('http://aioria.com.br', '_blank');
    });

    $(".img-tooltip").on('mouseover', function(){
       $(this).css({
           "cursor" : "pointer"
       });
    });

    $('#private_health_plan').change(function(){

        if ($(this).is(':checked')) $('.select-field').css('display', 'none');
        else $('.select-field').removeAttr('style');

    });


    /* ============ Sidemenu Scroll ================*/
    $('.cash-popup-inner').enscroll({
        showOnHover: false,
        verticalTrackClass: 'track3',
        verticalHandleClass: 'handle3'
    });

    $(".a-forgot").on('mouseover', function(){
        $(this).css({
           "cursor" : "pointer"
        });
    });

    $(".a-forgot").on('click', function(){
        $("#form_login").slideUp(200);

        setTimeout(function(){
            $("#forgot_form").slideDown(200);
        }, 300);
    });

    $(".a-cancel").on('mouseover', function(){
        $(this).css({
            "cursor" : "pointer"
        });
    });

    $(".a-cancel").on('click', function(){
        $("#forgot_form").slideUp(200);

        setTimeout(function(){
            $("#form_login").slideDown(200);
        }, 300);
    });

    //esse aqui e do pop up pq as vezes tem dois forms na mesma view

    $(".a-forgott").on('mouseover', function(){
        $(this).css({
            "cursor" : "pointer"
        });
    });

    $(".a-forgott").on('click', function(){
        $("#form_loginn").slideUp(200);

        setTimeout(function(){
            $("#forgot_formm").slideDown(200);
        }, 300);
    });

    $(".a-cancell").on('mouseover', function(){
        $(this).css({
            "cursor" : "pointer"
        });
    });

    $(".a-cancell").on('click', function(){
        $("#forgot_formm").slideUp(200);

        setTimeout(function(){
            $("#form_loginn").slideDown(200);
        }, 300);
    });

    var responsive = 976;

//    parte das abas
    $(".btn-next-week").on('click', function(){
        if($(window).width() <= responsive){
            $(".tabs").animate({scrollTop: '+=' + ($('.tabs').height())}, 500);
        }else{
            $(".tabs").animate({scrollLeft: '+=' + $('.tabs').width()}, 500);
        }
    });

    $(".btn-previous-week").on('click', function(){
        if($('.tabs').scrollLeft() == 0){
            if($(window).width() <= responsive){
                $(".tabs").animate({scrollTop: '-=' + ($('.tabs').height())}, 500);
            }
        }else{
            if($(window).width() <= responsive){
                $(".tabs").animate({scrollTop: '-=' + ($('.tabs').height())}, 500);
            }else{
                $(".tabs").animate({scrollLeft: '-=' + $('.tabs').width()}, 500);
            }
        }
    });

    $(".tab").on('click', function(){

        var URL = get_url();

        $("#pagi").hide(0);
        $("#div_days").html('<div class="spinner"></div><style>.spinner{width: 40px; height: 40px; margin: 100px auto; background-color: #333; border-radius: 100%; -webkit-animation: sk-scaleout 1.0s infinite ease-in-out; animation: sk-scaleout 1.0s infinite ease-in-out;}@-webkit-keyframes sk-scaleout{0%{-webkit-transform: scale(0)}100%{-webkit-transform: scale(1.0); opacity: 0;}}@keyframes sk-scaleout{0%{-webkit-transform: scale(0); transform: scale(0);}100%{-webkit-transform: scale(1.0); transform: scale(1.0); opacity: 0;}}</style>');
        var date = $(this).find(".span-date").text();
        $.ajax({
            url  : URL + 'consultas/',
            type : 'GET',
            data : {'data' : date},
            success: function(retorno){

                var $dateArray = date.split('/');
                var $today = new Date();
                var $string = (($today.getMonth() + 1) > parseInt($dateArray[1]) ? ($today.getFullYear() + 1) : $today.getFullYear()) + '-' + $dateArray.reverse().join('-') + 'T23:59:59';

                var $now = new Date($string);
                var $count = 0;
                var $output = '';

                $output += '<div id="conteudo">';

                $.each(retorno.physicians.locals_by_date, function(){

                    if (parseInt(this.time_epoch) < ($now.getTime() / 1000)) {

                        $count++;

                        if ($(window).width() < 993) {
                            $output += '<div class="col s12 m12 l6 center-align">';
                        } else {
                            $output += '<div class="col s12 m12 l6">';
                        }
                        $output += '<div class="doc-time">';

                        if ($(window).width() < 993) {
                            if (this.image_url !== null && this.image_url !== undefined && this.image_url !== "") {
                                $output += '<img src="' + this.image_url + '" alt="" />';
                            } else {
                                $output += '<img src="assets/site/images/logo_300x300.png" alt="" />';
                            }
                        } else {
                            if (this.image_url !== null && this.image_url !== undefined && this.image_url !== "") {
                                $output += '<img src="' + this.image_url + '" alt="" />';
                            } else {
                                $output += '<img src="assets/site/images/logo_300x300.png" alt="" />';
                            }
                        }

                        var $substr = this.subtitle.split(',');

                        $output += '<div class="doc-detail">';
                        $output += '<h4>' + this.title + '</h4>';
                        $output += '<ul>';
                        $output += '<li>';
                        $output += $substr.length > 1 ? ($substr.length > 2 ? ($substr[0] + ', ' + $substr[1] + ', ...') : $substr[0] + ', ' + $substr[1]) : $substr[0];
                        $output += '</li>';
                        $output += '<li>' + ((this.time_slot_for_queue) ? '<i class="fa fa-ticket"></i>' : '<i class="fa fa-clock-o"></i>');
                        $output += ' Próximo horário: ' + this.time_str.replace(":00", "h").replace(":", "h")/*.str_replace(":", "h", str_replace(":00", "h", $physician->time_str))*/;
                        $output += '</li>';
                        $output += '<li><i class="fa fa-globe"></i> ';
                        $output += 'Distância: ' + this.distance_str;
                        $output += '</li>';
                        if (retorno.private) {
                            $output += '<li><i class="fa fa-money"></i> ';
                            $output += 'Valor: ' + verificar_null(this.amount_str, 'A combinar');
                            $output += '</li>';
                        }
                        $output += '</ul>';
                        $output += '<a class="' + (retorno.auth ? 'dark-btn' : 'call-popup popup1 dark-btn');
                        $output += '" href="' + (retorno.auth ? (URL + 'medicos/' + this.id) : '#') + '" title="">MARCAR</a>';
                        $output += '</div>';
                        $output += '</div>';
                        $output += '</div>';

                    }
                });
                $output += '</div>';

                $("#div_days").html($output);

                if(retorno.physicians.locals_by_date.length > 0 && $count > 0){
                    paginate();
                }else{
                    var $html = '<h5>Ainda não existem horários livres nesse dia!</h5><br><img src="assets/site/images/logo_300x300.png" alt="" />';
                    $("#div_days").addClass("center-align").html($html);
                }
            },
            error: function(){
                var $html = '<h5>Ocorreu algum erro, não foi possivel carregar os horários livres!</h5><br><img src="assets/site/images/logo_300x300.png" alt="" />';
                $("#div_days").addClass("center-align").html($html);
            }
        });
    });

}); /*=== Document.Ready Ends Here ===*/

$(document).ready(function() {

    var URL = get_url();

    if(window.location.href == (URL + 'agendamentos')){
        if ($(window).width() > 992){
            $('#calendar').fullCalendar( 'changeView', 'month' );
        }

        if($(window).width() < 993) {
            $('#calendar').fullCalendar( 'changeView', 'basicDay' );
        }

        $("#calendar").css({
            "overflow" : "hidden",
            "width" : "100%"
        });

        $(".fc-toolbar").css({
            "width" : "100%"
        });

        $(".fc-view-container").css({
            "width" : "100%"
        });

        $(".fc-next-button").on('click', function(){
            $(".fc-view-container").css({
                "width" : "100%"
            });
        });

        $(".fc-prev-button").on('click', function(){
            $(".fc-view-container").css({
                "width" : "100%"
            });
        });
    }

    if(window.location.href == (URL + 'medicos')){

        paginate();

        var date = $(".tab").find(".active .span-date").text();
        $("#div_days").html('<div class="spinner"></div><style>.spinner{width: 40px; height: 40px; margin: 100px auto; background-color: #333; border-radius: 100%; -webkit-animation: sk-scaleout 1.0s infinite ease-in-out; animation: sk-scaleout 1.0s infinite ease-in-out;}@-webkit-keyframes sk-scaleout{0%{-webkit-transform: scale(0)}100%{-webkit-transform: scale(1.0); opacity: 0;}}@keyframes sk-scaleout{0%{-webkit-transform: scale(0); transform: scale(0);}100%{-webkit-transform: scale(1.0); transform: scale(1.0); opacity: 0;}}</style>');
        $.ajax({
            url: URL + 'consultas/',
            type: 'GET',
            data: {'data': date},
            success: function (retorno) {

                var $dateArray = date.split('/');
                var $today = new Date();
                var $string = (($today.getMonth() + 1) > parseInt($dateArray[1]) ? ($today.getFullYear() + 1) : $today.getFullYear()) + '-' + $dateArray.reverse().join('-') + 'T23:59:59';

                var $now = new Date($string);
                var $count = 0;
                var $output = '';

                $output += '<div id="conteudo">';

                $.each(retorno.physicians.locals_by_date, function () {

                    if (parseInt(this.time_epoch) < ($now.getTime() / 1000)) {

                        $count++;

                        if ($(window).width() < 993) {
                            $output += '<div class="col s12 m12 l6 center-align">';
                        } else {
                            $output += '<div class="col s12 m12 l6">';
                        }
                        $output += '<div class="doc-time">';

                        if ($(window).width() < 993) {
                            if (this.image_url !== null && this.image_url !== undefined && this.image_url !== "") {
                                $output += '<img src="' + this.image_url + '" alt="" />';
                            } else {
                                $output += '<img src="assets/site/images/logo_300x300.png" alt="" />';
                            }
                        } else {
                            if (this.image_url !== null && this.image_url !== undefined && this.image_url !== "") {
                                $output += '<img src="' + this.image_url + '" alt="" />';
                            } else {
                                $output += '<img src="assets/site/images/logo_300x300.png" alt="" />';
                            }
                        }

                        var $substr = this.subtitle.split(',');

                        $output += '<div class="doc-detail">';
                        $output += '<h4>' + this.title + '</h4>';
                        $output += '<ul>';
                        $output += '<li>';
                        $output += $substr.length > 1 ? ($substr.length > 2 ? ($substr[0] + ', ' + $substr[1] + ', ...') : $substr[0] + ', ' + $substr[1]) : $substr[0];
                        $output += '</li>';
                        $output += '<li>' + ((this.time_slot_for_queue) ? '<i class="fa fa-ticket"></i>' : '<i class="fa fa-clock-o"></i>');
                        $output += ' Próximo horário: ' + this.time_str.replace(":00", "h").replace(":", "h")/*.str_replace(":", "h", str_replace(":00", "h", $physician->time_str))*/;
                        $output += '</li>';
                        $output += '<li><i class="fa fa-globe"></i> ';
                        $output += 'Distância: ' + this.distance_str;
                        $output += '</li>';
                        if (retorno.private) {
                            $output += '<li><i class="fa fa-money"></i> ';
                            $output += 'Valor: ' + verificar_null(this.amount_str, 'A combinar');
                            $output += '</li>';
                        }
                        $output += '</ul>';
                        $output += '<a class="' + (retorno.auth ? 'dark-btn' : 'call-popup popup1 dark-btn');
                        $output += '" href="' + (retorno.auth ? (URL + 'medicos/' + this.id) : '#') + '" title="">MARCAR</a>';
                        $output += '</div>';
                        $output += '</div>';
                        $output += '</div>';

                    }

                });
                $output += '</div>';

                $("#div_days").html($output);

                if(retorno.physicians.locals_by_date.length > 0 && $count > 0){
                    paginate();
                }else{
                    var $html = '<h5>Ainda não existem horários livres nesse dia!</h5><br><img src="assets/site/images/logo_300x300.png" alt="" />';
                    $("#div_days").addClass("center-align").html($html);
                }
            },
            error: function(){
                var $html = '<h5>Ocorreu algum erro, não foi possivel carregar os horários livres!</h5><br><img src="assets/site/images/logo_300x300.png" alt="" />';
                $("#div_days").addClass("center-align").html($html);
            }
        });
    }

});

$(window).resize(function(){
    if ($(window).width() > 992){
        $('#calendar').fullCalendar( 'changeView', 'month' );
    }

    if($(window).width() < 993) {
        $('#calendar').fullCalendar( 'changeView', 'basicDay' );
    }

    $(".fc-view-container").css({
        "width" : "100%"
    });
});

//essa paginação é para os medicos

function paginate(){
    $("#pagi").show();
    if($(window).width() < 993){
        var mostrar_por_pagina = 4;
    }else{
        var mostrar_por_pagina = 8;
    }
    var numero_de_itens    = $(".doc-time").size();
    var numero_de_paginas  = Math.ceil(numero_de_itens / mostrar_por_pagina);

    if($("#pagi").size() > 0) {
        $("#pagi").empty().html("");
    }

    if($(".doc-time").size() > mostrar_por_pagina){
        $('#pagi').append('<div class="controls"></div><input id="current_page" type="hidden"><input id="mostrar_por_pagina" type="hidden">');
        $('#current_page').val(0);
        $('#mostrar_por_pagina').val(mostrar_por_pagina);

        var nevagacao = '';
        var link_atual = 0;

        while (numero_de_paginas > link_atual) {
            if(link_atual == 0){
                var id = 'page_' + link_atual;
                nevagacao += '<li class="waves-effect active" id="' + id + '_li"><a class="page" onclick="ir_para_pagina(' + link_atual + ', ' + id + ')" id="' + id + '" longdesc="' + link_atual + '">' + (link_atual + 1) + '</a></li>'; link_atual++;
            }else{
                var id = 'page_' + link_atual;
                nevagacao += '<li class="waves-effect" id="' + id + '_li"><a class="page" onclick="ir_para_pagina(' + link_atual + ', ' + id + ')" id="' + id + '" longdesc="' + link_atual + '">' + (link_atual + 1) + '</a></li>'; link_atual++;
            }
        }

        $('.controls').html("<div class='paginacao'><ul class='pagination'>"+nevagacao+"</ul></div>");
        $('.controls .page:first').addClass('active');
        $('#conteudo').children().css('display', 'none');
        $('#conteudo').children().slice(0, mostrar_por_pagina).css('display', 'block');
    }
}

function ir_para_pagina(numero_da_pagina, elem) {
    $("html, body").animate({scrollTop: $("#header_days").offset().top - 90}, 750);
    $("li").removeClass("active");
    $("#" + $(elem).attr("id") + "_li").addClass("active");
    var mostrar_por_pagina = parseInt($('#mostrar_por_pagina').val(), 0);
    inicia = numero_da_pagina * mostrar_por_pagina;
    end_on = inicia + mostrar_por_pagina;
    $('#conteudo').children().css('display', 'none').slice(inicia, end_on).css('display', 'block');
    $('.page[longdesc=' + numero_da_pagina+ ']').addClass('active').siblings('.active').removeClass('active');
    $('#current_page').val(numero_da_pagina);
}

function verificar_null(obj, msg){
    if(obj !== null && obj !== undefined && obj !== ""){
        return obj;
    }else{
        return msg;
    }
}

function get_url(){
    var page = window.location.protocol + '//' + window.location.host + '/';
    return page;
}

jQuery(window).load(function() {
    "use strict";
    $(".pageloader").fadeOut("slow");
}); /*=== Document.Ready Ends Here ===*/

//funcoes de validação de formularios

$(function(){

    $("#formulario_cadastro").validate({
        rules : {
            name : {
                required : true
            },
            email : {
                required : true,
                email : true
            },
            password : {
                required : true
            },
            password_confirmation : {
                required : true,
                equalTo  : "#password"
            }
        },
        messages : {
            name : {
                required : ""
            },
            email : {
                required : "",
                email : ""
            },
            password : {
                required : ""
            },
            password_confirmation : {
                required : "",
                equalTo : ""
            }
        }
    });

    $("#formulario_medicos").validate({
       rules : {
            name : {
                required : true
            },
           email : {
                required : true,
                email : true
           },
           password : {
               required : true
           },
           password_confirmation : {
               required : true,
               equalTo  : "#password"
           }
       },
       messages : {
            name : {
                required : ""
            },
           email : {
                required : "",
                email : ""
           },
           password : {
               required : ""
           },
           password_confirmation : {
               required : "",
               equalTo : ""
           }
       }
    });


    $("#formulario_contato").validate({
        rules : {
            name : {
                required : true
            },
            phone : {
                required : true,
                number : true
            },
            subject : {
                required : true
            },
            email : {
                required : true,
                email : true
            },
            msg : {
                required : true
            }
        },
        messages : {
            name : {
                required : ""
            },
            phone : {
                required : "",
                number : ""
            },
            subject : {
                required : ""
            },
            email : {
                required : "",
                email : ""
            },
            msg : {
                required : ""
            }
        },
        errorElement : 'div',
        errorPlacement : function(error){

        }
    });

});


function hide_alert(){
    //esconder a alert maior
    setTimeout(function(){
        $(".alert-dismiss-facebook").slideUp(500);
    }, 12000);
}
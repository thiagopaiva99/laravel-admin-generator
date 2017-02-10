@extends('site.page')

@section('page.content')

    <section>
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col column  s12 m12 l12">
                        <div class="dismiss-alert">
                            @include("flash::message")
                        </div>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--{{ dd($appointments) }}--}}

    <section class="block">
        <div class="container">
            <div class="row">
                <div class="col s12 m12">
                    <div class="modern-title">
                        <h2 class="">Meus <span>Agendamentos</span></h2>
                    </div>

                    <div id="conteudo">

                        @foreach($appointments as $appointment)
                            <div class="col l4 m6 s12 divs">
                                <div class="service" style="height: 300px; text-align: center;">
                                    <div style="">
                                        <img src="{{ (isset($appointment->timeSlotLocal()->first()->image_url) && $appointment->timeSlotLocal()->first()->image_url) ? $appointment->timeSlotLocal()->first()->image_url : asset('assets/site/images/logo_300x300.png') }}" alt="">
                                    </div>
                                    <?php
                                        $data = explode(' ', $appointment->appointment_start);
                                        $date = explode('-', $data[0]);
                                        $hour = explode(':', $data[1]);

                                        $dateCarbon = Carbon\Carbon::create($date[0], $date[1], $date[2], $hour[0], $hour[1], $hour[2], 'America/Sao_Paulo');
                                    ?>
                                    <div class="service-hover">
                                        <i class="icon-drip2"></i>
                                        <span><strong>{{ $appointment->subtitle }}</strong></span>
                                        <h5>
                                            @if (isset($appointment->timeSlot()->first()->queue_type))
                                                <span style="font-size: inherit">
                                                    <i class="fa {{ $appointment->timeSlot()->first()->queue_type == 2 ? 'fa-clock-o' : 'fa-ticket' }}"></i>
                                                </span>
                                            @endif
                                            {{ date("d/m/Y", strtotime($data[0])) }} às {{ date("H:i", strtotime($data[1])) }}
                                        </h5>
                                        <h5>{{ $appointment->title }}</h5>
                                    </div>

                                    @if(Carbon\Carbon::now() <= $dateCarbon)
                                        <a href="{{ url('agendamentos/cancelar/'.$appointment->id) }}" title="">Cancelar <i class="fa fa-remove"></i></a>
                                    @elseif(isset($appointment->timeSlotLocal()->first()->id))
                                        <a href="{{ url('medicos/'.$appointment->timeSlotLocal()->first()->id) }}" title="">Nova Consulta <i class="fa fa-repeat"></i></a>
                                    @endif

                                </div><!-- Service -->
                            </div>
                        @endforeach

                    </div>

                    <div class="row"><div class="col s12 m12 l12 center-align"><div id="pagi"></div></div></div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('page.scripts')

    @include('site.pages.calendar')

    <script>

        $(document).ready(function() {

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                weekMode: 'liquid',
                lang: 'pt-br',
                defaultDate: new Date(),
                buttonIcons:false,
                eventLimit: true, // allow "more" link when too many events
                timeFormat: 'H:mm',
                events: [
                    @foreach($appointments as $a)
                    {
                        "title" : "{{ $a->title }}",
                        "start" : "{{ $a->appointment_start }}",
                        "end" : "{{ $a->appointment_end }}"
                    },
                    @endforeach
                ]
            });

            var mostrar_por_pagina = 12;
            var numero_de_itens    = $(".divs").size();
            var numero_de_paginas  = Math.ceil(numero_de_itens / mostrar_por_pagina);

            if($(".divs").size() > mostrar_por_pagina){
                $('#pagi').append('<div class=controls></div><input id=current_page type=hidden><input id=mostrar_por_pagina type=hidden>');
                $('#current_page').val(0);
                $('#mostrar_por_pagina').val(mostrar_por_pagina);

                var nevagacao = '';
                var link_atual = 0;
                while (numero_de_paginas > link_atual) {
                    var id = 'page_' + link_atual;
                    if(link_atual == 0){
                        nevagacao += '<li class="waves-effect active" id="' + id + '_li"><a class="page" onclick="ir_para_pagina_here(' + link_atual + ', ' + id + ')" id=' + id + ' longdesc="' + link_atual + '">' + (link_atual + 1) + '</a></li>';
                    }else{
                        nevagacao += '<li class="waves-effect" id="' + id + '_li"><a class="page" onclick="ir_para_pagina_here(' + link_atual + ', ' + id + ')" id=' + id + ' longdesc="' + link_atual + '">' + (link_atual + 1) + '</a></li>';
                    }

                    link_atual++;
                }

                $('.controls').html("<div class='paginacao'><ul class='pagination pagination-sm'>"+nevagacao+"</ul></div>");
                $('.controls .page:first').addClass('active');
                $('#conteudo').children().css('display', 'none');
                $('#conteudo').children().slice(0, mostrar_por_pagina).css('display', 'block');
                $("#pagi").css({"display" : "block"});
            }
        });

        function ir_para_pagina_here(numero_da_pagina, elem) {
            $("html, body").animate({scrollTop : ($("#conteudo").offset().top - 120)}, 500);
            $("li").removeClass("active");
            $("#" + $(elem).attr("id") + "_li").addClass("active");
            var mostrar_por_pagina = parseInt($('#mostrar_por_pagina').val(), 0);
            inicia = numero_da_pagina * mostrar_por_pagina;
            end_on = inicia + mostrar_por_pagina;
            $('#conteudo').children().css('display', 'none').slice(inicia, end_on).css('display', 'block');
            $('.page[longdesc=' + numero_da_pagina+ ']').addClass('active').siblings('.active').removeClass('active');
            $('#current_page').val(numero_da_pagina);
        }
    </script>

@stop
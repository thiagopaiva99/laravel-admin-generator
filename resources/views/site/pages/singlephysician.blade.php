@extends('site.page')

@section('page.content')

    <section>
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col column s12 m12 l12">
                        <div class="staff-detail">
                            <div class="member-introduction">
                                <div class="member-wrapper">
                                    <div class="staff-img">
                                        <img src="{{ $physician['place']->image_url ? $physician['place']->image_url : url('assets/site/images/avatar_02.png') }}" alt="{{ $physician['place']->user->name }}" width="100%" height="auto" style="display: block;" />
                                    </div>
                                    <div class="member-detail">
                                        <i>{{ $physician['place']->name }}</i>
                                        <h2>{{ $physician['place']->user->name }}</h2>
                                        <span>
                                            <?php $first = true; $count = 0;
                                            foreach($physician['place']->specializations as $specialization) {
                                                $count++;
                                                if($first) {
                                                    $first = false;
                                                    echo trim($specialization->name);
                                                } else {
                                                    if ($count == sizeof($physician['place']->specializations)) echo ' e ' . trim($specialization->name);
                                                    else echo ', ' . trim($specialization->name);
                                                }
                                            } ?>
                                        </span>
                                        <span>
                                            <ul class="info-list">
                                                <li>
                                                    <strong>Endereço:</strong>
                                                    <span>
                                                        <?php header('Content-type: text/html; charset=UTF-8'); ?>
                                                        {{ str_replace('SÃ¡bado', 'Sábado', $physician['place']->address) }}
                                                        <br>
                                                        <a href="{{ $map }}" target="_blank">Ver mapa</a>
                                                    </span>
                                                </li>
                                                @if($physician['place']->phone)
                                                    <li><strong>Telefone:</strong><span>{{ $physician['place']->phone }}</span></li>
                                                @endif
                                                @if($physician['place']->amount_str)
                                                    <li><strong>Valor da consulta:</strong><span>{{ $physician['place']->amount_str }}</span></li>
                                                @endif
                                                <li>
                                                    <strong>Próximo horário:</strong>
                                                    <span>
                                                        <?php $time_label = explode(' - ', $physician['place']->next_available_appointment_str) ?>
                                                        {{ str_replace(" De ", " de ", ucwords(utf8_encode(strftime("%A, %d de %B", $physician['place']->next_available_appointment_epoch)))) }}
                                                        {!!
                                                            $physician['place']->next_available_appointment_substr ?
                                                            '<br>'.$physician['place']->next_available_appointment_substr :
                                                            (isset($time_label[1]) ? ' às '.str_replace("00", "", str_replace(":", "h", $time_label[1])) : '')
                                                         !!}
                                                    </span>
                                                </li>
                                                @if($physician['place']->exams && sizeof($physician['place']->exams))
                                                    <li>
                                                        <strong>Exames disponíveis:</strong>
                                                        <span>
                                                            <?php $first = true;
                                                            foreach($physician['place']->exams as $exam) {
                                                                if($first) {
                                                                    $first = false;
                                                                    echo trim($exam->name);
                                                                } else echo ', ' . trim($exam->name);
                                                            } ?>
                                                        </span>
                                                    </li>
                                                @endif
                                                @if(!\Auth::user()->private_health_plan && $physician['place']->healthPlans && sizeof($physician['place']->healthPlans) > 0)
                                                    <li>
                                                        <strong>Plano(s) de saúde:</strong>
                                                        <span>
                                                            <?php $first = true;
                                                            foreach($physician['place']->healthPlans as $health_plan) {
                                                                if($first) {
                                                                    $first = false;
                                                                    echo trim($health_plan->name);
                                                                } else echo ', ' . trim($health_plan->name);
                                                            } ?>
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </span>
                                        <a class="{{ $button_class }}" href="{{ $button_href }}" title="">
                                            MARCAR PARA PRÓXIMO HORÁRIO
                                        </a>
                                    </div><!-- Member Detail -->
                                </div>
                                @if($next && sizeof($next))

                                    <div class="modern-title">
                                        <h2><br>Outros <span>Horários</span></h2>
                                    </div>
                                    <div id="schedule" class="toggle style2">

                                        <?php $current_day = "Unknown"; ?>

                                        @foreach($next as $hour)
                                            @if($hour->time_slot_for_queue != \Auth::user()->private_health_plan)
                                                @if($hour->time_header != $current_day)
                                                    @if($current_day != "Unknown")

                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endif

                                                    <div class="toggle-item">
                                                        <h3><i class="fa fa-calendar"></i>
                                                            <span>{{ str_replace("á","Á",str_replace("ç","Ç",$hour->time_header)) }}</span>
                                                        </h3>
                                                        <div class="content">
                                                            <div class="simple-text">

                                                    <?php $current_day = $hour->time_header ?>

                                                @endif

                                                <span class="member-wrapper" style="border-top: 3px solid green">
                                                    @if($hour->time_slot_for_queue)
                                                        <i class="fa fa-ticket"></i>
                                                    @else
                                                        <i class="fa fa-clock-o"></i>
                                                    @endif
                                                     <a href="{{
                                                        Auth::check() ? url('agendar/'.$physician['place']->id.'/'.$hour->time_slot_id.'/'.$hour->time_epoch.'/'.$hour->time_slot_detail_id) : "#"
                                                     }}" class="{{ Auth::check() ? 'clock-o' : 'call-popup popup1' }}">
                                                        {{ $hour->time_label }}
                                                         <div class="center-align">{{ $hour->time_subtitle }}</div>
                                                     </a>
                                                </span>

                                            @endif
                                        @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                                @if($physician['other_places'] && sizeof($physician['other_places']) > 0)

                                    <div class="modern-title">
                                        <h2><br>Outros <span>Locais</span> de <span>Atendimento</span></h2>
                                    </div>
                                    <div id="toggle" class="toggle style2">

                                        @foreach($physician['other_places'] as $place)

                                            <div class="toggle-item">
                                                <h3><i class="icon-stethoscope"></i>
                                                    <span>
                                                        <a href="{{ url('medicos/'.$place->id) }}">
                                                            {{ $place->name }}
                                                        </a>
                                                    </span>
                                                </h3>
                                                <div class="content">
                                                    <div class="simple-text">
                                                        <p>
                                                            <b>Endereço:</b> {{ $place->address }}
                                                            <br>
                                                            <b>Valor da consulta:</b> {{ $place->amount_str }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach

                                    </div>

                                @endif
                            </div><!-- Member Introduction -->
                        </div><!-- Staff Detail -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
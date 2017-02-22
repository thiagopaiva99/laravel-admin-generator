@extends('layouts.app')

@section('content')

    <br>

    <div class="col-md-12">
        @if(\Auth::user()->locals->pluck("name","id")->count() >= 1)
            <div class="">
                {!! Form::open(["class" => "form-inline","method" => "GET", "style" => "width: 100% !important;"]) !!}
                    <div id="header-searches">
                        <div class="form-group">
                            {!! Form::label("local", "Local de Atendimento:") !!}<br>
                            {!! Form::select("local", $locals, isset($_GET['local']) ? $_GET['local'] : null, ["class" => "form-control select_plans show-menu-arrow", "data-live-search" => "true", "title" => "Selecione o local", "data-actions-box" => "true"]) !!}
                        </div>

                        <div class="form-group">
                            {{ Form::label('', 'Buscar dia:') }} <br>
                            {!! Form::date('search_day', null, ['class' => 'form-control', 'placeholder' => 'Avançar o calendário']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="content">
        <div class="clearfix"></div>
        <!-- Nav Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right" role="tablist">
                @if(isset($local))
                    @if(\Auth::user()->locals->pluck("name","id")->count() > 0)
                        <li role="presentation" class="" style=""><a href="#consultas" aria-controls="consultas" role="tab" data-toggle="tab">Consultas</a></li>
                        <li role="presentation" class="" style=""><a href="#horarios" aria-controls="horarios" role="tab" data-toggle="tab">Horários</a></li>
                    @endif
                @endif
                <li class="pull-left header" id="title_box"><i class="fa fa-calendar"></i> Calendário</li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active {{ (\Auth::user()->locals->pluck("name","id")->count() < 1) ? "text-center" : "" }}" id="consultas">
                    <div class="box">
                        <div class="box-body text-center">
                            @if(\Auth::user()->locals->pluck("name","id")->count() < 1)
                                <h3>Você não tem locais de consulta, aguarde sua clinica cadastrar um!</h3>
                                <img src="{{ asset('assets/site/images/logo_300x300.png') }}" alt="">
                            @else
                                @if(isset($local))
                                    <div class="col-md-12" id="alertar">
                                        @include("flash::message-admin")
                                    </div>

                                    <hr>

                                    <div id="monthCalendar"></div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane {{ (\Auth::user()->locals->pluck("name","id")->count() < 1) ? "text-center" : "" }}" id="horarios">
                    @if(\Auth::user()->locals->pluck("name","id")->count() < 1)
                        <h3>Você não tem locais de consulta, aguarde sua clinica cadastrar um!</h3>
                        <img src="{{ asset('assets/site/images/logo_300x300.png') }}" alt="">
                    @else
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="pull-left" style="padding-top: 10px;">
                                    <span style="margin-right: 15px;"><i class="fa fa-square text-red"></i> Horário fechado</span>
                                    <span style="margin-right: 15px;"><i class="fa fa-square" style="color: #efefef"></i> Horário padrão</span>
                                    <span style="margin-right: 15px;"><i class="fa fa-square text-green"></i> Horário customizado</span>
                                </div>
                                <div class="pull-right btn-group">
                                    <a class="btn btn-default btn-refresh-calendar">Recarregar Horários</a>
                                    <a href="{{ route('admin.timeSlots.create') }}" class="btn btn-default">Inserir horário</a>
                                    <a href="{{ route('admin.closedDates.create') }}" class="btn btn-default">Inserir horário fechados</a>
                                </div>

                                <div class="clearfix"></div>

                                <div class="alerta">
                                    Estamos carregando seus horários, isso pode levar alguns segundos!
                                </div>

                                <div class="col-md-12">
                                    @include("flash::message-admin")
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="weekCalendar"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modalTimeSlot">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Inserir novo horário de atendimento</h4>
                </div>
                <div class="modal-body">
                    <style>
                        .toggle{ margin-top: -10px; }
                    </style>

                    <?php $class = Auth::user()->user_type == \App\Models\User::UserTypeClinic ? "col-sm-3" : "col-sm-4"; ?>

                    <!-- Local Field -->
                    <div class="form-group {{ $class }}">
                        {!! Form::label('local_id', 'Local:') !!}
                        {!! Form::select('local_id', $locals, isset($local) ? $local : null, ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>

                    <!-- Slot Type Field -->
                    <div class="form-group {{ $class }}">
                        {!! Form::label('slot_type', 'Horário:') !!}
                        {!! Form::select('slot_type', $slotTypes, null, ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>

                    <!-- Slot Date Field -->
                    <div class="form-group {{ $class }}" id="div_day_slot">
                        {!! Form::label('slot_date', 'Dia:') !!}
                        {!! Form::date('slot_date', null, ['class' => 'form-control']) !!}
                    </div>

                    @if(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
                        {{--doctor--}}
                        <div class="form-group {{ $class }}">
                            {!! Form::label('', 'Selecione o médico:') !!}
                            {!! Form::select('user_id', $doctors, null, ['class' => 'form-control select_plans show-menu-arrow', 'data-live-search' => 'true', 'title' => 'Selecione o médico', 'data-actions-box' => 'true']) !!}
                        </div>
                    @endif

                    <!-- Day Of Week Field -->
                    <div class="form-group {{ $class }}" id="div_day_of_week">
                        {!! Form::label('day_of_week', 'Dia da Semana:') !!}
                        <select class="form-control" id="day_of_week" name="day_of_week">
                            <option value="1">Segunda-feira</option>
                            <option value="2">Ter&ccedil;a-feira</option>
                            <option value="3">Quarta-feira</option>
                            <option value="4">Quinta-feira</option>
                            <option value="5">Sexta-feira</option>
                            <option value="6">S&aacute;bado</option>
                        </select>
                    </div>

                    <!-- Slot Time Start Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('slot_time_start', 'Início:') !!}
                        {!! Form::time('slot_time_start', (isset($timeSlot) && isset($timeSlot->slot_time_start)) ? $timeSlot->slot_time_start->format('H:i') : (isset($start) && $start != null ? $start->format('H:i') : null), ['class' => 'form-control', 'required' => 'true', 'max' => '59']) !!}
                    </div>

                    <!-- Slot Time End Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('slot_time_end', 'Final:') !!}
                        {!! Form::time('slot_time_end', (isset($timeSlot) && isset($timeSlot->slot_time_end) )? $timeSlot->slot_time_end->format('H:i') : (isset($end) && $end !== null ? $end->format('H:i') : null), ['class' => 'form-control', 'required' => 'true']) !!}
                    </div>

                    <!-- <div class="form-group col-sm-2">
                        {!! Form::label('', 'Particular:') !!}
                        <div class="checkbox">
                            {!! Form::checkbox('private', isset($details) && $details[0]->private == 1 ? true : false, isset($details) && $details[0]->private == 1 ? true : false, ['data-toggle' => 'toggle', 'data-onstyle' => 'success', 'data-height' => '35', 'data-width' => '100', 'data-on' => 'SIM', 'data-off' => 'NÃO', 'id' => 'private']) !!}
                        </div>
                    </div> -->

                    <!-- <div class="form-group col-sm-4 plan_select">
                        {{ Form::label('', 'Selecione planos de saúde: ') }}
                        {{ Form::select('medics_select', [], null, ['class' => 'form-control select_plans show-menu-arrow', 'multiple' => 'multiple', 'data-live-search' => 'true', 'title' => 'Selecione os planos de saúde', 'data-actions-box' => 'true']) }}
                    </div> -->

                    <div class="form-group col-sm-4 slot_count">
                        {{ Form::label('', 'Quantidade de Atendimentos:') }}
                        {{ Form::number('slot_count', isset($details) ? $details[0]->slot_count : null, ['class' => 'form-control', 'placeholder' => 'Quantidade de pessoas que serão atendidas']) }}
                    </div>

                    <input type="hidden" name="plans_selected" value="">
                    <input type="hidden" name="value_select" value="1">
                    <input type="hidden" name="start_date" value="">
                    <input type="hidden" name="end_date" value="">

                    <div class="clearfix"></div>

                    <hr>

                    <div>
                        <input type="hidden" name="start_datetime">
                        <input type="hidden" name="end_datetime">

                        @if(Auth::user()->user_type == \App\Models\User::UserTypeDoctor)
                            <input type="hidden" name="medic_id" value="{{ Auth::user()->id }}">
                        @else
                            <input type="hidden" name="medic_id" value="">
                        @endif
                    </div>

                    <!-- Submit Field -->
                    <div class="form-group text-center">
                        <button class="pull-left btn btn-danger btn-save-closed" user-id="{{ Auth::user()->id }}">Fechar Horário</button>
                        <div class="btn-group text-center">
                            <button type="button" class="btn btn-success btn-save-timeslot" disabled>Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="eventContent" title="Event Details" style="display:none; position: absolute; z-index: 100; width: 200px; background: #fff; border: 1px solid rgba(0, 0, 0, 0.5);">
        <header style="width: 100%; background: #00A65A; padding: 5px; color: white;">Horário <button class="btn btn-default btn-xs pull-right" onclick="close_popup();"><i class="fa fa-remove"></i></button> </header>
        <span style="margin-top: 10px; display: block;">
            <center>
                <div class="btn-group">
                    <a href="" class="btn btn-xs btn-success" id="a_time_slot">Editar</a>
                    <button class="btn btn-xs btn-danger" onclick="delete_time_slot()">Apagar</button>
                    <button class="btn btn-xs btn-bitbucket" id="close_time_slot" onclick="close_all_slot_doctor()">Fechar</button>
                </div>

                <input type="hidden" name="time_slot_id" value="">
                <input type="hidden" name="close_slot_start" value="">
                <input type="hidden" name="close_slot_end" value="">

                <br><br>
            </center>
        </span>
    </div>
@endsection

@section('scripts')
    {!! $calendar->script() !!}

    <script type="text/javascript">
        $(document).ready(function() {

            $(function() {
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var lastTab = $(this).attr('href');
                    $.cookie('calendar_last_tab', lastTab);
                    $('.fc-today-button').click();



                    if(lastTab === '#consultas'){
                        $("#title_box").html("<i class='fa fa-calendar'></i> Calendário");
                    }else{
                        $("#title_box").html("<i class='fa fa-clock-o'></i> Horários");
                    }
                });

                var lastTab = $.cookie('calendar_last_tab');
                if (lastTab) {
                    $('[href="' + lastTab + '"]').tab('show');
                    $('.fc-today-button').click();
                }
            });


            $('#local').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $('select[name=private_type]').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $("#monthCalendar").fullCalendar({
                eventSources: [
                    {
                        url : '/admin/calendar/feed-calendar',
                        type : 'GET',
                        color: '#571C36',
                        textColor: 'white',
                        data: {
                            local: $("select[name=local]").val()
                        },
                    }
                ],
                eventClick: function(calEvent, jsEvent, view) {
                    window.location.href = "//" + $(location).attr('host') + "/admin/appointments/" + calEvent.id + "";
                }
            });

            $('#weekCalendar').fullCalendar({
                defaultView: 'agendaWeek',
                timezone: 'local',
                hiddenDays: [
                  0
                ],
                eventSources : [
                    {
                        url: '/admin/calendar/feed',
                        type: 'GET',
                        data : {
                            tipo : 1,
                            local : $("#local").val()
                        },
                        color: '#ededed',   // a non-ajax option
                        textColor: 'black', // a non-ajax option
                        className: 'semanal',
                        timeout: 25000,
                        success: function(data){
                        },
                        error: function(data){
                            $(".alerta").text("Infelizmente não foi possivel carregar seus horários, tente <strong>recarregar</strong> a página!");
                        }
                    },
                    {
                        url: '/admin/calendar/feed',
                        type: 'GET',
                        data : {
                            tipo : 2,
                            local : $("#local").val()
                        },
                        color: '#571C36',   // a non-ajax option
                        textColor: 'white', // a non-ajax option
                        className: 'especifico',
                        timeout: 25000,
                        error: function(data){
                            $(".alerta").text("Infelizmente não foi possivel carregar seus horários, tente <strong>recarregar</strong> a página!");
                        }
                    },
                    {
                        url: '/admin/calendar/feed-closed-dates',
                        type: 'GET',
                        data: {
                            local : $("#local").val()
                        },
                        color: '#ff4d4d',   // a non-ajax option
                        textColor: 'white', // a non-ajax option
                        className: 'closed',
                        timeout: 5000,
                        success: function(data){
                        },
                        error: function(data){
                            $(".alerta").slideUp(450);

                            $("#weekCalendar").fullCalendar('selectable', 'true');
                            $(".btn-save-closed, .btn-save-timeslot").removeAttr('disabled');
                        }
                    }

                ],
                eventRender: function(event, element) {
                    $(".alerta").slideUp(450);

                    $("#weekCalendar").fullCalendar('selectable', 'true');
                    $(".btn-save-closed, .btn-save-timeslot").removeAttr('disabled');
                },
                views: {
                    agenda: {
                        allDaySlot: false,
                        slotDuration: '<?php echo $slotDuration ?>',
                        minTime: "00:00:00",
                        maxTime: "23:00:00"
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    var data = date._d.toString().split(" ")[0];
                    var day = 0;

                    if(data === "Sun"){
                        day = 0;
                    }else if(data === "Mon"){
                        day = 1;
                    }else if(data === "Tue"){
                        day = 2;
                    }else if(data === "wed"){
                        day = 3;
                    }else if(data === "Thu"){
                        day = 4;
                    }else if(data === "Fri"){
                        day = 5;
                    }else if(data === "Sat"){
                        day = 6;
                    }

                    $('select[name=day_of_week] option').removeAttr('selected').filter('[value=' + day + ']').attr('selected', true);
                },
                eventClick: function(calEvent, jsEvent, view) {
                    var elem    = jsEvent.currentTarget;
                    var classes = elem.firstChild.innerHTML;
                    var edge    = false;
                    var URL     = get_url();

                    if(jsEvent.toElement === null) {
                        edge = true;

                        elem = jsEvent.currentTarget;
                        classes = elem.firstChild.innerHTML;
                    }

                    if(!edge){
                        if(elem.className === 'fa fa-remove'){

                            if(elem.parentElement.parentElement.parentElement.classList.contains("closed")){
                                $.ajax({
                                    url  : URL + 'admin/closedDates/deletar/' + calEvent.id,
                                    type : 'get',
                                    data : {},
                                    success: function(data){
                                        if(data == "deletou"){
                                            $("#weekCalendar").fullCalendar("removeEvents", calEvent.id);
                                            $("#weekCalendar").fullCalendar("rerenderEvents");
                                        }
                                    }, error: function(data){
                                        swal({
                                            title: "Ocorreu um erro ao buscar os horários",
                                            type: "error",
                                            timer: 1500,
                                            showConfirmButton: false
                                        })
                                    }
                                });
                            }


                        }else{
                            if($(this).hasClass('closed')){
                                window.location.href = "//" + $(location).attr('host') + "/admin/closed-dates-destroy/" + calEvent.id;
                            }else{
                                $('#eventContent').offset({ top: jsEvent.pageY, left:  jsEvent.pageX}).fadeIn();

                                $("#a_time_slot").attr({ "href" : "//" + $(location).attr('host') + "/admin/timeSlots/" + calEvent.id + '/edit' });

                                $("input[name=close_slot_start]").val(calEvent.start._i);
                                $("input[name=close_slot_end]")  .val(calEvent.end._i);
                                $("input[name=time_slot_id]")    .val(calEvent.id);

                                $("#eventContent > div").html('<center><br><i class="fa fa-spinner fa-spin"></i> Carregando...<br><br></center>');

                                $.ajax({
                                    url  : URL + 'admin/get-details-by-id',
                                    type : 'GET',
                                    data : { time_slot_id: calEvent.id },
                                    success: function(data){
                                        var html = '<br>';

                                        $.each(data, function(key){
                                            if(data[key].health_plan_id != 0) {
                                                html += '<p style="margin-top: 5px; margin-left: 10px;">';
                                                html += '<span>' + data[key].name + '</span>';
                                                html += '<button class="btn btn-xs btn-danger pull-right" style="margin-right: 10px;" onclick="fechar_plano(' + data[key].id + ', ' + data[key].time_slot_id + ', \'' + calEvent.start._i + '\');"><i class="fa fa-remove"></i></button>'
                                                html += '</p>';
                                            }else{
                                                html += '<p style="margin-top: 5px; margin-left: 10px;">';
                                                html += 'Horário privado sem planos';
                                                html += '</p>';
                                            }
                                        });

                                        $("#eventContent > div").html(html);
                                    }
                                });
                            }
                        }
                    }else{
                        if(classes.indexOf("fa-remove") > 0){
                            if(elem.classList.contains("closed")){
                                $.ajax({
                                    url  : URL + 'admin/closedDates/deletar/' + calEvent.id,
                                    type : 'get',
                                    data : {},
                                    success: function(data){
                                        if(data == "deletou"){
                                            $("#weekCalendar").fullCalendar("removeEvents", calEvent.id);
                                            $("#weekCalendar").fullCalendar("rerenderEvents");
                                        }
                                    }, error: function(data){
                                        swal({
                                            title: "Ocorreu um erro ao buscar os horários",
                                            type: "error",
                                            timer: 1500,
                                            showConfirmButton: false
                                        })
                                    }
                                });
                            }
                        }else{
                            if($(this).hasClass('closed')){
                                window.location.href = "//" + $(location).attr('host') + "/admin/closed-dates-destroy/" + calEvent.id;
                            }else{
                                $('#eventContent').offset({ top: jsEvent.pageY, left:  jsEvent.pageX}).fadeIn();

                                $("#a_time_slot").attr({ "href" : "//" + $(location).attr('host') + "/admin/timeSlots/" + calEvent.id + '/edit' });

                                $("input[name=close_slot_start]").val(calEvent.start._i);
                                $("input[name=close_slot_end]")  .val(calEvent.end._i);
                                $("input[name=time_slot_id]")    .val(calEvent.id);

                                $("#eventContent > div").html('<center><br><i class="fa fa-spinner fa-spin"></i> Carregando...<br><br></center>');

                                $.ajax({
                                    url  : URL + 'admin/get-details-by-id',
                                    type : 'GET',
                                    data : { time_slot_id: calEvent.id },
                                    success: function(data){
                                        var html = '<br>';

                                        $.each(data, function(key){
                                            if(data[key].health_plan_id != 0) {
                                                html += '<p style="margin-top: 5px; margin-left: 10px;">';
                                                html += '<span>' + data[key].name + '</span>';
                                                html += '<button class="btn btn-xs btn-danger pull-right" style="margin-right: 10px;" onclick="fechar_plano(' + data[key].id + ', ' + data[key].time_slot_id + ', \'' + calEvent.start._i + '\');"><i class="fa fa-remove"></i></button>'
                                                html += '</p>';
                                            }else{
                                                html += '<p style="margin-top: 5px; margin-left: 10px;">';
                                                html += 'Horário privado sem planos';
                                                html += '</p>';
                                            }
                                        });

                                        $("#eventContent > div").html(html);
                                    }
                                });
                            }
                        }
                    }
                },
                eventMouseover: function(calEvent, jsEvent, view){
                        $(this)
                            .attr({
                                "data-toggle"    : "tooltip",
                                "data-placement" : "top",
                                "title"          : $(this).text()
                            })
                            .css({
                                "border" : "1px solid #000",
                                "z-index" : "99"
                            });

                        var html = '<p><span><i class="fa fa-remove" style="cursor: pointer;"></i></span> ' + $(this).text() + '</p>';
                        $(this).html(html);
                },
                eventMouseout: function(calEvent, jsEvent, view){
                        $(this).find("i").remove();
                        $(this).css({
                            "border"   : "0px",
                            "z-index"  : "1"
                        })
                },
                selectable : true,
                select: function(start, end, allDay) {
                    var data = start._d.toString().split(" ")[0];
                    var day = 0;

                    if(data === "Sun"){
                        day = 0;
                    }else if(data === "Mon"){
                        day = 1;
                    }else if(data === "Tue"){
                        day = 2;
                    }else if(data === "wed"){
                        day = 3;
                    }else if(data === "Thu"){
                        day = 4;
                    }else if(data === "Fri"){
                        day = 5;
                    }else if(data === "Sat"){
                        day = 6;
                    }

                    $('select[name=day_of_week] option').removeAttr('selected').filter('[value=' + day + ']').attr('selected', true);

                        var dateStart = new Date(start);
                        var dateEnd   = new Date(end);

                        var yearS      = dateStart.getYear() + 1900;
                        var dayS       = dateStart.getDate();
                        var monthS     = dateStart.getMonth() + 1;
                        var hoursS     = dateStart.getHours();
                        var minutesS   = dateStart.getMinutes() < 10 ? "0" + dateStart.getMinutes() : dateStart.getMinutes();
                        var secondsS   = "0" + dateStart.getSeconds();

                        var yearE      = dateEnd.getYear() + 1900;
                        var dayE       = dateEnd.getDate();
                        var monthE     = dateEnd.getMonth() + 1;
                        var hoursE     = dateEnd.getHours();
                        var minutesE   = dateEnd.getMinutes() < 10 ? "0" + dateEnd.getMinutes() : dateEnd.getMinutes();
                        var secondsE   = "0" + dateEnd.getSeconds();

                        if(hoursS === 24){
                            hoursS = 00;
                        }

                        if(hoursS > 24){
                            hoursS -= 24;
                        }

                        if(hoursE === 24){
                            hoursE = 00;
                        }

                        if(hoursE > 24){
                            hoursE -= 24;
                        }

                        var formattedHourS = (hoursS < 10 ? '0' + (hoursS) : hoursS) + ':' + minutesS;
                        var formattedHourE = (hoursE < 10 ? '0' + hoursE : hoursE) + ':' + minutesE;
                        var formattedDate  = yearS + '-' + (monthS < 10 ? '0' + monthS : monthS) + '-' + (dayS < 10 ? '0' + dayS : dayS);

                        var formattedDateStart = yearS + '-' + monthS + '-' + dayS + ' ' + hoursS + ':' + minutesS + ':00';
                        var formattedDateEnd   = yearS + '-' + monthS + '-' + dayS + ' ' + hoursE + ':' + minutesE + ':00';

                        $('input[name=start_datetime]').val(formattedDateStart);
                        $('input[name=end_datetime]')  .val(formattedDateEnd);

                        $("input[name=slot_time_start]").val(formattedHourS);
                        $("input[name=slot_time_end]").val(formattedHourE);
                        $("input[name=slot_date]").val(formattedDate);
                        $("input[name=start_date]").val(start);
                        $("input[name=end_date]").val(end);

                        $('#modalTimeSlot').modal();
                        $('#modalTimeSlot').modal('show');
                }
            });
        });
    </script>
@endsection
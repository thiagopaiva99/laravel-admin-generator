@extends("layouts.app")

@section("content")
    <div class="content-header col-md-12" id="">
        @if(count($locals) > 0)
            {!! Form::open(["class" => "form-inline pull-left", "method" => "GET", "style" => "width: 100% !important;"]) !!}
                <div id="header-searches">
                    <div class="form-group">
                        {{ Form::label('', 'Selecione o local do médico:') }}
                        <select name="local" id="local" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione o local" data-actions-box="true">
                            @foreach($doctors as $medic)
                                <option value="{{ $medic->local_id }}" id-user="{{ $medic->user_id }}" {{ isset($_GET['local']) && $_GET['local'] == $medic->local_id ? "selected" : "" }}>{{ $medic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        {{ Form::label('', 'Buscar dia:') }} <br>
                        {!! Form::date('search_day', null, ['class' => 'form-control', 'placeholder' => 'Avançar o calendário']) !!}
                    </div>
                </div>
            {!!  Form::close() !!}
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="content">
        <div class="clearfix"></div>

        <div id="alertar"></div>

        <div class="box " style="border: none;">
            <div class="box-body {{ count($locals) == 0 | count($doctors) ? "text-center" : "" }}">
                @if(count($locals) > 0)
                    <div class="pull-left" style="padding-top: 10px;">
                        <span style="margin-right: 15px;"><i class="fa fa-square text-red"></i> Horário fechado</span>
                        <span style="margin-right: 15px;"><i class="fa fa-square" style="color: #efefef"></i> Horário padrão</span>
                        <span style="margin-right: 15px;"><i class="fa fa-square text-green"></i> Horário customizado</span>
                    </div>
                    <div class="pull-right btn-group">
                        <a class="btn btn-default btn-refresh-calendar">Recarregar Horários</a>
                        <a class="btn btn-default" data-toggle="modal" style="cursor: pointer;" data-target="#modalTimeSlot">Novo Horário</a>
                        <!-- <div class="dropdown">
                            <button id="dLabel" type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Novo Horário
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                                <li><a data-toggle="modal" style="cursor: pointer;" data-target="#modalTimeSlot">Abrir janela</a></li>
                                <li><a href="{{ route('admin.timeSlots.create') }}">Horário Simples</a></li>
                                <li><a href="{{ url('admin/time-slots/create-multiple') }}">Multiplos médicos</a></li>
                            </ul> 
                        </div> -->
                    </div>

                    <div class="clearfix"></div>

                    <div class="alerta">
                        Estamos carregando seus horários, isso pode levar alguns segundos!
                    </div>

                    <hr>

                    <div class="col-md-12" style="margin-bottom: 20px;">
                        @include("flash::message-admin")
                    </div>

                    <div id="weekCalendar"></div>
                @else
                    <h3>Você não tem médicos cadastrados, cadastre um agora mesmo <a target="_blank" href="{{ url('admin/users/create') }}" class="text-green">clicando aqui</a></h3>
                    <img src="{{ asset('assets/site/images/logo_300x300.png') }}" alt="">
                @endif
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

                    <?php $class = Auth::user()->user_type == \App\Models\User::UserTypeClinic ? "col-sm-4" : "col-sm-4"; ?>

                    <!-- Local Field -->
                    <div class="form-group {{ $class }}">
                        {!! Form::label('local_id', 'Local:') !!}
                        {!! Form::select('local_id', $locals, isset($local) ? $local : null, ['class' => 'form-control', 'required' => 'true', 'disabled' => 'true']) !!}
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
                        <input type="hidden" name="user_id" value="">
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

                    <input type="hidden" name="value_select" value="1">
                    <input type="hidden" name="plans_selected" value="">

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
                        <button class="pull-left btn btn-danger btn-save-closed">Fechar Horário</button>
                        <div class="btn-group text-center">
                            <button type="button" class="btn btn-success btn-save-timeslot" disabled>Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="eventContent" title="Event Details" style="display:none; position: absolute; z-index: 100; width: 200px; background: #fff; border: 1px solid rgba(0, 0, 0, 0.5);">
        <header style="width: 100%; background: #361121; padding: 5px; color: white;">Horários <button class="btn btn-default btn-xs pull-right" onclick="close_popup();"><i class="fa fa-remove"></i></button> </header>
        <span style="margin-top: 10px; display: block;">
            <center>
                <div class="btn-group">
                    <a href="" class="btn btn-xs btn-success" id="a_time_slot">Editar</a>
                    <button class="btn btn-xs btn-danger" onclick="delete_time_slot()">Apagar</button>
                    <button class="btn btn-xs btn-bitbucket" id="close_time_slot" onclick="close_all_slot()">Fechar</button>
                </div>

                <input type="hidden" name="time_slot_id" value="">
                <input type="hidden" name="close_slot_start" value="">
                <input type="hidden" name="close_slot_end" value="">

                <br><br>
            </center>
        </span>
    </div>
@endsection

@section("scripts")
    <script>
        $(function(){
            var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
            // Firefox 1.0+
            var isFirefox = typeof InstallTrigger !== 'undefined';
            // At least Safari 3+: "[object HTMLElementConstructor]"
            var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
            // Internet Explorer 6-11
            var isIE = /*@cc_on!@*/false || !!document.documentMode;
            // Edge 20+
            var isEdge = !isIE && !!window.StyleMedia;
            // Chrome 1+
            var isChrome = !!window.chrome && !!window.chrome.webstore;



            @if(!isset($_GET['local']))
                $("#local option:nth-child(2)").attr("selected", true);
            @endif

            $('select[name=medics]').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $('select[name=local]').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $('#weekCalendar').fullCalendar({
                defaultView: 'agendaWeek',
                timezone: 'local',
                hiddenDays: [
                    0
                ],
                eventSources : [
                    {
                        url: '/admin/calendar/clinic-time-slots',
                        type: 'GET',
                        data : {
                            tipo : 1,
                            local : $("#local").val(),
                            doctor : $("#local option:selected").attr("id-user")
                        },
                        color: '#ededed',   // a non-ajax option
                        textColor: 'black', // a non-ajax option
                        className: 'semanal',
                        timeout: 25000,
                        success: function(data){
                        },
                        error: function(data){
                            $(".alerta").text("Infelizmente não foi possivel carregar seus horários, tente recarregar a página!");
                        }
                    },
                    {
                        url: '/admin/calendar/clinic-time-slots',
                        type: 'GET',
                        data : {
                            tipo : 2,
                            local : $("#local").val(),
                            doctor : $("#local option:selected").attr("id-user")
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
                        url: '/admin/calendar/clinic-closed-dates',
                        type: 'GET',
                        data: {
                            local : $("#local").val(),
                            doctor : $("#local option:selected").attr("id-user")
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
                    }else if(data === "Wed"){
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
                eventClick: function(calEvent, jsEvent, view, event) {
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
                                    data : { user_id : $("#local option:selected").attr("id-user") },
                                    success: function(data){
                                        if(data == "deletou"){
                                            $("#weekCalendar").fullCalendar("removeEvents", calEvent.id);
                                            $("#weekCalendar").fullCalendar("removeEvents");
                                            $("#weekCalendar").fullCalendar('refetchEvents');
                                        }
                                    }
                                });
                            }
                        }else{
                            if($(this).hasClass('closed')){
                                window.location.href = "//" + $(location).attr('host') + "/admin/closed-date-destroy/" + calEvent.id;
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
                                    data : { user_id : $("#local option:selected").attr("id-user") },
                                    success: function(data){
                                        if(data == "deletou"){
                                            $("#weekCalendar").fullCalendar("removeEvents", calEvent.id);
                                            $("#weekCalendar").fullCalendar("removeEvents");
                                            $("#weekCalendar").fullCalendar('refetchEvents');
                                        }
                                    }
                                });
                            }
                        }else{
                            if($(this).hasClass('closed')){
                                window.location.href = "//" + $(location).attr('host') + "/admin/closed-date-destroy/" + calEvent.id;
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

                                        console.log(data);

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
                    var html = "";

                    if($(this).attr('class').indexOf('closed') != -1){
                        html = '<p><span><i class="fa fa-remove" style="cursor: pointer; z-index: 666 !important;"></i></span> ' + $(this).text() + '</p>';
                    }else{
                        html = '<p><span><i class="fa fa-plus" style="cursor: pointer; z-index: 666 !important;"></i></span> ' + $(this).text() + '</p>';
                    }

                    $(this).html(html);
                },
                eventMouseout: function(calEvent, jsEvent, view){
                    $(this).find("i").remove();
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
                    }else if(data === "Wed"){
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

                    if(hoursS === 25){
                        hoursS = '01';
                    }

                    if(hoursE === 25){
                        hoursE = '01';
                    }

                    if(hoursS === 24){
                        hoursS = '00';
                    }

                    if(hoursE === 24){
                        hoursE = '00';
                    }

                    var formattedHourS = (hoursS < 10 ? '0' + (hoursS) : hoursS) + ':' + minutesS;
                    var formattedHourE = (hoursE < 10 ? '0' + hoursE : hoursE) + ':' + minutesE;
                    var formattedDate  = yearS + '-' + (monthS < 10 ? '0' + monthS : monthS) + '-' + (dayS < 10 ? '0' + dayS : dayS);

                    var formattedDateStart = yearS + '-' + monthS + '-' + dayS + ' ' + hoursS + ':' + minutesS + ':00';
                    var formattedDateEnd   = yearS + '-' + monthS + '-' + dayS + ' ' + hoursE + ':' + minutesE + ':00';

                    $("input[name=slot_time_start]").val(formattedHourS);
                    $("input[name=slot_time_end]").val(formattedHourE);
                    $("input[name=slot_date]").val(formattedDate);
                    $("input[name=start_date]").val(start);
                    $("input[name=end_date]").val(end);

                    $('input[name=start_datetime]').val(formattedDateStart);
                    $('input[name=end_datetime]')  .val(formattedDateEnd);

                    $('#modalTimeSlot').modal();
                    $('#modalTimeSlot').modal('show');
                }
            });
        });
    </script>
@endsection
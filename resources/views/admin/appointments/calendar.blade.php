@extends('layouts.app')

@section('content')
    @if(count($locals) > 0)
        <div class="content-header col-md-12" id="">
            @if(count($locals) > 0)
                {!! Form::open(["class" => "form-inline pull-left", "method" => "GET", "style" => "width: 100% !important;"]) !!}
                <div id="header-searches">
                    <div class="form-group">
                        {{ Form::label('', 'Selecione o local do médico:') }}
                        <select name="local" id="local" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione o local" data-actions-box="true">
                            @foreach($medics as $medic)
                                <option value="{{ $medic->local_id }}" id-user="{{ $medic->user_id }}" {{ isset($_GET['local']) && $_GET['local'] == $medic->local_id ? "selected" : "" }}>{{ $medic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {!!  Form::close() !!}
            @endif
        </div>

        <div class="clearfix"></div>

        <div class="content">
            <div class="box box-success">
                <div class="box-header with-border text-center">
                    <span class="pull-left">
                        @include("admin.util._back")
                    </span>

                    <h1 class="box-title">Agendamentos</h1>
                </div>

                <div class="box-body">
                    <div id="monthCalendar"></div>
                </div>
            </div>
        </div>
    @else
        <div class="content-header">
            <div class="box box-success text-center">
                <div class="box-body">
                    <h3>Você não tem locais de consulta, cadastre um agora mesmo <a target="_blank" href="{{ url('admin/locals/create') }}" class="text-green">clicando aqui</a></h3>
                    <img src="{{ asset('assets/site/images/logo_300x300.png') }}" alt="">
                </div>
            </div>
        </div>
    @endif
@endsection

@section("scripts")
    <script>
        $(function(){
            $('select[name=medics]').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $('select[name=local]').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                form.submit();
            });

            $("#monthCalendar").fullCalendar({
                eventSources: [
                    {
                        url : '/admin/calendar/clinic-appointments',
                        type : 'GET',
                        color: '#571C36',
                        textColor: 'white',
                        data: {
                            local: $("select[name=local]").val()
                        },
                        success: function(data){

                        }
                    }
                ],
                eventClick: function(calEvent, jsEvent, view) {
//                    console.log(calEvent.id);
                    window.location.href = "//" + $(location).attr('host') + "/admin/appointments/" + calEvent.id + "";
                }
            });
        });
    </script>
@endsection


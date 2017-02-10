@extends('layouts.app')

@section('content')

    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">

            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Gerar relatório</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <div class="row">
                    {{ Form::open(['url' => 'admin/reports', 'method' => 'post']) }}
                        <div class="col-md-12">
                            @if(Auth::user()->user_type == 1)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('', 'Selecione a clinica: ') }}
                                        {{ Form::select('clinic', $doctors, isset($_GET['doctors']) ? $_GET['doctors'] : null, ['class' => 'form-control select_doctors show-menu-arrow', 'data-live-search' => 'true', 'title' => 'Selecione a clínica', 'data-actions-box' => 'true', 'required' => 'true']) }}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('', 'Selecione um médico: ') }}
                                        {{ Form::select('doctors[]', $doctors, isset($_GET['doctors']) ? $_GET['doctors'] : null, ['class' => 'select_medics_report form-control select_doctors show-menu-arrow', 'multiple' => 'multiple', 'data-live-search' => 'true', 'title' => 'Selecione o médico', 'data-actions-box' => 'true', 'required' => 'true']) }}
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('', 'Selecione um médico: ') }}
                                        {{ Form::select('doctors[]', $doctors, isset($_GET['doctors']) ? $_GET['doctors'] : null, ['class' => 'form-control select_doctors show-menu-arrow', 'multiple' => 'multiple', 'data-live-search' => 'true', 'title' => 'Selecione o médico', 'data-actions-box' => 'true', 'required' => 'true']) }}
                                    </div>
                                </div>
                            @endif

                            @if(Auth::user()->user_type == 1)
                                <div class="col-md-2">
                                    <!-- data_hora_inicio -->
                                    <div class="form-group">
                                        {!! Form::label('data_hora_inicio', 'Início *') !!}
                                        <input type="date" name="start_datetime" class="form-control" required>
                                    </div>
                                    <!-- / data_hora_inicio -->
                                </div>

                                <div class="col-md-2">
                                    <!-- data_hora_final -->
                                    <div class="form-group">
                                        {!! Form::label('data_hora_inicio', 'Fim *') !!}
                                        <input type="date" name="end_datetime" class="form-control" required>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3">
                                    <!-- data_hora_inicio -->
                                    <div class="form-group">
                                        {!! Form::label('data_hora_inicio', 'Início *') !!}
                                        <input type="date" name="start_datetime" class="form-control" required>
                                    </div>
                                    <!-- / data_hora_inicio -->
                                </div>

                                <div class="col-md-3">
                                    <!-- data_hora_final -->
                                    <div class="form-group">
                                        {!! Form::label('data_hora_inicio', 'Fim *') !!}
                                        <input type="date" name="end_datetime" class="form-control" required>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-1">
                                {{ Form::label('', 'Ações') }}<br>
                                {{ Form::submit('Gerar relatório', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
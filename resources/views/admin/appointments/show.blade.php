@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Atendimento</h1>

                <span class="pull-right">
                    {!! Form::open(['route' => ['admin.appointments.destroy', $appointment->id], 'method' => 'delete']) !!}
                    <div class="form-group col-sm-12">
                            {!! Form::button('<i class="fa fa-trash"></i> Cancelar', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger pull-right '
                            ]) !!}
                        </div>
                    {!! Form::close() !!}
                </span>
            </div>
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.appointments.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection

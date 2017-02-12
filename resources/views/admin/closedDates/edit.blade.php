@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Editar horário fechado</h1>

                <span class="pull-right">
                    {!! Form::open(['route' => ['admin.closedDates.destroy', $closedDate->id], 'method' => 'delete']) !!}
                    <div class="form-group col-sm-12">
                            {!! Form::button('<i class="fa fa-trash"></i> Apagar', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger pull-right '
                            ]) !!}
                        </div>
                    {!! Form::close() !!}
                </span>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::model($closedDate, ['route' => ['admin.closedDates.update', $closedDate->id], 'method' => 'patch']) !!}

                    @include('admin.closedDates.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
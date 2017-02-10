@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Novo agendamento</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.appointments.store']) !!}

                    @include('admin.appointments.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">

            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Horário de atendimento</h1>

            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include("flash::message-admin")
                </div>

                <div class="row">

                    {!! Form::open(['url' => url('admin/time-slots/create'), 'data-toggle' => 'validator', 'id' => 'form_time_slot', 'role' =>'form']) !!}

                    @include('admin.timeSlots.fields-multiple')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

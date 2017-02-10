@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Datas fechadas</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.closedDates.store', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_new_closed_date']) !!}

                    @include('admin.closedDates.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-danger">

            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Nova especialização</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.specializations.store', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_new_spec']) !!}

                    @include('admin.specializations.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

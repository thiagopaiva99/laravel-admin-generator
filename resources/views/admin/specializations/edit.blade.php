@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-danger">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Editar especialização</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::model($specialization, ['route' => ['admin.specializations.update', $specialization->id], 'method' => 'patch', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_edit_spec']) !!}

                    @include('admin.specializations.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

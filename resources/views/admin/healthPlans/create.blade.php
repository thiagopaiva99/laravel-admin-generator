@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">

            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Novo plano de saúde</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.healthPlans.store', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_new_plan']) !!}

                    @include('admin.healthPlans.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

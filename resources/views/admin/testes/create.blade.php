@extends('layouts.app')

@section('content')
<div class="content">
    <div class="" style="text-align: right;">
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/admin/testes') }}"> Testes</a></li>
            <li class="active">Novo Teste</li>
        </ol>
    </div>
    <div class="box box-success">
        <div class="box-header with-border text-center">
                        <span class="pull-left">
                            @include("admin.util._back")
                        </span>

            <h1 class="box-title">Teste</h1>
        </div>

        <div class="box-body">
            <div class="col-md-12">
                @include('flash::message-admin')
            </div>

            <div class="row">
                {!! Form::open(['route' => 'admin.testes.store', 'data-toggle' => 'validator', 'role' => 'form']) !!}

                    @include('admin.testes.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

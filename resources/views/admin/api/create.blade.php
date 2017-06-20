@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/admin/api') }}"> API Generator</a></li>
                <li class="active">Nova API</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Nova API</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                {!! Form::open(['route' => 'admin.api.store']) !!}

                @include('admin.api.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

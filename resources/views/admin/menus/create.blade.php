@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Menus</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Menus</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>
                {!! Form::open(['route' => 'admin.menus.store']) !!}

                    @include('admin.menus.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

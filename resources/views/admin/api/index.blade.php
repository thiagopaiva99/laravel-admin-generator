@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">API Generator</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                            <span class="pull-left">
                                @include("admin.util._back")
                                <a href="{{ url('/admin/api/create') }}" class="btn btn-primary">Nova API</a>
                            </span>

                <h1 class="box-title">API Generator</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                @include('admin.api.table')
            </div>
        </div>
    </div>
@endsection


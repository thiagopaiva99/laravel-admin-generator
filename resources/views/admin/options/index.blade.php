@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Opçoes</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                            <span class="pull-left">
                                @include("admin.util._back")
                            </span>

                <h1 class="box-title">Opçoes</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1-1" data-toggle="tab">Gerenciar opçoes</a></li>
                        <li><a href="#tab_2-2" data-toggle="tab">Lista de opçoes</a></li>
                        <li><a href="#tab_2-3" data-toggle="tab">Açoes</a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1-1">
                            @include('admin.options.table')
                        </div>
                        <div class="tab-pane" id="tab_2-2">
                            <a href="{{ url('/admin/options/create') }}" class="btn btn-primary">Gerar Nova Opção</a>

                            <hr>

                            @include('admin.options.list')
                        </div>
                        <div class="tab-pane" id="tab_2-3">
                            <a href="{{ url('/admin/export-database') }}">Exportar banco de dados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




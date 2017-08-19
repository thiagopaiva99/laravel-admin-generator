@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Usuarios</li>
            </ol>
        </div>
        <div class="box box-danger" style="margin-top: 10px; ">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Usuários</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <!-- include admin.users.table -->
                @include('admin.users.table')
                <!-- ./ include admin.users.table -->

            </div>
        </div>
    </div>
@endsection

@section("scripts")
    {!! $dataTable->scripts() !!}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
@endsection

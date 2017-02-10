@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-success" style="margin-top: 10px; ">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                @if(Auth::user()->user_type == \App\Models\User::UserTypeAdmin)
                    <h1 class="box-title">Usuários</h1>
                @elseif(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
                    <h1 class="box-title">Médicos</h1>
                @else
                    <h1 class="box-title">Pacientes</h1>
                @endif
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection

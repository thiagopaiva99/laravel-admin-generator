@extends('layouts.app')

@section('content')
    <div class="content">
        @if(Auth::user()->user_type == 1)
            <div class="col-md-3">
                {!! Form::open(["method" => "GET", 'style' => 'display: inline']) !!}
                {!! Form::label("user_type", "Listar usuários") !!}
                {!! Form::select("user_type", [null=>"Todos", 1=>"Admin", 2=>"Médicos", 3=>"Pacientes",4=>"Clínicas"], isset($user_type) ? $user_type : null, ['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>

            @if((isset($user_type) && $user_type == 2) || Request::is('admin/users/preferred_users'))
                <div class="col-md-3">

                    {!! Form::open(["url" => "admin/users", "method" => "GET"]) !!}
                    {!! Form::label("user_type", "Médicos preferenciais") !!}
                    {!! Form::select("user_preferred", ['all' => 'Todos', 'true' => 'Sim', 'false' => 'Não'], isset($_GET['user_preferred']) && $_GET['user_preferred'] != '' ? $_GET['user_preferred'] : 'false', ['class' => 'form-control']) !!}
                    {!! Form::hidden("user_type", $_GET['user_type']) !!}
                    {!! Form::close() !!}

                </div>
            @endif

            <div class="col-md-3">
                {!! Form::open(["method" => 'get']) !!}
                {!! Form::label("user_type", "Status dos usuários:") !!}
                {!! Form::select('select_status', ['all' => 'Todos', 1 => 'Aguardando', 2 => 'Aprovado', 3 => 'Negado'], isset($_GET['select_status']) ? $_GET['select_status'] : 'all', ['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>
        @endif

        <div class="clearfix"></div>
        <div class="box box-danger" style="margin-top: 10px; ">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                    @if(Auth::user()->user_type == 1 || Auth::user()->user_type == 4)
                        <a href="{{ route('admin.users.create') }}" class="btn btn-default">Novo usuário</a>
                    @endif
                </span>

                @if(Auth::user()->user_type == \App\Models\User::UserTypeAdmin)
                    <h1 class="box-title">Usuários</h1>
                @elseif(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
                    <h1 class="box-title">Profissionais</h1>
                @else
                    <h1 class="box-title">Usuários</h1>
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#user_type').on('change', function (e) {
                var select = $(this), form = select.closest('form');
                //form.attr('action', '/admin/user?user_type=' + select.val());
                form.submit();
            });

            $("select[name=user_preferred]").on('change', function(){
                var select = $(this), form = select.closest('form');
                //form.attr('action', '/admin/user?user_type=' + select.val());
                form.submit();
            });

            $("select[name=select_status]").on('change', function(){
                var select = $(this), form = select.closest('form');
                //form.attr('action', '/admin/user?user_type=' + select.val());
                form.submit();
            })
        });
    </script>
@endsection

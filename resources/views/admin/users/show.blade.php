@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="clearfix"></div>

        <div class="box box-danger">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                @if (Auth::user()->user_type == \App\Models\User::UserTypeDoctor)
                    @if($user->id == Auth::id())
                        <h1 class="box-title">Perfil</h1>
                    @else
                        <h1 class="box-title">{!! $user->user_type_str !!}</h1>
                    @endif
                @else
                    <h1 class="box-title">{!! $user->user_type_str !!}</h1>
                @endif

                @if($user->id == Auth::user()->id)
                    <span class="pull-right">
                        <a class="btn btn-default" href="{!! route("admin.users.edit", ["users" => $user->id]) !!}">Editar</a>
                    </span>
                @endif
            </div>
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.users.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/admin/users') }}"> Usuarios</a></li>
                <li class="active">Visualizar Usuario</li>
            </ol>
        </div>
        <div class="row">
            @include("admin.users.show_fields")
        </div>
    </div>
@endsection

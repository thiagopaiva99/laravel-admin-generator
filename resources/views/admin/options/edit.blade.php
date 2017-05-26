@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/admin/options') }}"> Opçoes</a></li>
                <li class="active">Editar Opçao</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Editar Opçao</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>
               {!! Form::model($options, ['route' => ['admin.options.update', $options->id], 'method' => 'patch', 'data-toggle' => 'validator']) !!}

                    @include('admin.options.fields')

               {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection
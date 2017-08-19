@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                            <span class="pull-left">
                                @include("admin.util._back")
                            </span>

                <h1 class="box-title">Teste</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <div class="row" style="padding-left: 20px">
                    @include('admin.testes.show_fields')
                    <a href="{!! route('admin.testes.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection

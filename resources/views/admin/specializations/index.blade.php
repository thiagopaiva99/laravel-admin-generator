@extends('layouts.app')

@section('content')
        <div class="content">
                <div class="clearfix"></div>

                <div class="clearfix"></div>
                <div class="box box-danger">

                        <div class="box-header with-border text-center">
                                <span class="pull-left">
                                    @include("admin.util._back")
                                </span>

                                <h1 class="box-title">Especialidades</h1>
                        </div>

                        <div class="box-body">
                                <div class="col-md-12">
                                        @include('flash::message-admin')
                                </div>

                                @include('admin.specializations.table')
                        </div>
                </div>
        </div>
@endsection


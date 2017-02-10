@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Detalhes do plano de saúde</h1>
            </div>

            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.healthPlans.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection

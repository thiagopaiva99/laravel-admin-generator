@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Novo local de atendimento</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.locals.store', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_new_local']) !!}

                    @include('admin.locals.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.locals.map_view')

    <script>
        $('.select-multple').multiSelect()
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Editar exame</h1>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::model($exam, ['route' => ['admin.exams.update', $exam->id], 'method' => 'patch', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_edit_exam']) !!}

                    @include('admin.exams.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
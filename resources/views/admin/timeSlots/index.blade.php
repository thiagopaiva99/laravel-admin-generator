@extends('layouts.app')

@section('content')
        <section class="content-header">
                <h1 class="pull-right">
                        {!! Form::open(["method" => "GET", "class" => "form-inline, pull-right"]) !!}
                        <div class="form-group">
                                {!! Form::label("local", "Local de Atendimento", ['style' => 'font-weight: 400;']) !!}
                                {!! Form::select("local", $locals, $local, ['class' => "form-control"]) !!}
                        </div>
                        {!! Form::close() !!}
                </h1>
        </section>
        <div class="content">
                <div class="clearfix"></div>

                <div class="clearfix"></div>
                <div class="box box-success">
                        <div class="box-header with-border text-center">
                            <span class="pull-left">
                                @include("admin.util._back")
                            </span>

                            <h1 class="box-title">Horários de atendimento</h1>
                        </div>
                        <div class="box-body">
                                <div class="col-md-12">
                                        @include('flash::message-admin')
                                </div>

                                @include('admin.timeSlots.table')
                        </div>
                </div>
        </div>
@endsection

@section('scripts')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
        <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
        <script src="/vendor/datatables/buttons.server-side.js"></script>
        {!! $dataTable->scripts() !!}
        <script type="text/javascript">
                $(document).ready(function() {
                        $('#local').on('change', function (e) {
                                var select = $(this), form = select.closest('form');
                                //form.attr('action', '/admin/user?user_type=' + select.val());
                                form.submit();
                        });
                });
        </script>
@endsection
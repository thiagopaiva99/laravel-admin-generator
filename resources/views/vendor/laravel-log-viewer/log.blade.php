@extends("layouts.app")

@section("styles")
    <style>
        .stack {
            font-size: 0.85em;
        }
        .date {
            min-width: 75px;
        }
        .text {
            word-break: break-all;
        }
        a.llv-active {
            z-index: 2;
            background-color: #f5f5f5;
            border-color: #777;
        }
    </style>
@endsection

@section("content")
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Logs</li>
            </ol>
        </div>
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Logs</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>
                    <div class="col-sm-3 col-md-2 sidebar">
                        <div class="callout callout-success">
                            @if($current_file)
                                <a href="?dl={{ base64_encode($current_file) }}">Download file</a><br>
                                <a id="delete-log" href="?del={{ base64_encode($current_file) }}">Delete file</a><br>
                                @if(count($files) > 1)
                                    <a id="delete-all-log" href="?delall=true">Delete all files</a>
                                @endif
                            @endif
                        </div>
                        <div class="list-group">
                            @foreach($files as $file)
                                <a href="?l={{ base64_encode($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif">
                                    {{$file}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-9 col-md-10 table-container">
                        @if ($logs === null)
                            <div>
                                Log file >50M, please download it.
                            </div>
                        @else
                            <table id="table-log" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ambiente</th>
                                    <th>Data</th>
                                    <th>Conteudo</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($logs as $key => $log)
                                    <tr data-display="stack{{{$key}}}">
                                        <td class="text-{{{$log['level_class']}}}"><span class="fa fa-{{ $log['level_img'] }}-sign" aria-hidden="true"></span> &nbsp;{{$log['level']}}</td>
                                        <td class="text">{{$log['context']}}</td>
                                        <td class="date">{{{$log['date']}}}</td>
                                        <td class="text">
                                            @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs" data-display="stack{{{$key}}}"><span class="glyphicon glyphicon-search"></span></a>@endif
                                            {{{$log['text']}}}
                                            @if (isset($log['in_file'])) <br />{{{$log['in_file']}}}@endif
                                            @if ($log['stack']) <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}</div>@endif
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
    </div>
@endsection

@section("scripts")
    <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function(){
            $('tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({
                "order": [ 1, 'desc' ],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });
            $('.table-container').on('click', '.expand', function(){
                $('#' + $(this).data('display')).toggle();
            });
            $('#delete-log, #delete-all-log').click(function(){
                return confirm('Are you sure?');
            });
        });
    </script>
@endsection
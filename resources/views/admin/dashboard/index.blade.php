@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="" style="text-align: right;">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-hdd-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Espaço no HD</span>
                        <span class="info-box-number">{{ $diskUsage }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                        Total: {{ $diskTotal }}
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-database"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">banco de dados</span>
                        <span class="info-box-number">{{ $databaseSize }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                        Espaço total do banco de dados
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-percent"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Memoria RAM disponivel</span>
                        <span class="info-box-number">{{ $memoryAvailable }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                        Total: {{ $totalMemory }}
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>

            <div class="col-md-3">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-tasks"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">processador</span>
                        <span class="info-box-number">{{ $databaseSize }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                        Uso do processador
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-header with-border text-center">
                <span class="pull-left">
                    @include("admin.util._back")
                </span>

                <h1 class="box-title">Dashboard</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                <div class="row col-md-3">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h1 class="box-title">
                                Deploy
                            </h1>
                        </div>
                        <div class="box-body">
                            <p>
                                Status do ZIP:

                                <strong>{{ $existZip ? 'Gerado' : 'Não Gerado' }}</strong>
                            </p>

                            <hr>

                            @if ( $existZip )
                                <a href="{{ url('/zip/' . strtolower(getenv('TITLE')) . '.zip') }}" class="btn btn-primary btn-block">Download</a>
                                <a href="{{ url('/admin/dashboard/delete-zip') }}" class="btn btn-danger btn-block">Deletar ZIP</a>
                            @else
                                <a href="{{ url('/admin/dashboard/prepare-zip') }}" class="btn btn-primary btn-block">Gerar ZIP Completo</a>
                                <a href="{{ url('/admin/dashboard/prepare-zip-vendor') }}" class="btn btn-danger btn-block">Gerar ZIP Sem Vendor</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


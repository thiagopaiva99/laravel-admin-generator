@extends('layouts.app')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="content">
        <div class="box box-success">
            <div class="box-header with-border text-center">
            <span class="pull-left">
                @include("admin.util._back")
            </span>

                <h1 class="box-title">Detalhes do médico</h1>
            </div>

            <div class="box-body">
                <h2><i class="fa fa-user-md"></i> {{ $doctor->name }}</h2>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Email: </strong>{{ $doctor->email }}</p>
                        <p><strong>Telefone: </strong>{{ $doctor->phone }}</p>
                        <p><strong>Endereço: </strong>{{ $doctor->address }}</p>
                    </div>

                    <div class="col-md-3">
                        <p><strong>Médico preferencial: </strong> {{ $doctor->preferred_user ? 'Sim' : 'Não' }}</p>
                        <p><strong>Número de conselho: </strong> {{ $doctor->crm }}</p>
                        <p><strong>CPF: </strong> {{ $doctor->cpf }}</p>
                    </div>
                </div>

                <hr>

                <h3>Locais de atendimento:</h3>
                <div class="row">
                   @foreach($locals as $local)
                       @if(count($locals) > 1)
                           <div class="col-md-4" style="border-right: .8px solid rgba(204, 204, 204, 0.5);">
                       @else
                           <div class="col-md-12">
                       @endif

                           <p><strong>Local: </strong> {{ $local->name }}</p>
                           <p><strong>Email: </strong> {{ $local->email }}</p>
                           <p><strong>Telefone: </strong> {{ $local->phone }}</p>
                           <p><strong>Endereço: </strong> {{ $local->address }} - {{ $local->address_complement }}</p>
                           <p><strong>Valor da consulta: </strong> R${{ $local->amount }}</p>
                           <p><strong>Duração da consulta: </strong> {{ $local->appointment_duration_in_minutes }} minutos</p>

                       </div>
                   @endforeach

                   <div class="col-md-12">
                       <div id="map" style="width: 100%; height: 400px; margin-top: 10px;"></div>
                   </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBDmfcFhqs-rXv7_kyYPX1QfFqrETWLg94" type="text/javascript"></script>
    <script type="text/javascript">
        var locations = [
            @foreach($locals as $local)
                <?php $locations = explode(' ', $local->location); $i = 1; ?>
                ['{{ $local->address }}', {{ $locations[1] }}, {{ $locations[0] }}, {{ $i }}],
                <?php $i++; ?>
            @endforeach
        ];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(-30, -51),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    </script>
@endsection
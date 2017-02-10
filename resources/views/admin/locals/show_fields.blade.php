<div class="col-md-5">
    <!-- Map -->
    <div class="form-group col-sm-12">
        <div id="map" style="display: block; min-height: 720px; position: relative; width: 100%; height: 100%;"></div>
        {!! Form::hidden("lat", isset($local) ? $local->location->getLat() : null) !!}
        {!! Form::hidden("lng", isset($local) ? $local->location->getLng() : null) !!}
    </div>
</div>

<div class="col-md-7">
    <h2>{!! $local->name !!} <span class="pull-right" style="margin-right: 15px;">{!! $local->phone !!}</span></h2>
    <hr>

    <!-- Address Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('address', 'Endereço:') !!}
        <p>{!! $local->address !!}</p>
    </div>

    <!-- Address Complement Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('address_complement', 'Complemento:') !!}
        <p>{!! $local->address_complement !!}</p>
    </div>

    {{--formato interessante de telefone--}}
    {{--<p>{!! \App\Helpers\StringHelper::phoneFormat(\App\Helpers\StringHelper::onlyNumbers($local->phone)) !!}</p>--}}

    <!-- Amount Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('amount', 'Valor:') !!}
        <p>R$ {!! number_format($local->amount, 2, ",", ".") !!}</p>
    </div>

    <!-- Appointment Duration Time Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('appointment_duration_in_minutes', 'Intervalo de consultas:') !!}
        <p>{!! $local->appointment_duration_in_minutes !!}</p>
    </div>

    <div class="form-group col-sm-12">
        <h2>Especialidades</h2><hr>
        @foreach($local->specializations AS $specialization)
            <div class="col-sm-4">
                <p>{{ $specialization->name }}</p>
            </div>
        @endforeach
    </div>

    <div class="form-group col-sm-12">
        <h2>Exames</h2><hr>
        @foreach($local->exams AS $exam)
            <div class="col-sm-4">
                <p>{{ $exam->name }}</p>
            </div>
        @endforeach
    </div>

    <div class="form-group col-sm-12">
        <h2>Planos de Saúde</h2><hr>
        @foreach($local->healthPlans AS $healthPlan)
            <div class="col-sm-4">
                <p>{{ $healthPlan->name }}</p>
            </div>
        @endforeach
    </div>

    <div class="clearfix"></div>
</div>
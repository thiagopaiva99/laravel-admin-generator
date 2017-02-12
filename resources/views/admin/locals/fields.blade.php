<?php $class = isset($_GET['user_id']) ? 'col-sm-3' : 'col-sm-4' ?>

@if(Auth::user()->user_type == App\Models\User::UserTypeClinic)
    <!-- Predefined local -->
    <div class="form-group {{ $class }}">
        {{ Form::label('', 'Local pré-definido:') }}
        <div class="input-group">
            {{ Form::select('local_predefined', $locals, null, ['class' => 'form-control', (count($locals) < 1) ? "disabled" : ""]) }}
            <span class="input-group-btn">
                <button class="btn btn-success btn-flat btn-apply-values" type="button">Aplicar</button>
            </span>
        </div>
    </div>

    <div class="form-group col-sm-3">
        {{ Form::label('', 'Médico:') }}
        <select name="user_idd" id="" class="form-control">
            @foreach($medics as $medic)
                <option value="{{ $medic->id }}" {{ isset($_GET['user_id']) && $_GET['user_id'] == $medic->id ? "selected" : "" }}>{{ $medic->name }}</option>
            @endforeach
        </select>
    </div>
@endif

<!-- Name Field -->
<div class="form-group {{ $class }}">
    {!! Form::label('name', 'Nome:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Nome do local']) !!}
</div>

@if(Auth::user()->user_type == App\Models\User::UserTypeDoctor)
    {{-- Clinica --}}
    <div class="form-group col-sm-4">
        {{ Form::label('', 'Selecione uma clínica: ') }}
        {{ Form::select('clinic_id', $clinics, null, ['class' => 'form-control']) }}
    </div>
@endif

<div class="row col-sm-12">
    <!-- Phone Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('phone', 'Telefone:') !!}
        {!! Form::tel('phone', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Telefone do local']) !!}
    </div>

    <!-- Amount Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('amount', 'Valor da consulta (R$):') !!}
        {!! Form::number('amount', null, ['class' => 'form-control', 'min' => 0, 'step' => 'any', 'required' => 'true', 'placeholder' => 'Valor da consulta no local']) !!}
    </div>

    <!-- Appointment duration in minutes Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('appointment_duration_in_minutes', 'Tempo de consulta (minutos):') !!}
        {!! Form::number('appointment_duration_in_minutes', null, ['class' => 'form-control', 'min' => 0, 'step' => '1', 'required' => 'true', 'placeholder' => 'Tempo da consulta no local']) !!}
    </div>

    <!-- Specialization -->
    <div class="form-group col-sm-3">
        {!! Form::label('', 'Especialidades:') !!}
        {!! Form::select("specializations[]", $specializations, isset($local) ? $local->specializations->pluck("id")->toArray() : null, ['class' => 'form-control select_plans select-specializations show-menu-arrow', 'multiple' => 'multiple', 'required' => 'true', 'data-live-search' => 'true', 'title' => 'Selecione as especialidades', 'data-actions-box' => 'true']) !!}
    </div>
</div>

<!-- Address Field -->
<div class="form-group col-sm-8">
    {!! Form::label('address', 'Endereço:') !!}

    <div class="input-group">
        {!! Form::text('address', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Endereço do local']) !!}
        <span class="input-group-btn">
            <button class="btn btn-success btn-flat btn-search-map" type="button">Pesquisar</button>
        </span>
    </div>
</div>

<!-- Address Complement Field -->
<div class="form-group col-sm-4">
    {!! Form::label('address_complement', 'Complemento:') !!}
    {!! Form::text('address_complement', null, ['class' => 'form-control', 'placeholder' => 'Complemento do endereço do local']) !!}
</div>

<!-- Map -->
<div class="form-group col-sm-12">
    <div id="map" style="display: block; min-height: 350px; position: relative; width: 100%;"></div>
    {!! Form::hidden("lat", isset($local) ? $local->location->getLat() : null) !!}
    {!! Form::hidden("lng", isset($local) ? $local->location->getLng() : null) !!}
</div>

<!-- ./ Address -->


<div class="col-md-12 center-align">
    <!-- Exams -->
    <!-- <div class="form-group col-sm-12 col-md-4">
        {!! Form::label("exams", "Exames:") !!}
        {!! Form::select("exams[]", $exams, isset($local) ? $local->exams->pluck("id")->toArray() : null, ['class' => 'form-control select-multple select-exams', 'multiple' => 'multiple']) !!}
    </div> -->

    <!-- Health plans -->
    <!-- <div class="form-group col-sm-12 col-md-4">
        {!! Form::label("healthPlans", "Planos de Saúde:") !!}
        {!! Form::select("healthPlans[]", $health_plans, isset($local) ? $local->healthPlans->pluck("id")->toArray() : null, ['class' => 'form-control select-multple select-plans', 'multiple' => 'multiple', 'required' => 'true']) !!}
    </div>-->
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.locals.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>
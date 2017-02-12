<style>
    .toggle{ margin-top: -10px; }
</style>

<?php $class = Auth::user()->user_type == \App\Models\User::UserTypeClinic ? "col-sm-3" : "col-sm-4"; ?>

<!-- Local Field -->
<div class="form-group {{ $class }}">
    {!! Form::label('', 'Selecione os médicos:') !!}
    <div class="input-group">
        <select name="local_id[]" id="doctors" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione os médicos" data-actions-box="true" required multiple>
            @foreach($doctors as $medic)
                <option value="{{ $medic->id }}">{{ $medic->name }}</option>
            @endforeach
        </select>
        <span class="input-group-btn">
            <button class="btn btn-success btn-flat btn-load-locals" type="button">Buscar Locais</button>
        </span>
    </div>
</div>

<!-- local Field -->
<div class="form-group {{ $class }}">
    {!! Form::label('', 'Locais:') !!}
    <div class="input-group">
        <select name="locals" id="locals_select" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione os locais" data-actions-box="true" required multiple="multiple">
            <option value="0">Selecione os locais</option>
        </select>
{{--        {!! Form::select('locals[]', [0 => "Selecione os locais"], null, ['class' => 'form-control show-menu-arrow select_locals', 'required' => 'true', 'data-live-search' => "true",  'title' => "Selecione os locais", 'data-actions-box' => "true", 'required', 'multiple']) !!}--}}
        <span class="input-group-btn">
            <button class="btn btn-success btn-flat btn-load-health-plans" type="button">Buscar Planos</button>
        </span>
    </div>
</div>

<!-- Slot Type Field -->
<div class="form-group {{ $class }}">
    {!! Form::label('slot_type', 'Horário:') !!}
    {!! Form::select('slot_type', $slotTypes, null, ['class' => 'form-control', 'required' => 'true']) !!}
</div>

<!-- Slot Date Field -->
<div class="form-group {{ $class }}" id="div_day_slot">
    {!! Form::label('slot_date', 'Dia:') !!}
    {!! Form::date('slot_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Day Of Week Field -->
<div class="form-group {{ $class }}" id="div_day_of_week">
    {!! Form::label('day_of_week', 'Dia da Semana:') !!}
    {!! Form::select('day_of_week', $days, isset($start) ? $start->dayOfWeek : null, ['class' => 'form-control']) !!}
</div>

<!-- Slot Time Start Field -->
<div class="form-group col-sm-4">
    {!! Form::label('slot_time_start', 'Início:') !!}
    {!! Form::time('slot_time_start', (isset($timeSlot) && isset($timeSlot->slot_time_start)) ? $timeSlot->slot_time_start->format('H:i') : (isset($start) && $start != null ? $start->format('H:i') : null), ['class' => 'form-control', 'required' => 'true', 'max' => '59']) !!}
</div>

<!-- Slot Time End Field -->
<div class="form-group col-sm-5">
    {!! Form::label('slot_time_end', 'Final:') !!}
    {!! Form::time('slot_time_end', (isset($timeSlot) && isset($timeSlot->slot_time_end) )? $timeSlot->slot_time_end->format('H:i') : (isset($end) && $end !== null ? $end->format('H:i') : null), ['class' => 'form-control', 'required' => 'true']) !!}
</div>

<div class="form-group col-sm-2 col-md-2">
    {!! Form::label('', 'Fila:') !!}<br>
    <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default {{ isset($timeSlot) && $timeSlot->queue_type == 1 ? "active" : '' }}"  style="width: 123px;">
            <input type="radio" id="" name="queue" value="1"> Ordem de Chegada
        </label>
        <label class="btn btn-default {{ isset($timeSlot) && $timeSlot->queue_type == 2 ? "active" : '' }}" style="width: 123px;">
            <input type="radio" id="" name="queue" value="2" required> Hora Marcada
        </label>
    </div>
</div>

<div class="form-group col-sm-1 col-md-1">
    {!! Form::label('', 'Particular:') !!}
    <div class="checkbox">
        {!! Form::checkbox('private', isset($details) && $details[0]->private == 1 ? true : false, isset($details) && $details[0]->private == 1 ? true : false, ['data-toggle' => 'toggle', 'data-onstyle' => 'success', 'data-height' => '35', 'data-width' => '100', 'data-on' => 'SIM', 'data-off' => 'NÂO', 'id' => 'private']) !!}
    </div>
</div>

<div class="form-group col-sm-2 plan_select">
    {{ Form::label('', 'Selecione planos de saúde: ') }}
    {{ Form::select('medics_select', [], null, ['class' => 'form-control select_plans show-menu-arrow', 'id' => 'select_health_plans', 'multiple' => 'multiple', 'data-live-search' => 'true', 'title' => 'Selecione os planos de saúde', 'data-actions-box' => 'true']) }}
</div>

<div class="form-group col-sm-5 slot_count">
    {{ Form::label('', 'Quantidade de pacientes que serão atendidos:') }}
    {{ Form::number('slot_count', isset($details) ? $details[0]->slot_count : null, ['class' => 'form-control', 'required']) }}
</div>

<input type="hidden" name="plans_selected" value="">
<input type="hidden" name="locals_selected" value="">

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.calendar.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

<style>
    .toggle{ margin-top: -10px; }
</style>

<?php $class = Auth::user()->user_type == \App\Models\User::UserTypeClinic ? "col-sm-4" : "col-sm-4"; ?>

<!-- Local Field -->
<div class="form-group {{ $class }}">
    {!! Form::label('local_id', 'Local:') !!}
    @if(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
        <select name="local_id" id="local" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione o local" data-actions-box="true" required>
            @foreach($doctors as $medic)
                <option value="{{ $medic->local_id }}" id-user="{{ $medic->user_id }}" {{ isset($_GET['local']) && $_GET['local'] == $medic->local_id || isset($timeSlot) && $timeSlot->local_id == $medic->local_id ? "selected" : "" }}>{{ $medic->name }}</option>
            @endforeach
        </select>

        <input type="hidden" name="user_id" value="">
    @else
        <select name="local" id="local" class="form-control select_plans show-menu-arrow" data-live-search="true" title="Selecione o local" data-actions-box="true">
            @foreach($doctors as $medic)
                <option value="{{ $medic->id }}" {{ isset($_GET['local']) && $_GET['local'] == $medic->id ? "selected" : "" }}>{{ $medic->name }}</option>
            @endforeach
        </select>
    @endif
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
<div class="form-group col-sm-4">
    {!! Form::label('slot_time_end', 'Final:') !!}
    {!! Form::time('slot_time_end', (isset($timeSlot) && isset($timeSlot->slot_time_end) )? $timeSlot->slot_time_end->format('H:i') : (isset($end) && $end !== null ? $end->format('H:i') : null), ['class' => 'form-control', 'required' => 'true']) !!}
</div>

<div class="form-group col-sm-4 slot_count">
    {{ Form::label('', 'Quantidade de pacientes que serão atendidos:') }}
    {{ Form::number('slot_count', isset($details) ? $details[0]->slot_count : null, ['class' => 'form-control', 'required']) }}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.calendar.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>


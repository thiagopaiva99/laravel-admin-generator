<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
<!-- Local Field -->
<div class="form-group col-sm-12">
    {!! Form::label('local_id', 'Local de atendimento:') !!}
    {!! Form::select('local_id', $locals, isset($local) ? $local : null, ['class' => 'form-control', 'required' => 'true']) !!}
</div>

{{--<!-- Start Datetime Field -->--}}
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('start_datetime', 'Início:') !!}--}}
    {{--{!! Form::datetime('start_datetime', (isset($start) && $start !== null ? $start->format('d-m-Y H:i:s') : null), ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'dd/mm/aaaa hh:mm:ss-fh']) !!}--}}
{{--</div>--}}

{{--<!-- End Datetime Field -->--}}
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('end_datetime', 'Final:') !!}--}}
    {{--{!! Form::datetime('end_datetime', (isset($end) && $end !== null ? $end->format('d-m-Y H:i:s') : null), ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'dd/mm/aaaa hh:mm:ss-fh']) !!}--}}
{{--</div>--}}




<!-- data_hora_inicio -->
<div class="form-group col-sm-6">
    {!! Form::label('data_hora_inicio', 'Início *') !!}
    {{--<div class="col-md-12">--}}
        {!! Form::input('datetime','start_datetime', (isset($start) && $start !== null ? $start->format('d/m/Y H:i:s') : (isset($closedDate) ? $closedDate->start_datetime->format("d/m/Y H:i:s") : null )), ['class' => 'form-control', 'readonly' => 'true', 'id' => 'data_inicio_input_id', 'placeholder' => 'Selecione a data de inicio do evento']) !!}
        <div class="separated_picker">
            {!! Form::input('hidden','data_inicio', isset($date_time_begin) && sizeof($date_time_begin > 1) ? $date_time_begin[0] : null) !!}
        </div>
        <div class="separated_picker">
            {!! Form::input('hidden','hora_inicio', isset($date_time_begin) && sizeof($date_time_begin > 1) ? substr($date_time_begin[1],0,5) : null) !!}
        </div>
        <span class="help-block">{{ $errors->first("data_hora_inicio") ? $errors->first("data_hora_inicio") : 'Clique para selecionar data e hora' }}</span>
    {{--</div>--}}
</div>
<!-- / data_hora_inicio -->

<!-- data_hora_final -->
<div class="form-group col-sm-6">
    {!! Form::label('data_hora_inicio', 'Fim *') !!}
    {{--<div class="col-md-12">--}}
        {!! Form::input('datetime','end_datetime', (isset($end) && $end !== null ? $end->format('d-m-Y H:i:s') : (isset($closedDate) ? $closedDate->end_datetime->format("d/m/Y H:i:s") : null )), ['class' => 'form-control', 'readonly' => 'true', 'placeholder' => 'Selecione a data de término do evento']) !!}
        <div class="separated_picker">
            {!! Form::input('hidden','data_final', isset($date_time_end) && sizeof($date_time_end > 1) ? $date_time_end[0] : null) !!}
        </div>
        <div class="separated_picker">
            {!! Form::input('hidden','hora_final', isset($date_time_end) && sizeof($date_time_end > 1) ? substr($date_time_end[1],0,5) : null) !!}
        </div>
        <span class="help-block">{{ $errors->first("data_hora_final") ? $errors->first("data_hora_final") : 'Clique para selecionar data e hora' }}</span>
    {{--</div>--}}
</div>
<!-- / data_hora_final -->




<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.calendar.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

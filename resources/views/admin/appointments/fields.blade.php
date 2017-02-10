<!-- Appointment Start Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appointment_start', 'Appointment Start:') !!}
    {!! Form::date('appointment_start', null, ['class' => 'form-control']) !!}
</div>

<!-- Appointment End Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appointment_end', 'Appointment End:') !!}
    {!! Form::date('appointment_end', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.appointments.index') !!}" class="btn btn-default">Cancelar</a>
</div>

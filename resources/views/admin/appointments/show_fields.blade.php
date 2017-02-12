<!-- Patient Name Field -->
<div class="form-group col-xs-4">
    {!! Form::label('patient_name', 'Paciente:') !!}
    <p>{!! $appointment->patient_name !!}</p>
</div>

<!-- Patient Phone Field -->
<div class="form-group col-xs-4">
    {!! Form::label('patient_phone', 'Telefone:') !!}
    <p>{!! (isset($appointment->patient_phone) && $appointment->patient_phone != '' ) ? $appointment->patient_phone : "Telefone ainda não informado" !!}</p>
</div>

<!-- Patient E-mail Field -->
<div class="form-group col-xs-4">
    {!! Form::label('patient_email', 'E-mail:') !!}
    <p>{!! (isset($appointment->patient_email) && $appointment->patient_email != '') ? '<a href="mailto:'.$appointment->patient_email.'" class="btn btn-default btn-social" target="_top"><i class="glyphicon glyphicon-envelope"></i> '.$appointment->patient_email.'</a>' : "" !!}</p>
</div>

<hr>

<!-- Appointment Start Field -->
<div class="form-group col-xs-4">
    {!! Form::label('appointment_start', 'Data:') !!}
    <p>{!! $appointment->appointment_start->format('d/m, H:i') !!} até {!! $appointment->appointment_end->format('H:i') !!}</p>
</div>

<!-- Local Name Field -->
<div class="form-group col-xs-6">
    {!! Form::label('local.name', 'Local:') !!}
    <p>{!! $appointment->timeSlotLocal->name !!}</p>
</div>





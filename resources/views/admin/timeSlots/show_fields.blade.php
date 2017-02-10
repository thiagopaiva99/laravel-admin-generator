<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $timeSlot->id !!}</p>
</div>

<!-- Slot Time Field -->
<div class="form-group">
    {!! Form::label('slot_time', 'Slot Time:') !!}
    <p>{!! $timeSlot->slot_time !!}</p>
</div>

<!-- Day Of Week Field -->
<div class="form-group">
    {!! Form::label('day_of_week', 'Day Of Week:') !!}
    <p>{!! $timeSlot->day_of_week !!}</p>
</div>

<!-- Slot Time Start Field -->
<div class="form-group">
    {!! Form::label('slot_time_start', 'Slot Time Start:') !!}
    <p>{!! $timeSlot->slot_time_start !!}</p>
</div>

<!-- Slot Time End Field -->
<div class="form-group">
    {!! Form::label('slot_time_end', 'Slot Time End:') !!}
    <p>{!! $timeSlot->slot_time_end !!}</p>
</div>

<!-- Slot Date Field -->
<div class="form-group">
    {!! Form::label('slot_date', 'Slot Date:') !!}
    <p>{!! $timeSlot->slot_date !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $timeSlot->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $timeSlot->updated_at !!}</p>
</div>


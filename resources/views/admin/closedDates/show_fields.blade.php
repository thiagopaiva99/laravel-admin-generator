<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $closedDate->id !!}</p>
</div>

<!-- Start Datetime Field -->
<div class="form-group">
    {!! Form::label('start_datetime', 'Start Datetime:') !!}
    <p>{!! $closedDate->start_datetime !!}</p>
</div>

<!-- End Datetime Field -->
<div class="form-group">
    {!! Form::label('end_datetime', 'End Datetime:') !!}
    <p>{!! $closedDate->end_datetime !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $closedDate->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $closedDate->updated_at !!}</p>
</div>


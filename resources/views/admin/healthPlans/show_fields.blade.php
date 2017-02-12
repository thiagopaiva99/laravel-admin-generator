<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Nome:') !!}
    <p>{!! $healthPlan->name !!}</p>
</div>

@if(isset($healthPlanFather) && $healthPlanFather != false)
    <div class="form-group">
        {{ Form::label('', 'Pertence ao plano de saúde: ') }}
        <p>{!! $healthPlanFather->name !!}</p>
    </div>
@endif


<style>
    .toggle{ margin-top: -10px; }
</style>

<!-- Name Field -->
<div class="form-group col-sm-6 col-md-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', isset($healthPlan) ? $healthPlan->name : null, ['class' => 'form-control', 'placeholder' => 'Insira o nome do novo plano de saúde', 'required' => 'true', 'data-error' => 'O nome do plano de saúde é um campo obrigatório']) !!}
    <div class="help-block with-errors"></div>
</div>

<div class="form-group col-sm-2 col-md-1">
    {!! Form::label('', 'Sub-plano?:') !!}
    <div class="checkbox">
        {!! Form::checkbox('have_plan', 0, null, ['data-toggle' => 'toggle', 'data-onstyle' => 'success', 'data-height' => '30', 'data-width' => '100', 'data-on' => 'SIM', 'data-off' => 'NÂO', 'id' => 'sub_plan']) !!}
        <input type="hidden" name="have_to" value="false">
    </div>
</div>

<div class="form-group col-sm-2 col-md-2 select_sub_plan" style="display: none;">
    {{ Form::label('', 'Escolha o plano: ') }}
    {{ Form::text('health_plan_name', null, ['class' => 'form-control', 'placeholder' => 'Insira o nome do plano de saúde']) }}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-4 col-md-2">
    <label for="">Ações:</label><br>
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.healthPlans.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

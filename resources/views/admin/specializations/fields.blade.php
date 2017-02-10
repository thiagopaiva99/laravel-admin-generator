<!-- Name Field -->
<div class="form-group col-sm-6 col-md-8">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Insira o novo nome da especialização!', 'data-error' => 'O nome da especialazação é um campo obrigatório']) !!}
    <div class="help-block with-errors"></div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 col-md-4">
    <label for="">Ações:</label><br>
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-danger']) !!}
        <a href="{!! route('admin.specializations.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

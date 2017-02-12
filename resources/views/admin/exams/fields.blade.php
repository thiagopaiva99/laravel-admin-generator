<!-- Name Field -->
<div class="form-group col-sm-6 col-md-8">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Insira o nome do novo exame', 'data-error' => 'O nome do exame é um campo obrigatório']) !!}
    <div class="help-block with-errors"></div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 col-md-4">
    <label for="">Ações:</label><br>
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
        <a href="{!! route('admin.exams.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

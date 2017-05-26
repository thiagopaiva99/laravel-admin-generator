<!-- Key Field -->
<div class="form-group col-sm-5">
    {!! Form::label('key', 'Key:') !!}
    {!! Form::text('key', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Insira a opçao']) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-5">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::text('value', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Insira o valor que ela vai ter']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-2">
    {!! Form::label('', 'Açoes:') !!}
    <div class="btn-group">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.options.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>

<hr>

<div class="col-md-12">
    <h4>Precisa de ajuda?</h4>
    <button type="button" class="btn btn-options btn-primary">Exibir opçoes disponiveis</button>
    
    <div id="options" style="display: none; margin-top: 15px;">
        @include('admin.options.list')
    </div>
</div>

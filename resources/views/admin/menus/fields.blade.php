<!-- Menu Field -->
<div class="form-group col-sm-4">
    {!! Form::label('menu', 'Menu:') !!}
    {!! Form::text('menu', null, ['class' => 'form-control', 'placeholder' => 'Digite o nome do menu', 'required' => 'true']) !!}
</div>

<!-- Link To Field -->
<div class="form-group col-sm-4">
    {!! Form::label('link_to', 'Link:') !!}
    {!! Form::text('link_to', null, ['class' => 'form-control', 'placeholder' => 'path/to', 'required' => 'true']) !!}
</div>


<!-- Icon Field -->
<div class="form-group col-sm-4">
    {!! Form::label('icon', 'Icone:') !!}
    {!! Form::text('icon', null, ['class' => 'form-control', 'placeholder' => 'fa fa-some-icon', 'required' => 'true']) !!}
</div>

<!-- Active Field -->
<div class="form-group col-sm-3">
    {!! Form::label('active', 'Ativado quando:') !!}
    {!! Form::text('active', null, ['class' => 'form-control', 'placeholder' => '/some/route/*', 'required' => 'true']) !!}
</div>

<!-- Menu Root Field -->
<div class="form-group col-sm-3">
    {!! Form::label('menu_root', 'Menu Pai:') !!}
    {!! Form::text('menu_root', null, ['class' => 'form-control', 'placeholder' => 'Em breve...', 'disabled' => 'true']) !!}
</div>

<!-- Appears To Field -->
<div class="form-group col-sm-3">
    {!! Form::label('appears_to', 'Visivel Para:') !!}
    {!! Form::select('appears_to', $types, null, ['class' => 'form-control', 'placeholder' => 'Selecione o nivel de usuario', 'required' => 'true']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-3">
    {!! Form::label('order', 'Ordem:') !!}
    {!! Form::number('order', null, ['class' => 'form-control', 'placeholder' => 'Digite a ordem', 'required' => 'true']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.menus.index') !!}" class="btn btn-default">Cancel</a>
</div>

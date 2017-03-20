<!-- Menu Field -->
<div class="form-group col-sm-6">
    {!! Form::label('menu', 'Menu:') !!}
    {!! Form::text('menu', null, ['class' => 'form-control']) !!}
</div>

<!-- Link To Field -->
<div class="form-group col-sm-6">
    {!! Form::label('link_to', 'Link:') !!}
    {!! Form::text('link_to', null, ['class' => 'form-control']) !!}
</div>


<!-- Icon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('icon', 'Icon:') !!}
    {!! Form::text('icon', null, ['class' => 'form-control']) !!}
</div>

<!-- Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('active', 'Active:') !!}
    {!! Form::text('active', null, ['class' => 'form-control']) !!}
</div>

<!-- Menu Root Field -->
<div class="form-group col-sm-6">
    {!! Form::label('menu_root', 'Menu Root:') !!}
    {!! Form::text('menu_root', null, ['class' => 'form-control']) !!}
</div>

<!-- Appears To Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appears_to', 'Appears To:') !!}
    {!! Form::text('appears_to', null, ['class' => 'form-control']) !!}
</div>

<!-- Order Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order', 'Order:') !!}
    {!! Form::text('order', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.menus.index') !!}" class="btn btn-default">Cancel</a>
</div>

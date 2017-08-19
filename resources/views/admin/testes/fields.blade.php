<!-- Aaaa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('aaaa', 'Aaaa:') !!}
    {!! Form::text('aaaa', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.testes.index') !!}" class="btn btn-default">Cancel</a>
</div>

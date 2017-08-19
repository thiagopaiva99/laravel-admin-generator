<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Titulo do site:') !!}
    {!! Form::text('title', getenv("HOLDER_TITLE"), ['class' => 'form-control', 'placeholder' => 'Digite o titulo do site']) !!}
</div>

<!-- Phrase Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phrase', 'Frase do holder:') !!}
    {!! Form::text('phrase', getenv("HOLDER_PHRASE"), ['class' => 'form-control', 'placeholder' => 'Digite a frase do holder']) !!}
</div>

<!-- Color Field -->
<div class="form-group col-sm-6">
    {!! Form::label('color', 'Cor de fundo:') !!}
    {!! Form::text('color', getenv("HOLDER_COLOR"), ['class' => 'form-control', 'placeholder' => 'Digite a cor de fundo']) !!}
</div>

<!-- Contact Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact', 'Email de contato:') !!}
    {!! Form::text('contact', getenv("HOLDER_CONTACT"), ['class' => 'form-control', 'placeholder' => 'Digite o email de contato']) !!}
</div>

<!-- Image Field - VAI SR TROCADO -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Link da imagem:') !!}
    {!! Form::text('image', getenv("HOLDER_IMAGE"), ['class' => 'form-control', 'placeholder' => 'Digite a imagem do holder']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Gerar Holder', ['class' => 'btn btn-danger']) !!}
    <a href="{!! url('holders') !!}" class="btn btn-default">Cancelar</a>
</div>
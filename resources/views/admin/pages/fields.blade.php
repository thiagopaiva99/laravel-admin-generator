<!-- Name Field -->
<div class="col-md-12">
    <div class="row">
        <div class="form-group col-md-3">
            {!! Form::label('name', 'Nome da pagina:') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true', 'placeholder' => 'Nome da pagina']) !!}
        </div>
    </div>
</div>

    <div class="row appended">

    </div>

<div class="col-md-12">
    <hr>

    <button class="btn btn-primary btn-new-field">Adicionar campo</button>
    <button class="btn btn-danger btn-generate" disabled >Gerar Pagina</button>
</div>
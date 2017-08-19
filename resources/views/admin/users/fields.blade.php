<div class="col-md-9">

    @if(Auth::user()->user_type == 1)
        @if(!isset($user))
            <!-- Address Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('user_type', 'Tipo de usuário:') !!}
                    {!! Form::select('user_type', [1 => 'Administrador'], null, ['class' => 'form-control', isset($user) ? '' : 'requried']) !!}
                    <div class="help-block with-errors"></div>
                </div>
        @endif
    @endif

    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Nome:') !!}
        {!! Form::text('name', isset($old) ? $old['name'] : null, ['class' => 'form-control', 'required' => 'true', 'data-error' => 'O nome é um campo obrigatório', 'placeholder' => 'Preencha com o nome do usuário']) !!}
        <div class="help-block with-errors"></div>
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('email', 'E-mail:') !!}
        {!! Form::email('email', isset($old) ? $old['email'] : null, ['class' => 'form-control', isset($user) ? 'readonly' : '', 'required' => 'true', 'data-error' => 'O email é um campo obrigatório', 'placeholder' => 'Preencha com o email do usuário']) !!}
        <div class="help-block with-errors"></div>
    </div>

    {{-- Address field --}}
    <div class="form-group col-sm-12">
        {!! Form::label('', 'Endereço:' ) !!}
        {!! Form::text('address', isset($old) ? $old['address'] : null, ['class' => 'form-control', 'placeholder' => 'Preencha com o endereço do usuário', 'required' => 'required', 'data-error' => 'O endereço é um campo obrigatório']) !!}
        <div class="help-block with-errors"></div>
    </div>

    <!-- Phone Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('phone', 'Telefone:') !!}
        {!! Form::tel('phone', isset($old) ? $old['phone'] : null, ['class' => 'form-control', 'data-mask' => '(00) 0000-0000', 'placeholder' => 'Preencha com o telefone do usuário', 'required' => 'required', 'data-error' => 'O telefone é um campo obrigatório']) !!}
        <div class="help-block with-errors"></div>
    </div>

@if(Auth::user()->user_type == 1)
    @if(isset($user))
        <!-- Approval Status -->
        <div class="form-group col-sm-12">
            {!! Form::label('approval_status', 'Status:') !!}
            {!! Form::select('approval_status', $approvalStatus, null, ['class' => 'form-control']) !!}
        </div>
    @endif
@endif

<!-- Password Field -->
<div class="form-group col-sm-12">
    {!! Form::label('password', 'Senha:') !!}
    {!! Form::password('password', ['class' => 'form-control', isset($user) ? '' : 'required', 'id' => 'password_new', 'data-error' => 'A senha é um campo obrigatório', 'placeholder' => 'Preencha com a senha do usuário']) !!}
    <div class="help-block with-errors"></div>
</div>

<!-- Password Field -->
<div class="form-group col-sm-12">
    {!! Form::label('password_confirmation', 'Confirme a senha:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control', isset($user) ? '' : 'required', 'data-match' => '#password_new', 'data-match-error' => 'As senhas não são iguais', 'data-error' => 'A confirmação de senha é um campo obrigatório', 'placeholder' => 'Confirme a senha do usuário']) !!}
    <div class="help-block with-errors"></div>
</div>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 col-md-12 text-center">
    <div class="btn-group">
        {!! Form::submit('Salvar', ['class' => 'btn btn-danger']) !!}
        <a href="{!! route('admin.users.index') !!}" class="btn btn-default">Cancelar</a>
    </div>
</div>

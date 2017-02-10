<!-- imagem -->
<div class="form-group col-sm-12 col-md-3" id="div_img">
    {!! Form::label("image_src", 'Imagem de perfil:') !!}
    @if(isset($user) && $user->image_src != '')
        <div class="img-responsive">
            <img class="img-thumbnail" name="imagem" id="imagem" src="{{ $user->image_url }}" />
            <br><br>
        </div>
    @else
        <div class="img-responsive">
            <img class="img-thumbnail" name="imagem" id="imagem" style="width: 100%;" src="{{ asset('assets/site/images/img_medicos.jpg') }}" />
        </div>
    @endif
    <img class="uploaded-image img-thumbnail hidden" src="image.jpg" />

    <br>

    <div class="progress" id="loading">
        <div id="progressCounter" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; background: #361121;"></div>
        <span class="sr-only">0% complete</span>
    </div>

    {!! Form::file('featured', null, ['class' => 'filestyle']) !!}
    {!! Form::text("image_src", isset($user) ? $user->image_src : "", ['class' => 'hidden']) !!}
</div>
<!--  /imagem -->

<div class="col-md-9">

    @if(Auth::user()->user_type == 1)
        @if(!isset($user))
            <!-- Address Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('user_type', 'Tipo de usuário:') !!}
                    {!! Form::select('user_type', [1 => 'Administrador', 2 => 'Médico', 3 => 'Paciente', 4 => 'Clínica'], null, ['class' => 'form-control', isset($user) ? '' : 'requried']) !!}
                    <div class="help-block with-errors"></div>
                </div>
        @endif
    @endif

    {{-- Clinics Field --}}
    <div class="form-group col-sm-12 select-clinics">
        {{ Form::label('', 'Selecione uma clínica: ') }}
        {{ isset($clinics) ? Form::select('clinics', $clinics, null, ['class' => 'form-control select-clinics-select show-menu-arrow', 'data-live-search' => 'true', 'title' => 'Selecione a clinica', 'data-actions-box' => "true"])  : '' }}
    </div>

    @if(isset($user) && $user->user_type == 2)
        <div class="form-group col-sm-12">
            {{ Form::label('', 'Selecione uma clínica: ') }}
            {{ isset($clinics) ? Form::select('clinics', $clinics, null, ['class' => 'form-control select-clinics-select show-menu-arrow', 'data-live-search' => 'true', 'title' => 'Selecione a clinica', 'data-actions-box' => "true"])  : '' }}
        </div>
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

    <!-- CRM Id Field -->
    <div class="form-group col-sm-12 {{ Auth::user()->user_type == \App\Models\User::UserTypeClinic ? '' : 'crm' }}">
        {{ Form::label('', 'Número de conselho:') }}
        {{ Form::text('crm', isset($old) ? $old['crm'] : null, ['class' => 'form-control', isset($user) ? 'readonly' : '', 'required' => 'true', 'data-error' => 'O CRM do médico é um campo obrigatório', 'placeholder' => 'Preencha com o número de conselho']) }}
        <div class="help-block with-errors"></div>
    </div>

    <!-- CPF Id Field -->
    <div class="form-group col-sm-12 {{ Auth::user()->user_type == \App\Models\User::UserTypeClinic || Auth::user()->user_type == \App\Models\User::UserTypeDoctor ? '' : 'cpf' }}">
        {{ Form::label('', 'CPF:') }}
        {{ Form::text('cpf', isset($old) ? $old['cpf'] : null, ['class' => 'form-control', isset($user) ? 'readonly' : '', 'data-error' => 'O CPF do médico é um campo obrigatório', 'placeholder' => 'Preencha com o CRM']) }}
        <div class="help-block with-errors"></div>
    </div>

    <!-- Facebook Id Field -->
    <div class="form-group col-sm-12 facebook-input">
        {!! Form::label('facebook_id', 'Facebook:') !!}
        {!! Form::text('facebook_id', isset($old) ? $old['facebook_id'] : null, ['class' => 'form-control', 'placeholder' => 'Preencha com a id do facebook do usuário']) !!}
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
        @if($user->user_type == 2)
            <!-- Preferred User Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('preferred_user', 'Preferencial:') !!}
                    <div class="checkbox">
{{--                        {!! Form::checkbox('preferred_user') !!}--}}
                        {!! Form::checkbox('preferred_user', ($user->preferred_user) ? 1 : 0, null, ['data-toggle' => 'toggle', 'data-onstyle' => 'success', 'data-height' => '30', 'data-width' => '100', 'data-on' => 'SIM', 'data-off' => 'NÂO']) !!}
                    </div>
                </div>

                <!-- Approval Status -->
                <div class="form-group col-sm-12">
                    {!! Form::label('approval_status', 'Status:') !!}
                    {!! Form::select('approval_status', $approvalStatus, null, ['class' => 'form-control']) !!}
                </div>
        @endif
    @else
    @endif

@else

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

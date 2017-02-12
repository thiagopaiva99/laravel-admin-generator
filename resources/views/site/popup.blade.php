<div class="popup">
    <div class="popup-container">
        <div class="popup-wrapper">
            <span class="close-all-popup"><i class="fa fa-close"></i></span>

            <div class="has-popup-content popup1">
                <div class="container">
                    <div class="row">
                        <div class="col offset-l1 l10 m12 s12 column">
                            <div class="registration-page">
                                <div class="theme-form login-form" style="height: 752px;">
                                    <div  id="form_loginn">
                                        <div class="white-title">
                                            <i class="icon-key"></i>
                                            <h4>FAÇA LOGIN</h4>
                                            <span>Preencha os campos abaixo para acessar a área exclusiva</span>
                                            {!! isset($error) ? $error : '' !!}
                                        </div>
                                        {!! Form::open(['url' => 'site/login']) !!}
                                        <div class="input-field col s12">
                                            {!! Form::email('email', null, ['placeholder' => 'Endereço de email']) !!}
                                        </div>
                                        <div class="input-field col s12">
                                            {!! Form::password('password', ['placeholder' => 'Senha']) !!}
                                        </div>
                                        <a class="a-forgott" title="" href="{{ url('password/reset') }}">Esqueceu seu usuário ou senha?</a>
                                        <div class="input-field col s12">
                                            <button type="submit">Entrar</button>
                                        </div>
                                        <div class="other-logins"  style="padding-bottom: 70px;">
                                            <strong>Ou, faça login com:</strong>
                                            <a class="facebook-hover" href="{{ url('login/facebook') }}" title=""><i class="fa fa-facebook"></i></a>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>

                                    <div  id="forgot_formm" style="display: none;">
                                        <div class="white-title">
                                            <i class="icon-key"></i>
                                            <h4>RECUPERAR SENHA</h4>
                                            <span>Preencha oo campo abaixo com seu email para poder recuperar sua senha</span>
                                            {!! isset($error) ? $error : '' !!}
                                        </div>
                                        {!! Form::open(['url' => 'password/email']) !!}
                                        <div class="input-field col s12">
                                            {!! Form::email('forgot_email', null, ['placeholder' => 'Endereço de email']) !!}

                                        </div>
                                        <a class="a-cancell" title="">Cancelar</a>
                                        <div class="input-field col s12">
                                            <button type="submit">Recuperar Senha</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>

                                </div><!-- Login Form -->
                                <div class="theme-form registration-form"  style="height: 752px;">
                                    <div class="white-title">
                                        <i class="icon-signin"></i>
                                        <h4>FAÇA SEU CADASTRO</h4>
                                        <span>Preencha os campos abaixo para agendar consultas e muito mais</span>
                                        {!! isset($response) ? $response : '' !!}
                                    </div>
                                    {!! Form::open(['url' => 'cadastro']) !!}
                                    <div class="input-field col s12">
                                        {!! Form::text('name', null, ['placeholder' => 'Nome completo']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::email('email', null, ['placeholder' => 'Endereço de email']) !!}
                                    </div>
                                    @if(isset($health_plans))
                                        <div class="input-field col s12">
                                            <p>
                                                {!! Form::checkbox('private_health_plan', 'private', false, ['class' => 'filled-in', 'id' => 'private_health_plan']) !!}
                                                {!! Form::label('private_health_plan', 'Particular') !!}
                                            </p>
                                        </div>
                                        <div class="input-field col s12 select-field">
                                            <select name="health_plans[]" class="chosen-select" data-placeholder="Plano de Saúde" multiple>
                                                @foreach($health_plans as $plan)
                                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                    @if(sizeof($plan->healthPlans)>0)
                                                        @foreach($plan->healthPlans as $sub_plan)
                                                            <option class="hp_id hp-{{ $sub_plan->health_plan_id }}" value="{{ $sub_plan->id }}">{{ $sub_plan->name }}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="input-field col s12">
                                        {!! Form::password('password', ['placeholder' => 'Senha']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::password('password_confirmation', ['placeholder' => 'Confirme sua senha']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        <button type="submit"><i class="fa fa-user-md"></i>Cadastrar</button>
                                    </div>
                                    <div class="other-logins">
                                        <strong>Ou, cadastre-se com:</strong>
                                        <a class="facebook-hover" href="{{ url('login/facebook') }}" title=""><i class="fa fa-facebook"></i></a>
                                    </div>
                                    {!! Form::close() !!}
                                </div><!-- Registration Form -->
                            </div><!-- Registration Popup -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="has-popup-content register-dr">
                <div class="container">
                    <div class="row">
                        <div class="col s12 offset-s3">
                            <div class="registration-page">
                                <div class="theme-form registration-form">
                                    <div class="white-title">
                                        <i class="icon-signin"></i>
                                        <h4>FAÇA SEU CADASTRO</h4>
                                        <span>Preencha os campos abaixo para agendar consultas e muito mais</span>
                                        {!! isset($response) ? $response : '' !!}
                                    </div>
                                    {!! Form::open(['url' => 'medicos-cadastro', 'id' => 'formulario_medicos']) !!}
                                    <div class="input-field col s12">
                                        {!! Form::text('name', null, ['placeholder' => 'Nome completo']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::text('phone', null, ['placeholder' => 'Telefone']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::email('email', null, ['placeholder' => 'Endereço de email']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::password('password', ['placeholder' => 'Senha', 'id' => 'password']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::password('password_confirmation', ['placeholder' => 'Confirme sua senha']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        <button type="submit"><i class="fa fa-user-md"></i>Cadastrar</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div><!-- Registration Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><!-- Popup -->
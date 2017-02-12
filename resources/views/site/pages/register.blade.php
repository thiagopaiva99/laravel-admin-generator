@extends('site.page')

@section('page.content')

    <section>
        <div class="block">
            <div class="parallax-container">
                <div data-speed="1" data-bleed="12" class="parallax">
                    <img src="{{ url('assets/site/images/parallax6.jpg') }}" alt="" />
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col offset-l1 l10 m12 s12 column">
                        @if(isset($errorFacebook))
                            <div style="width: 86%; margin-left: 7%;" class="alert alert-dismiss-facebook" onload="hide_alert()">
                                <div class="row">
                                    <div class="" style="width: 100%; color: #fff; border-radius: 10px;">
                                        <div class="card-panel dark-btn" style="font-size: 15px; text-align: center; width: 100%;">
                                            Você não concedeu acesso a alguma informação necessária, libere o acesso ou cadastre-se pelo formulário!
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="registration-page">
                            <div class="theme-form login-form" style="height: 752px;">
                                <div  id="form_login">
                                    <div class="white-title">
                                        <i class="icon-key"></i>
                                        <h4>FAÇA LOGIN</h4>
                                        <span>Preencha os campos abaixo para acessar a área exclusiva</span>
                                        @if(isset($error) || isset($msg))
                                            <div style="width: 120%;" class="dismiss-alert">
                                                @include("flash::message")
                                            </div>
                                        @endif
                                    </div>
                                    {!! Form::open(['url' => 'site/login']) !!}
                                    <div class="input-field col s12">
                                        {!! Form::email('email', null, ['placeholder' => 'Endereço de email']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::password('password', ['placeholder' => 'Senha']) !!}
                                    </div>
                                    <a class="a-forgot" title="">Esqueceu seu usuário ou senha?</a>
                                    <div class="input-field col s12">
                                        <button type="submit">Entrar</button>
                                    </div>
                                    <div class="other-logins"  style="padding-bottom: 70px;">
                                        <strong>Ou, faça login com:</strong>
                                        <a class="facebook-hover" href="{{ url('login/facebook') }}" title=""><i class="fa fa-facebook"></i></a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                                <div  id="forgot_form" style="display: none;">
                                    <div class="white-title">
                                        <i class="icon-key"></i>
                                        <h4>RECUPERAR SENHA</h4>
                                        <span>Preencha oo campo abaixo com seu email para poder recuperar sua senha</span>
                                    </div>
                                    {!! Form::open(['url' => 'site/recuperar-senha']) !!}
                                    <div class="input-field col s12">
                                        {!! Form::email('forgot_email', null, ['placeholder' => 'Endereço de email']) !!}
                                    </div>
                                    <a class="a-cancel" title="">Cancelar</a>
                                    <div class="input-field col s12">
                                        <button type="submit">Recuperar Senha</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                            </div><!-- Login Form -->
                            <div class="theme-form registration-form" style="height: 752px;">
                                <div class="white-title">
                                    <i class="icon-signin"></i>
                                    <h4>FAÇA SEU CADASTRO</h4>
                                    <span>Preencha os campos abaixo para agendar consultas e muito mais</span>
                                    @if(isset($response))
                                        <div style="width: 120%;" class="dismiss-alert">
                                            @include("flash::message")
                                        </div>
                                    @endif
                                </div>
                                {!! Form::open(['url' => 'cadastro', 'id' => 'formulario_cadastro']) !!}
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
                                        {!! Form::password('password', ['placeholder' => 'Senha', 'id' => 'password']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        {!! Form::password('password_confirmation', ['placeholder' => 'Confirme sua senha']) !!}
                                    </div>
                                    <div class="input-field col s12">
                                        <button type="submit">Cadastrar</button>
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
    </section>

@stop

@section('page.scripts')
    <script>
        $(document).ready(function(){
           hide_alert();
        });
    </script>
@stop
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
                    <div class="col s12 offset-s2">
                        <div class="registration-page">
                            <div class="theme-form form-registration">
                                <div class="white-title">
                                    <i class="icon-signin"></i>
                                    <h4>ATUALIZE SEU CADASTRO</h4>
                                    <span>Preencha os campos abaixo com seus dados mais atuais!</span>

                                    <div class="dismiss-alert" style="width: 115%;">
                                        @include("flash::message")
                                    </div>

                                </div>
                                {{ Form::open(['url' => 'atualizar', 'class' => '', 'files' => 'true']) }}
                                <div class="input-field col s12">
                                    {!! Form::text('name', (Auth::check()) ? Auth::user()->name : null, ['placeholder' => 'Nome completo']) !!}
                                </div>

                                <div class="input-field col s12">
                                    {{ Form::email('email', (Auth::check()) ? Auth::user()->email : null, ['placeholder' => 'Endereço de email', 'disabled' => 'true']) }}
                                </div>

                                @if(isset($health_plans))
                                    <div class="input-field col s12">
                                        <p>
                                            {!! Form::checkbox('private_health_plan', 'private', (Auth::check()) ? Auth::user()->private_health_plan : null, ['class' => 'filled-in', 'id' => 'private_health_plan']) !!}
                                            {!! Form::label('private_health_plan', 'Particular') !!}
                                        </p>
                                    </div>
                                    <div class="input-field col s12 select-field" {!! (Auth::check() && Auth::user()->private_health_plan) ? 'style="display:none;"' : ''  !!}>
                                        <select name="health_plans[]" class="chosen-select" data-placeholder="Plano de Saúde" multiple>
                                            @foreach($health_plans as $plan)
                                                <option value="{{ $plan->id }}" {{ in_array($plan->id, $my_health_plans) ? 'selected' : '' }}>{{ $plan->name }}</option>
                                                @if(sizeof($plan->healthPlans)>0)
                                                    @foreach($plan->healthPlans as $sub_plan)
                                                        <option class="hp_id hp-{{ $sub_plan->health_plan_id }}" value="{{ $sub_plan->id }}" {{ in_array($sub_plan->id, $my_health_plans) ? 'selected' : '' }}>{{ $sub_plan->name }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="input-field col s12">
                                    {{ Form::password('password', ['placeholder' => 'Nova senha']) }}
                                </div>

                                <div class="input-field col s12">
                                    {{ Form::password('password_confirmation', ['placeholder' => 'Confirme a nova senha']) }}
                                </div>

                                <div class="input-field col s12">
                                    {{ Form::text('phone', (Auth::check()) ? Auth::user()->phone : null, ['placeholder' => 'Telefone de contato']) }}
                                </div>

                                <div class="input-field col s12">
                                    {{ Form::text('address', (Auth::check()) ? Auth::user()->address : null, ['placeholder' => 'Endereço completo']) }}
                                </div>

                                <div class="file-field input-field col s12">
                                    <div class="btn">
                                        <i class="fa fa-camera"></i>
                                        <input type="file" id="imgInput" name="featured_upload" accept="image/*">
                                    </div>
                                    <div class="file-path-wrapper">
                                        {{ Form::text('image_src', null, ['class' => 'file-path validate', 'placeholder' => 'Clique para escolher sua foto']) }}
                                    </div>
                                </div>

                                <img id="view-img" src="{{ Auth::user()->image_src }}" style="{{ (Auth::user()->image_src != null) ? 'background: #fff; border-radius: 3px; box-shadow: 0px 0px 15px #555' : 'display: none' }}; margin-top: 10px; width: 300px;">

                                <div class="input-field col s12">
                                    <button type="submit"><i class="fa fa-user-md"></i> Atualizar Seus Novos Dados</button>
                                </div>

                                {{ Form::close() }}
                            </div>
                        </div><!-- Registration Popup -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
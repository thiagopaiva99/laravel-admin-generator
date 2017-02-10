@if(isset($user->image_src) && $user->image_src != "")
<!-- Image -->
<div class="form-group col-xs-12 col-md-3">
    <div class="center">
        <img src="{{$user->image_url }}" class="img img-responsive img-thumbnail" style="width: 100%"/>
    </div>
</div>
@else
<!-- Image -->
<div class="form-group col-xs-12 col-md-3">
    <div class="center">
        <img src="{{ asset('assets/site/images/logo_300x300.png') }}" class="img img-responsive img-thumbnail" style="width: 100%"/>
    </div>
</div>
@endif

<div class="col-md-9">

    <div>
        <h1 class="inline"><i class="fa fa-user"></i> {!! $user->name !!}</h1>

        <span class="pull-right">
            <a href="https://www.facebook.com/{{ $user->facebook_id }}" class="btn btn-social btn-default btn-facebook" target="_blank"><i class="fa fa-facebook"></i> Ver perfil</a>
        </span>
    </div>

    <hr>

    <div>
        <p>
            <b>
                Email: <br>
            </b>

            {!! $user->email !!}
        </p>
    </div>

    <div>
        <p>
            <b>
                Telefone: <br>
            </b>

            {!! ($user->phone != "") ? $user->phone : "Telefone ainda não informado."!!}
        </p>
    </div>

    <div>
        <p>
            <b>
                Endereço: <br>
            </b>

            {!! ($user->address != "") ? $user->address : "Endereço ainda não informado."!!}
        </p>
    </div>

    <div>
        <p>
            <b>
                Tipo de usuário: <br>
            </b>

            {!! ($user->user_type != 2) ? ($user->user_type == 1) ? "Administrador" : "Paciente." : "Médico." !!}
        </p>
    </div>

    <div>
        <p>
            <b>
                Número de conselho: <br>
            </b>

            {!! $user->crm !!}
        </p>
    </div>

    <div>
        <p>
            <b>
                CPF: <br>
            </b>

            {!! $user->cpf !!}
        </p>
    </div>

    {{--<div class="well well-lg">...</div>--}}

    {{--<div class="well well-sm">--}}
        {{--<h3>Dados básicos:</h3>--}}

        {{--<hr>--}}

        {{--<p>--}}
            {{--<h3>Nome:</h3>--}}
            {{--<h4>{!! $user->name !!}</h4>--}}
        {{--</p>--}}

        {{--<p>--}}
            {{--<h3>Email:</h3>--}}
            {{--<h4>{!! $user->email !!}</h4>--}}
        {{--</p>--}}
    {{--</div>--}}

    {{--<p>--}}
        {{--<h3>Perfil do Facebook:</h3>--}}
        {{--<a href="https://www.facebook.com/{{ $user->facebook_id }}" class="btn btn-default btn-facebook" target="_blank"><i class="fa fa-facebook"></i> Ver perfil</a>--}}
    {{--</p>--}}

    {{--<p>--}}
        {{--<h3>Endereço:</h3>--}}
        {{--<h4>{!! $user->address !!}</h4>--}}
    {{--</p>--}}

    {{--<p>--}}
        {{--<h3>Telefone:</h3>--}}
        {{--<h4>{!! $user->phone !!}</h4>--}}
    {{--</p>--}}

    {{--<!-- Name Field -->--}}
    {{--<div class="form-group col-xs-4">--}}
        {{--{!! Form::label('name', 'Nome:') !!}--}}
        {{--<p>{!! $user->name !!}</p>--}}
    {{--</div>--}}

    {{--<!-- Email Field -->--}}
    {{--<div class="form-group col-xs-4">--}}
        {{--{!! Form::label('email', 'E-mail:') !!}--}}
        {{--<p>{!! $user->email !!}</p>--}}
    {{--</div>--}}

    {{--<!-- Facebook Id Field -->--}}
    {{--<div class="form-group col-xs-4">--}}
        {{--{!! Form::label('facebook_id', 'Facebook:') !!}--}}
        {{--@if (isset($user->facebook_id) && $user->facebook_id != "")--}}
            {{--<p><a href="https://www.facebook.com/{{ $user->facebook_id }}" class="btn btn-default btn-facebook"><i class="fa fa-facebook"></i> Ver perfil</a></p>--}}
        {{--@else--}}
            {{--<p>Não disponível</p>--}}
        {{--@endif--}}
    {{--</div>--}}

    {{--<!-- Address Field -->--}}
    {{--<div class="form-group col-xs-8">--}}
        {{--{!! Form::label('address', 'Endereço:') !!}--}}
        {{--<p>{!! $user->endereco !!}</p>--}}
    {{--</div>--}}

    {{--<!-- Phone Field -->--}}
    {{--<div class="form-group col-xs-4">--}}
        {{--{!! Form::label('phone', 'Telefone:') !!}--}}
        {{--<p>{!! $user->telefone !!}</p>--}}
    {{--</div>--}}
</div>
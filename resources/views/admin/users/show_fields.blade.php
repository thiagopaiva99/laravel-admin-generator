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

            {!! ($user->user_type != 2) ? ($user->user_type == 1) ? "Administrador" : "Cliente." : "Profissional." !!}
        </p>
    </div>
</div>
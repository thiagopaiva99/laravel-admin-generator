@extends('auth.emails.base')

@section('conteudo')
    <div style="color: black;">
        {{-- Se for um cadastro de novo medico --}}
        @if($type == 'new_medic')
            {{--Ola DocSaúde, um novo médico se cadastrou no site, segua algumas informações:--}}
            <p>Ola Docsaúde!</p>
            <p>Um novo médico se cadastrou no site!</p>
            <p>Segue aqui algumas informações do médico:</p>
            <br><strong>Nome:</strong> {{ isset($user) ? $user->name : '' }}
            <br><strong>Email:</strong> {{ isset($user) ? $user->email : '' }}
            <br><br>
        @endif

        @if($type == 'appointment_marked')
            <br>
            Olá <strong>{{ $user->name }}</strong>, sua consulta foi marcada com sucesso!
            <br>
        @endif

        @if($type == 'approval_medic')
            <br>
            Olá <strong>{{ $user->name }}</strong>, você foi aprovado no site Docsaúde!
            <br>
        @endif

        @if($type == 'new_user')
            <br>
            Olá <strong>{{ $user[0]->name }}</strong>, você foi cadastrado no site Docsaúde!
            <br>
        @endif

        @if($type == 'make_appointment')
            <br>
            Olá <strong>{{ $user->name }}</strong>, sua consulta foi marcado com sucesso!<br>
            <strong>
                Informações:
            </strong>
            <p>
                Data da consulta: dia {{ date("d/m/Y", strtotime($array['epoch'])) }} ás {{ date("H:m", strtotime($array['epoch'])) }}
            </p>
            <br>
        @endif

        @if($type == 'cancel_appointment')
            <br>
            Olá <strong>{{ $user->name }}</strong>, você fez o cancelamento da sua consulta!
            <br>
            <br>
        @endif

        @if($type == 'new_health_plan')
            <br>
            <p>
                Olá Dr.!
            </p>
            <p>
                Inserimos um novo plano de saúde em nosso site.
            </p>
            <p>
                Novo plano de saúde:
                <strong>
                    {{ $array['healthPlan'] }}
                </strong>
            </p>
            <p>
                Atualize seu cadastro agora mesmo <a href="http://www.docsaude.com/admin/users/" class="text-green">clicando aqui</a>!
            </p>
            <br>
        @endif

        @if($type == 'new_specialization')
            <br>
            <p>
                Olá Dr.!
            </p>
            <p>
                Inserimos uma nova especialização em nosso site!
            </p>
            <p>
                Nova especialização:
                <strong>
                    {{ $array['specialization'] }}
                </strong>
            </p>
            <p>
                Atualize seu cadastro agora mesmo <a href="http://www.docsaude.com/admin/users/" class="text-green">clicando aqui</a>!
            </p>
            <br>
        @endif

        @if($type == 'new_exam')
            <br>
            <p>
                Olá Dr.!
            </p>
            <p>
                Inserimos um novo tipo de exame em nosso site.
            </p>
            <p>
                Novo exame:
                <strong>
                    {{ $array['exam'] }}
                </strong>
            </p>
            <p>
               Atualize seu cadastro agora mesmo <a href="http://www.docsaude.com/admin/users/" class="text-green">clicando aqui</a>!
            </p>
            <br>
        @endif
    </div>
@stop
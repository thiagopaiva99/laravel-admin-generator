<?php

$menu = [
        'medicos'               => 'Médicos',
        'encontre-um-medico'    => 'Encontre um Médico',
        'agendamentos'          => 'Agendamentos',
        'cadastro'              => 'Cadastro',
        'baixar-app'            => 'Baixar App',
        'contato'               => 'Contato'
];

?>

<footer>
    <div class="bottom-footer">
        <div class="container">
            <p>
                <a href="{{ url('/') }}" title="Dr. Saúde">
                    <img src="{{ url('assets/site/images/logo_footer.png') }}" alt="Logo Dr. Saúde" />
                </a>
            </p>
            {{--<p class="copyright hidden-sm hidden-xs">--}}
                {{--<a href="https://aioria.com.br" target="_blank">--}}
                    {{--<img src="{{ url('assets/site/images/aioria.png') }}" alt="Aioria Software House">--}}
                {{--</a>--}}
            {{--</p>--}}
            <ul>
                @foreach($menu as $key => $value)
                    <li{!! Request::is($key) || Request::is($key."/*") ? ' class="this-page"' : '' !!}>
                        <a href="{{ url($key) }}" title="">{{ $value }}</a>
                    </li>
                @endforeach
            </ul>
            {{--<p class="copyright hidden-md hidden-lg">--}}
                {{--<a href="https://aioria.com.br" target="_blank">--}}
                    {{--<img src="{{ url('assets/site/images/aioria.png') }}" alt="Aioria Software House">--}}
                {{--</a>--}}
            {{--</p>--}}
        </div>

        <div style="width: 100%; background: white; padding-bottom: 10px; text-align: center; color: black;">
            <div style="width: 10%; background: url(https://aioria.com.br/wp-content/themes/aioria/images/aioria-mini-logo.png) no-repeat scroll 0 0 transparent; display: inline-block; font-size: 0; height: 40px; line-height: 0; position: relative; text-transform: capitalize; top: -4px; bottom: 4px; width: 115px;">
                <a href="https://aioria.com.br" target="blank" alt="Aioria Software House" title="Desenvolvido por Aioria Software House" style="display: block; height: 100%; position: relative; width: 100%;">aioria</a>
            </div>
        </div>
    </div>
</footer><!-- Footer -->
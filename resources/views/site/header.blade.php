<?php

$menu = [
        'medicos'               => 'Médicos',
        'encontre-um-medico'    => 'Encontre um Médico',
        'agendamentos'          => 'Agendamentos',
        'cadastro'              => 'Cadastro',
        'baixar-app'            => 'Baixar App',
        'contato'               => 'Contato'
];

$socials = [
        'facebook' => 'https://www.facebook.com/appdocsaude/',
        'linkedin' => 'https://www.linkedin.com/company/docsaude',
        'twitter' => 'https://twitter.com/docsaude',
        'instagram' => 'https://www.instagram.com/docsaude/',
        'whatsapp' =>'whatsapp://send?to=75 99221-8483'
]

?>

<div class="responsive-header">
    <div class="responsive-topbar">
        <div class="topbar-social-search center-align">
            @if(Auth::check())
                <ul class="topbar-info" >
                    <div class="valign-wrapper col s2">
                        <div class="col s1 m1 circle" style="height: 50px; margin-right: 5px; overflow: hidden; width: 50px;">
                            @if(Auth::user()->image_src)
                                <img src="{{ Auth::user()->image_src }}" alt="" width="50"  class="responsive-img" style="display: inline; margin-top: -15%;">
                            @endif
                        </div>
                        <li>Olá, <span style="font-weight: bold">{!! Auth::user()->name !!}</span></li>
                    </div>
                </ul>
            @endif
        </div>
    </div><!-- Responsive Topbar -->
    <div class="responsive-menu">
        <div class="logo">
            <a href="{{ url('/') }}" title="docsaúde">
                <img src="{{ url('assets/site/images/logo_header.png') }}" alt="Logo docsaúde" />
            </a>
        </div>
        <div class="responsive-menu-btns">

            @if(!Auth::check())
                <a class="call-popup popup1" href="#" title="">
                    <i class="fa fa-user"></i> Acesso Paciente / Cadastro
                </a>
            @else
                <a class="" href="{{ url('site/logout') }}" title="">
                    <i class="fa fa-sign-out"></i> Fazer Logout
                </a>
            @endif

            <a class="open-menu" href="#" title=""><i class="fa fa-list"></i></a>
        </div>
    </div><!-- Responsive Menu -->
    <div class="responsive-links">
        <span>
            <i class="fa fa-remove"></i>
        </span>
        <ul>
            @foreach($menu as $key => $value)
            <li>
                <a href="{{ url($key) }}" title="">{{ $value }}</a>
            </li>
            @endforeach
        </ul>
    </div><!-- Responsive Links -->
</div><!-- Responsive Header -->
<header class="stick">
    <div class="topbar style2">
        <div class="container">
            <ul class="topbar-info">
                <li><i class="icon-envelope"></i> <strong>Email:</strong>  <a href="mailto:contato@docsaude.com" class="text-white" style="color: #fff; text-decoration: underline;">contato@docsaude.com</a></li>
            </ul>
            <div class="social-icons">
                @foreach($socials as $social => $url)
                    <a class="{{ $social }}" title="{{ ucwords($social) }}" href="{{ $url }}"><i class="fa fa-{{ $social }}"></i></a>
                @endforeach
            </div>
            <div class="right hide-on-med-and-down valign">

                @if(Auth::check())
                    <ul class="topbar-info" >
                        <div class="valign-wrapper col s2">
                            <div class="col s1 m1 circle" style="height: 50px; margin-right: 5px; overflow: hidden; width: 50px;">
                                @if(Auth::user()->image_src)
                                    <img src="{{ url(Auth::user()->image_src) }}" alt="" width="50"  class="responsive-img" style="display: inline; margin-top: -15%;">
                                @endif
                            </div>
                            <li>Olá, <span style="font-weight: bold">{!! Auth::user()->name !!}</span></li>
                        </div>
                   </ul>
                @endif

            </div>
            <div class="topbar-btn ">

                    @if(!Auth::check())
                        <a class="call-popup popup1" href="#" title="">
                            <i class="fa fa-user"></i> Acesso Paciente / Cadastro
                        </a>
                    @else

                        <a class="" href="{{ url('site/logout') }}" title="">
                            <i class="fa fa-sign-out"></i> Fazer Logout
                        </a>
                    @endif
            </div>
        </div>
    </div><!-- Topbar -->
    <div class="menu-bar-height"></div>
    <div class="menu-bar">
        <div class="container">
            <div class="logo">
                <a href="{{ url('/') }}" title="docsaúde">
                    <img src="{{ url('assets/site/images/logo_header.png') }}" alt="Logo docsaúde" />
                </a>
            </div>
            <nav class="menu">
                <ul>
                    @foreach($menu as $key => $value)
                        <li{!! Request::is($key) || Request::is($key."/*") ? ' class="this-page"' : '' !!}>
                            <a href="{{ url($key) }}" title="">{{ $value }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div><!-- Menu Bar -->
</header><!-- Header -->
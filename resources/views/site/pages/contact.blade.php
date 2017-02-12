@extends('site.page')

@section('page.content')

    <section>
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col column s12 m12 l4">
                        <div class="contact-details">
                            <span>Seja bem-vindo ao</span>
                            <h4>docsaúde</h4>
                            <strong>Saúde com hora marcada</strong>
                            <p>Aproveite a experiência através do nosso site e aplicativo para marcar consultas de maneira fácil e rápida com médicos que estão bem perto de você.
                                Dúvidas e sugestões favor preencher nosso formulário abaixo. Obrigado!</p>
                        </div><!-- Contact Details -->
                    </div>
                    <div class="col column s12 m12 l8">
                        <div class="contact-boxes">
                            {{--<div class="parallax-container"><div  class="parallax"><img src="http://placehold.it/1227x757" alt="" /></div></div>--}}
                            <div class="row">
                                <div class="col s12 m4 l4">
                                    <div class="contact-box">
                                        <span><i class="fa fa-phone"></i></span>
                                        <strong>TELEFONE</strong>
                                        <p>(75) 9221-8483 <br> Savio Ribeiro ( docsaúde )</p>
                                    </div><!-- Contact Box -->
                                </div>
                                <div class="col s12 m4 l4">
                                    <div class="contact-box">
                                        <span><i class="fa fa-envelope"></i></span>
                                        <strong>EMAIL</strong>
                                        <p><span><a href="mailto:contato@docsaude.com" class="text-white" style="color: #fff; text-decoration: underline;">contato@docsaude.com</a></span><span><a
                                                        href="http://www.docsaude.com" style="color: white;">www.docsaude.com</a></span></p>
                                    </div><!-- Contact Box -->
                                </div>
                            </div>
                        </div><!-- Contact Boxes -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="block gray">
            <div class="container">
                <div class="row">
                    <div class="col column offset-l2 s12 m12 l8">
                        <div class="modern-title">
                            <h2>Dúvidas ou Sugestões? Por favor, entre em contato com o docsaúde.</h2>
                            <span>Preencha formulário abaixo.</span>
                        </div>

                        <div class="contact-form">
                            <div id="formresult">
                                @include("flash::message")
                            </div>
                            {!! Form::open(['url' => 'contato', 'id' => 'formulario_contato', 'novalidate']) !!}
                                <div class="row">
                                    <div class="input-field col s12 m12 l12">
                                        <input name="name"type="text" placeholder="Nome completo" data-error="Erro no nome"/>
                                        <span></span>
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <input name="email" type="email" placeholder="Endereço de email" />
                                        <span></span>
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <input name="subject" type="text" placeholder="Digite um assunto" />
                                        <span></span>
                                    </div>
                                    <div class="input-field col s12 m12 l12">
                                        <input name="phone" type="text"  placeholder="Digite um número de telefone" />
                                        <span></span>
                                    </div>
                                    <div class="input-field col s12 m12 l12">
                                        <textarea name="msg" placeholder="Descrição"></textarea>
                                        <span></span>
                                    </div>
                                    <div class="errors input-field col s12 m12 l12"></div>
                                    <div class="g-recaptcha" data-sitekey=""></div>
                                    <div class="input-field col s12 m12 l12">
                                        <button type="submit" class="coloured-btn">ENVIAR</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div><!-- Contact Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
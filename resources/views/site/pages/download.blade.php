@extends('site.page')

@section('page.content')
    <section>
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="refer-text">
                            <div class="traditional-title">
                                <h3>BAIXE AGORA MESMO NOSSOS APLICATIVOS NAS LOJAS <i>APPLE STORE</i> E <i>GOOGLE PLAY</i></h3>
                            </div>
                            <p>Usuários de smartphones e tablets podem baixar o aplicativo por aqui! Faça agora mesmo o seu download e aproveite o app docsaúde!</p>
                            <br><br><br><br><br><br><br><br><br><br><br>
                        </div><!-- Refer Text -->
                    </div>
                    <div class="col s12 m6 l6">
                        <div class="contact-boxes">
                            <div class="parallax-container"><div class="parallax"><img src="" alt="" style="display: block; transform: translate3d(-50%, 212px, 0px);"></div></div>
                            <div class="row">
                                <div class="col s12 m6 l6">
                                    <div class="contact-box" style="background: url('{{ asset('assets/site/images/img_apple_download.png') }}'); background-position: center; -webkit-background-size: ;background-size: 100%;">
                                        <span><img  style="border-radius: 3px;" src="{{ asset('assets/site/images/icon_googleplay.png') }}" alt=""></span>
                                        <strong>Google Play</strong>
                                        <p>
                                            <a href="" class="coloured-btn">DOWNLOAD</a>
                                        </p>
                                    </div>
                                </div>

                                <div class="col s12 m6 l6">
                                    <div class="contact-box" style="background: url('{{ asset('assets/site/images/img_google_download.png') }}'); background-position: center; -webkit-background-size: ;background-size: 100%;">
                                        <span><img  style="border-radius: 3px;" src="{{ asset('assets/site/images/icon_apple.png') }}" alt=""></span>
                                        <strong>Apple Store</strong>
                                        <p>
                                            <a href="" class="coloured-btn">DOWNLOAD</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Contact Boxes -->
                    </div>
                </div>
            </div>
        </div>
    </section>


@stop
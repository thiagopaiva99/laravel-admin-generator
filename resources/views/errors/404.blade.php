@extends('site.page')

@section('page.content')

    <section class="block">
        <div class="container">
            <div class="row">
                <div class="col s12 m12">
                    <div class="modern-title">
                        <h2 class="">Página <span>não encontrada</span></h2>
                    </div>
                    <div id="conteudo">
                        <div class="information" style="text-align: center">
                            <strong>
                                A página que você solicitou não existe.
                                <br>
                                Use os botões de navegação para acessar as páginas do <span>DocSaúde</span>.
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include("site.pages.searchform")

@stop
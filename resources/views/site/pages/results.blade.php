@extends('site.page')

@section('page.content')

    @if(sizeof($physicians_php) > 0)
        @include('site.pages.physicianslist')
    @else

        <section>
            <div class="block">
                <div class="container">
                    <div class="row">
                        <div class="col column s12 m12 l12">
                            <div class="modern-title">
                                <h2>
                                    Não foram encontrados médicos para sua pesquisa.
                                    <br>
                                    Tente refinar sua busca utilizando ferramentas avançadas!
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif

    @include('site.pages.searchform')

@stop
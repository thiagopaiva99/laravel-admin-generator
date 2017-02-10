<section>
    <div class="block gray">
        <div class="container">
            <div class="row">
                <div class="col column s12 m12 l8 offset-l2">
                    <div class="modern-title">
                        <h2>Faça uma pesquisa avançada para marcar sua consulta com o médico que deseja.</h2>
                    </div>

                    <div id="toggle" class="toggle fancy">
                        <div class="toggle-item">
                            <h3><i class="icon-hand"></i><span>INFORMAÇÕES DE PESQUISA</span></h3>
                            <div class="content" {!! isset($old) ? 'style="display: none;"' : "" !!}>

                            {{ Form::open(['class' => 'search']) }}

                                <!-- Pesquisar por -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('term', 'Pesquisar por:') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::text('term', isset($old['term']) ? $old['term'] : null, ['placeholder' => 'Nome do médico, ou especialidade, ou cidade...']) !!}
                                </div>

                                <button class="coloured-btn">ENCONTRAR</button>

                                {{ Form::close() }}

                            </div>
                        </div>
                        <div class="toggle-item">
                            <h3><i class="icon-hand"></i><span>PESQUISA AVANÇADA</span></h3>
                            <div class="content" {!! isset($old) ? 'style="display: none;"' : "" !!}>

                            {{ Form::open(['class' => 'search']) }}

                                <!-- Nome do médico -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('term', 'Nome do médico:') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::text('term', isset($old['term']) ? $old['term'] : null) !!}
                                </div>

                                <!-- Data mínima de consulta -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('min_date', 'Data minima:') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::text('min_date', isset($old['min_date']) ? $old['min_date'] : null, ['class' => 'datepicker']) !!}
                                </div>

                                <!-- Data máxima de consulta -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('max_date', 'Data máxima:') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::text('max_date', isset($old['max_date']) ? $old['max_date'] : null, ['class' => 'datepicker']) !!}
                                </div>

                                <!-- Distância máxima -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('distance', 'Distância máxima (KM):') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::number('distance', isset($old['distance']) ? $old['distance'] : null) !!}
                                </div>

                                <!-- Valor máximo da consulta -->

                                <div class="input-field simple-label col s12 m12 l3">
                                    {!! Form::label('max_amount', 'Valor máximo da consulta:') !!}
                                </div>
                                <div class="input-field simple-label col s12 m12 l9">
                                    {!! Form::number('max_amount', isset($old['max_amount']) ? $old['max_amount'] : null) !!}
                                </div>

                                @if(isset($search_fields))
                                    @foreach($search_fields as $select => $options)

                                        <div class="input-field simple-label col s12 m12 l3">
                                            {!! Form::label($select, $labels[$select]) !!}
                                        </div>
                                        <div class="input-field simple-label col s12 m12 l9">
                                            {!! Form::select($select, $options, isset($old[$select]) ? $old[$select] : null, ['placeholder' => 'Selecione']) !!}
                                        </div>

                                    @endforeach
                                @endif

                                <button class="coloured-btn">ENCONTRAR</button>

                                {{ Form::close() }}

                            </div>
                        </div>
                    </div><!-- Toggles -->

                </div>
            </div>
        </div>
    </div>
</section>
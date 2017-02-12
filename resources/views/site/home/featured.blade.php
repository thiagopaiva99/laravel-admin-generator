<section>
<section>
    <div class="block no-padding">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="creative-slider">
                    <div id="rev_slider_4_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="classicslider1">
                        <div id="rev_slider_4_1" class="rev_slider fullwidthabanner" style="display:none;">
                            <ul>
                                <li data-index="rs-1" data-transition="fade" data-slotamount="7"  data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000" data-title="Slide 1">
                                    <!-- MAIN IMAGE -->
                                    <img src="{{ url('assets/site/images/img_banner.png') }}"  alt=""  data-bgposition="center center"  data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg" data-no-retina>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="appointment-form">
                        <div class="simple-title">
                            <h4>Bem-vindo ao docsaúde</h4>
                            <span>Marque sua consulta</span>
                        </div>
                        {!! Form::open(['url' => 'encontre-um-medico']) !!}
                            <div class="row">

                                @if(isset($search_fields))
                                    @foreach($search_fields as $select => $options)

                                        <div class="input-field col s12">
                                            {!! Form::select($select, $options, null, ['placeholder' => $labels[$select]]) !!}
                                        </div>

                                    @endforeach
                                @endif

                                <div class="input-field simple-label col s12 l6 m6">
                                    {!! Form::label("min_date", "DATA MÍNIMA") !!}
                                </div>
                                <div class="input-field date-icon col s12 l6 m6">
                                    {!! Form::date("min_date", null, ['class' => "datepicker min-date", 'placeholder' => "Escolha data"]) !!}
                                </div>
                                <div class="input-field simple-label col s12 l6 m6">
                                    {!! Form::label("max_date", "DATA MÁXIMA") !!}
                                </div>
                                <div class="input-field date-icon col s12 l6 m6">
                                    {!! Form::date("max_date", null, ['class' => "datepicker max-date", 'placeholder' => "Escolha data"]) !!}
                                </div>
                                <div class="col s12">
                                    <p></p>
                                </div>
                                <div class="input-field col s12">
                                    <button type="submit">Consultar</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div><!-- Appointment Form -->
                </div><!-- Creative Slider  -->
            </div>
        </div>
    </div>
</section>
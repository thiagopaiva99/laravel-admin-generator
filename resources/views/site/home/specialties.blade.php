<section>
    <div class="block grayish">
        <div class="container">
            <div class="row">
                <div class="col l6 m12 s12 column">
                    <div class="doctors-intro overlap">
                        <div class="doctors-img">
                            <img src="{{ url('assets/site/images/img_medicoscadastrar.png') }}" alt="" />
                        </div>
                        <div class="doctor-detail">
                            <div class="doctor-description">
                                <h4><strong>Profissional, </strong><br> faça seu cadastro!</h4>
                                <p>Se você é um profissional da área da saúde, faça seu cadastro e aumente o número de novos pacientes através de um agendamento online 24h!</p>
                                @if(!Auth::check())
                                    <span class="call-popup register-dr"><a href="#" class="coloured-btn" title="">CADASTRAR</a></span>
                                @endif
                            </div>
                        </div>
                    </div><!-- Doctors Intro -->
                </div>

                @if($physicians)
                    <?php $i = 0; ?>

                    <div class="col l6 m12 s12 column">
                        <div class="classic-title">
                            <h2><span>Procure pelas</span>Especialidades</h2>
                        </div>

                        <div class="{{ sizeof($physicians) > 4 ? "staff-carousel" : "staff-mosaic"}}">
                            <div class="staff-slide">
                                <div class="row">

                                    @foreach($physicians as $physician)
                                        <?php $i++; ?>

                                        <div class="col l6 m6 s6">
                                            <div class="staff-member">
                                                <div class="member-img">
                                                    <img src="{{ $physician->image_url }}" alt="" width="100%" height="auto" />
                                                </div>
                                                <div class="doctor-intro">
                                                    <strong>
                                                        <a class="{{ \Auth::check() ? "" : "call-popup popup1" }}" href="{{ Auth::check() ? url('medicos/'.$physician->id) : '#' }}" title="{{ $physician->title }}">
                                                            {{ $physician->title }}
                                                        </a>
                                                    </strong>
                                                    <i>{{ $physician->subtitle }}</i>
                                                </div>
                                            </div><!-- Staff Member -->
                                        </div>

                                        @if(($i % 4) == 0 && $i < sizeof($physicians))

                                                </div>
                                            </div><!-- Staff Slide -->
                                            <div class="staff-slide">
                                                <div class="row">

                                        @endif
                                    @endforeach

                                </div>
                            </div><!-- Staff Slide -->
                        </div><!-- Staff Carousel -->
                    </div>

                @endif

            </div>
        </div>
    </div>
</section>
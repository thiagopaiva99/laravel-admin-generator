<section>
    <div class="block">
        <div class="container">
            <div class="row">
                <div class="col column s12 m12 l12">
                    <div class="doctors-timetable" id="header_days">
                        <div class="valign" style="padding: 5px 0px;">
                            <button class="waves-effect waves-light btn btn-previous-week" style="background-color: #1BAB7D;"><i class="fa fa-arrow-circle-left"></i> <span class="hidden-sm">Semana</span> Anterior</button>
                            <button class="waves-effect waves-light btn btn-next-week" style="float: right; background: #1BAB7D;">Próxima <span class="hidden-sm">Semana</span> <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                        <ul class="tabs">

                            @foreach($week as $id => $day)

                                <?php $idd = explode('-', $id); ?>

                                <li class="tab"  style="min-width: 14.28%;">
                                    <?php $active_ex = (isset($active) && !is_null($active) && $active != "") ? explode("-", $active) : $idd[0].$idd[1]; ?>

                                    <a class="{{ $idd[0].$idd[1] == $active_ex[0].$active_ex[1] ? "active" : "" }}"  style="width: 100%;" href="#{{ $idd[0].$idd[1] }}">
                                        {{ $day }}<br>
                                        <span class="span-date" style="display: block;">{{ $idd[1][0].$idd[1][1].'/'.$idd[1][2].$idd[1][3] }}</span>
                                    </a>
                                </li>

                            @endforeach

                            <div class="padding-box"></div>
                        </ul>
                        <div class="timetable-tab-content">
                            <div id="week" style="">
                                <div class="staff-timetable">
                                    <div class="row">
                                        <div id="div_days">

                                            @if(isset($physicians_php) && count($physicians_php) > 0)
                                                @foreach($physicians_php as $physician)

                                                    <div class="col s12 m12 l6">
                                                        <div class="doc-time">
                                                            <img src="assets/site/images/img_medicos_2.png" alt="" />
                                                            <div class="doc-detail">
                                                                <h4>{{ $physician->title }}</h4>
                                                                <ul>
                                                                    <li>{{ $physician->subtitle }}</li>
                                                                    <li>Próximo Horário: {!! str_replace(":", "h", str_replace(":00", "h", $physician->time_str)) !!}</li>
                                                                    <li>Distância: {!! $physician->distance_str !!}</li>
                                                                    <li>Valor: {!! ($physician->amount_str == null) ? 'A combinar' : $physician->amount_str !!}</li>
                                                                </ul>
                                                                <a class="{{ \Auth::check() ? "dark-btn" : "call-popup popup1" }}" href="{{ url('medicos/'.$physician->id) }}" title="">MARCAR</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="row"><div class="col s12 m12 l12 center-align"><div id="pagi"></div></div></div>
                                    </div>
                                </div><!-- Staff Timetable -->
                            </div><!-- Day -->
                        </div>
                    </div><!-- Timetable -->
                </div>
            </div>
        </div><!-- Container -->
    </div>
</section>
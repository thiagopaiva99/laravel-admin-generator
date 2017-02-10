@if(Auth::check())
    @if(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
        @if(Auth::user()->clinicLocals()->count() > 0)
            <li class="{{ Request::is('admin/clinic') ? 'active' : '' }}"><a href="{{ url('admin/clinic') }}"><i class="fa fa-calendar-o"></i> <span>Agenda</span></a></li>
            <li class="{{ Request::is('admin/users') ? 'active' : '' }}"><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i> <span>Profissionais</span></a></li>
            <!-- <li class="{{ Request::is('admin/users-without-clinic') ? 'active' : '' }}"><a href="{{ url('admin/users-without-clinic') }}"><i class="fa fa-users"></i> <span>Médicos sem clínica</span></a></li> -->
            <li class="{{ Request::is('admin/locals') ? 'active' : '' }}"><a href="{{ url('admin/locals') }}"><i class="fa fa-map-marker"></i> <span>Salões de Beleza</span></a></li>
            <li class="{{ Request::is('admin/appointments-list') ? 'active' : '' }}"><a href="{{ url('admin/appointments-list') }}"><i class="fa fa-calendar"></i> <span>Agendamentos</span></a></li>
            <!-- <li class="{{ Request::is('admin/report') ? 'active' : '' }}"><a href="{{ url('admin/relatorios') }}"><i class="fa fa-newspaper-o"></i> <span>Relatório</span></a></li> -->
        @else
            <li><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i> <span>Profissionais</span></a></li>
        @endif
    @else
        @if(\Auth::user()->locals->pluck("name","id")->count() < 1 && Auth::user()->user_type != \App\Models\User::UserTypeAdmin)
            @if(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
                <li><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i> <span>Profissionais</span></a></li>
            @endif
        @else
            @if(Auth::user()->user_type == \App\Models\User::UserTypeAdmin)
                <li class="{{ Request::is('admin/users') ? 'active' : '' }}"><a href="{{ url('admin/users') }}" ><i class="fa fa-users"></i> <span>Usuários</span></a></li>
                <!-- <li class="{{ Request::is('admin/healthPlans') ? 'active' : '' }}"><a href="{{ url('admin/healthPlans') }}"><i class="fa fa-credit-card"></i> <span>Planos de Saúde</span></a></li> -->
                <li class="{{ Request::is('admin/specializations') ? 'active' : '' }}"><a href="{{ url('admin/specializations') }}"><i class="fa fa-medkit"></i> <span>Especialidades</span></a></li>
                <!-- <li class="{{ Request::is('admin/exams') ? 'active' : '' }}"><a href="{{ url('admin/exams') }}"><i class="fa fa-stethoscope"></i> <span>Exames</span></a></li> -->
                <!-- <li class="{{ Request::is('admin/report') ? 'active' : '' }}"><a href="{{ url('admin/relatorios') }}"><i class="fa fa-newspaper-o"></i> <span>Relatório</span></a></li> -->
            @elseif(Auth::user()->user_type == \App\Models\User::UserTypeDoctor)
                <li class="{{ Request::is('admin/calendar') ? 'active' : '' }}"><a href="{{ url('admin/calendar') }}"><i class="fa fa-book"></i> <span>Agenda</span></a></li>
                <li class="{{ Request::is('admin/users') ? 'active' : '' }}"><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i> <span>Pacientes</span></a></li>
                <li class="{{ Request::is('admin/locals') ? 'active' : '' }}"><a href="{{ url('admin/locals') }}"><i class="fa fa-map-marker"></i> <span>Locais de Atendimento</span></a></li>
            @endif
        @endif
    @endif

    <li><a href="{{ url('site/logout') }}"><i class="fa fa-sign-out"></i> <span>Sair</span></a></li>
@endif

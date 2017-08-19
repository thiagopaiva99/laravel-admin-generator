@if(Auth::check())
<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" id="menu">
            @if( getenv("SIDEBAR_USER") != "" )
                <li class="header">INFORMAÇÕES</li>
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ Auth::user()->name }}</p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
            @endif
            @if( getenv("SIDEBAR_SEARCH") != "" )
                <div class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" autocomplete="off" class="form-control" placeholder="Procure um menu aqui...">
                        <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                  </span>
                    </div>
                </div>
            @endif
            <li class="header">MENUS DE NAVEGAÇÃO</li>
            {{--@include('layouts.menu')--}}
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
@endif

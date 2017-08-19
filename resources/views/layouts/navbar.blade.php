<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- Logo Mini -->
        <span class="logo-mini">
                    {{ getenv("MINI_TITLE") }}
                </span>
        <!-- Logo Lg -->
        <span class="logo-lg">
                    <b>{{ getenv("TITLE") }}</b>
                </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation" style="background: #571c36">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="Imagem do usuário"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header" style="background: #571c36;">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="Imagem do usuário"/>
                            <p>
                                {!! Auth::user()->name !!}
                                <small>Membro desde {!! Auth::user()->created_at->format('d/m/Y') !!}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route("admin.users.show", [Auth::id()]) }}" class="btn btn-default btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
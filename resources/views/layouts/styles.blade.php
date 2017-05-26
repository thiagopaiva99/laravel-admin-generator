<style>
    .navbar{
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .logo{
        background: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .user-header{
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .box{
        border-top-color: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .nav-tabs-custom > .nav-tabs > li.active{
        border-top-color: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .sidebar-toggle:hover{
        background-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .btn-primary{
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .btn-danger{
        background-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
        border-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .btn-primary:hover{
        background-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .btn-danger:hover{
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .btn-success{
        border-color: {{ getenv("COLOR_PRIMARY") }} !important;
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .btn-success:hover{
        border-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
        background: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{
        background: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
        border-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .skin-red .sidebar-menu > li.active > a{
        border-left-color: {{ getenv("COLOR_PRIMARY_DARK") }} !important;
    }

    .skin-red .sidebar-menu > li:hover > a{
        border-left-color: {{ getenv("COLOR_PRIMARY") }} !important;
    }

    .dropdown-menu > .active > a, .dropdown-menu > .active > a:focus, .dropdown-menu > .active > a:hover{
        background: {{ getenv("COLOR_PRIMARY") }} !important;
    }
</style>
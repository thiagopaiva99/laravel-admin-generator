<div class="col-md-3">

    <!-- Profile Image -->
    <div class="box box-primary">
        <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg" alt="User profile picture">

            <h3 class="profile-username text-center">{!! $user->name !!}</h3>

            <p class="text-muted text-center">{!! $user->email !!}</p>

            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>Cidade</b> <a class="pull-right">{!! ucwords($user->address) !!}</a>
                </li>
                <li class="list-group-item">
                    <b>Telefone</b> <a class="pull-right">{!! $user->phone !!}</a>
                </li>
                <li class="list-group-item">
                    <b>Usuario</b> <a class="pull-right">{!! $user->getUserTypeName($user->user_type) !!}</a>
                </li>
                @if( $user->facebook_id != "" )
                    <li class="list-group-item">
                        <b>Facebook</b> <a class="pull-right" target="_blank" href="https://facebook.com/{!! $user->facebook_id !!}">Perfil</a>
                    </li>
                @endif
            </ul>

            @if( $user->id == Auth::user()->id )
                <a href="{!! url('/admin/users/'.$user->id.'/edit') !!}" class="btn btn-info btn-block"><b>Editar</b></a>
            @endif
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <!-- About Me Box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

            <p class="text-muted">
                B.S. in Computer Science from the University of Tennessee at Knoxville
            </p>

            <hr>

            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

            <p class="text-muted">Malibu, California</p>

            <hr>

            <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

            <p>
                <span class="label label-danger">UI Design</span>
                <span class="label label-success">Coding</span>
                <span class="label label-info">Javascript</span>
                <span class="label label-warning">PHP</span>
                <span class="label label-primary">Node.js</span>
            </p>

            <hr>

            <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
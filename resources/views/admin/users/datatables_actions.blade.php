@if(Auth::user()->user_type == \App\Models\User::UserTypeAdmin || Auth::user()->user_type == \App\Models\User::UserTypeClinic)
    @if(Request::is('admin/users-without-clinic'))
        {!! Form::open(['route' => ['admin.users.destroy', $id], 'method' => 'delete']) !!}
        <div class='btn-group'>
            @if(Auth::user()->user_type == 4)
                <a href="{{ url('admin/users/'.$id.'/doctor') }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Ver detalhes">
                    <i class="fa fa-eye"></i>
                </a>
            @else
                <a href="{{ route('admin.users.show', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Ver detalhes">
                    <i class="fa fa-eye"></i>
                </a>
            @endif
            <a href="{{ url('admin/add-doctor-to-clinic/'.$id) }}" class='btn btn-bitbucket btn-xs' data-toggle="tooltip" data-placement="top" title="Adicionar a clinica">
                <i class="fa fa-plus"></i>
            </a>
        </div>
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['admin.users.destroy', $id], 'method' => 'delete']) !!}
        <div class='btn-group'>
            @if(Auth::user()->user_type == 4)
                <a href="{{ url('admin/users/'.$id.'/doctor') }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Ver detalhes">
                    <i class="fa fa-eye"></i>
                </a>
            @else
                <a href="{{ route('admin.users.show', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Ver detalhes">
                    <i class="fa fa-eye"></i>
                </a>
            @endif
            <a href="{{ route('admin.users.edit', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Editar">
                <i class="fa fa-edit"></i>
            </a>

            @if(Auth::user()->user_type == \App\Models\User::UserTypeClinic)
                <a href="{{ url('admin/locals/create?user_id='.$id) }}" target="_blank" class="btn btn-xs btn-bitbucket" data-toggle="tooltip" data-placement="top" title="Novo local"><i class="fa fa-home"></i></a>
            @endif

            {!! Form::button('<i class="fa fa-trash"></i>', [
                'type' => 'submit',
                'class' => 'btn btn-danger btn-xs confirmation',
                'onclick' => 'return confirm($(this))',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Deletar'
            ]) !!}
            {{--<a href="{{ route('admin.users.destroy', $id) }}"><i class="fa fa-trash"></i></a>--}}
        </div>
        {!! Form::close() !!}
    @endif
@else
    <div class='btn-group'>
        <a href="{{ route('admin.users.show', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Detalhes">
            <i class="fa fa-eye"></i>
        </a>
    </div>
@endif
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
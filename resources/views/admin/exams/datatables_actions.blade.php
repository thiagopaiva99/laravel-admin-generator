{!! Form::open(['route' => ['admin.exams.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('admin.exams.show', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Ver detalhes">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('admin.exams.edit', $id) }}" class='btn btn-default btn-xs' data-toggle="tooltip" data-placement="top" title="Editar">
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
</div>
{!! Form::close() !!}

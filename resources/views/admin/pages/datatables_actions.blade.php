{!! Form::open(['route' => ['admin.pages.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group text-center'>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirmation('pages', $id);"
    ]) !!}
</div>
{!! Form::close() !!}

{!! $dataTable->table(['width' => '100%']) !!}

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.15/i18n/Portuguese-Brasil.json"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection
{!! $dataTable->table(['width' => '100%']) !!}

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}

    <script>
        $(function(){
            setTimeout(function(){
                //apenas algumas alterações no css do datatables, ja que o padrão deles foge um pouco do nosso :D
                $("table").addClass("table-striped table-hover table-responsive");
                $(".dt-button").removeClass("dt-button").addClass("btn btn-default").css({"margin-right" : "5px"});
                $("th").on('mouseover', function(){
                    $(this).css({
                        "cursor" : "pointer",
                        "outline" : "none"
                    })
                });
                $("#dataTableBuilder_filter > label > input").removeClass("input-sm").attr({"placeholder" : "Faça sua pesquisa!"});
                $(".dataTables_paginate").find("ul.pagination").find("li.paginate_button").addClass("bg-red");
            }, 500);
        });
    </script>
@endsection
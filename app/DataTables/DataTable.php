<?php
/**
 * Created by PhpStorm.
 * User: gbtagliari
 * Date: 01/09/16
 * Time: 19:01
 */

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable as DT;

abstract class DataTable extends DT {

    protected $languageOptions = [
        "sEmptyTable" => "Nenhum registro encontrado",
        "sInfo" => "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty" => "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered" => "(Filtrados de _MAX_ registros)",
        "sInfoPostFix" => "",
        "sInfoThousands"=> ".",
        "sLengthMenu"=> "_MENU_ resultados por página",
        "sLoadingRecords"=> "Carregando...",
        "sProcessing"=> "Processando...",
        "sZeroRecords"=> "Nenhum registro encontrado",
        "sSearch"=> "",
        "oPaginate"=> [
            "sNext"=> "Próximo",
            "sPrevious"=> "Anterior",
            "sFirst"=> "Primeiro",
            "sLast"=> "Último"
        ],
        "oAria"=> [
            "sSortAscending"=> ": Ordenar colunas de forma ascendente",
            "sSortDescending"=> ": Ordenar colunas de forma descendente"
        ],
        "buttons" => [
            "print" => "Imprimir",
            "create" => "Novo",
            "reload" => "Recarregar",
            "export" => "Exportar"
        ]
    ];

}
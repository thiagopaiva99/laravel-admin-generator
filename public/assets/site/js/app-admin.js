// ES6 - FUNCIONAL
const init = () => {
    // all selects with the select picker plugin
    $('select') .selectpicker();

    // all forms with the validator plugin
    $("form")   .validator();

    // all the input files with the default filestyle plugin
    $(":file")  .filestyle('buttonText', 'Procurar Imagem')
                .filestyle('icon', false)
                .filestyle('buttonBefore', 'false');

    // masks to many input types
    $('input[type=datetime]').mask('00-00-0000 00:00:00-0');

    // error type for datatables
    $.fn.dataTable.ext.errMode = 'throw';

    // custom configuration for datatables
    $("#dataTableBuilder_processing")
        .html("<i class='fa fa-spinner fa-spin'></i> Aguarde, processando informação! Se demorar demais, <a style='color: #571C36 !important;' class='btn-refresh-page' style='cursor: pointer'>clique aqui</a> para recarregar a página")
        .css({
            "padding" : "10px",
            "background" : "#eee",
            "margin-top" : "5px"
        });

    //apenas algumas alterações no css do datatables, ja que o padrão deles foge um pouco do nosso :D
    $("table")
        .addClass("table-striped table-hover table-responsive");

    $(".dt-button")
        .removeClass("dt-button")
        .addClass("btn btn-default")
        .css({"margin-right" : "5px"});

    $("th")
        .on('mouseover', () => {
            $(this).css({
                "cursor" : "pointer",
                "outline" : "none"
            });
        });

    $("#dataTableBuilder_filter")
        .find("label")
        .find("input")
        .removeClass("input-sm")
        .attr({"placeholder" : "Faça sua pesquisa!"});

    $(".dataTables_paginate")
        .find("ul.pagination")
        .find("li.paginate_button")
        .addClass("bg-red");

    $("#sortable")
        .sortable()
        .disableSelection();
};

const date_formatted = (date) => {
    let year    = date.getYear() + 1900;

    let month   = (1 + date.getMonth()).toString();
    month       = month.length > 1 ? month : '0' + month;

    let day     = date.getDate().toString();
    day         = day.length > 1 ? day : '0' + day;

    let hours   = date.getHours();
    hours       = hours.length > 1 ? hours : '0' + hours;

    let minutes = date.getMinutes().toString();
    minutes     = minutes.length > 1 ? minutes : '0' + minutes;

    return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':00';
};

const confirmation = (page, id) => {
    swal({
            title: "Tem certeza?",
            text: "Essa ação não pode ser desfeita!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim!",
            cancelButtonText: "Não!",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: false,
        },
        (isConfirm) => {
            if (isConfirm) {
                $.ajax({
                   url: URL() + 'admin/' + page + '/' + id,
                   type: 'DELETE',
                   data: {
                       _method: "DELETE",
                       _token: "IvzRD8Tx4NayjumiI2cea9nIEV0w3XY7NcXSULxZ"
                   },
                   success: (res) => {
                       if( res ){
                           swal({
                               title: "Sucesso!",
                               text: "Item deletado",
                               showConfirmButton: false,
                               type: "success"
                           });
                           setTimeout(() => location.reload(), 1000);
                       }
                   },
                   error: () => {
                       swal("Oops!", "Ocoreu algum erro :/", "error");
                   }
                });
            } else {
            }
        });
    return false;
};

const URL = () => window.location.protocol + '//' + window.location.host + '/';

$(document).ready(() => {

});
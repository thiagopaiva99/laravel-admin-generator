// ES6 - FUNCIONAL
const init = () => {
    // all selects with the select picker plugin
    $('select') .selectpicker();

    // all forms with the validator plugin
    $("[data-toggle=validator]")   .validator();

    // all the input files with the default filestyle plugin
    // $(":file")  .filestyle('buttonText', 'Procurar Imagem')
    //     .filestyle('icon', false)
    //     .filestyle('buttonBefore', 'false');

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

    // custom css for datatables button
    $(".dt-button")
        .removeClass("dt-button")
        .addClass("btn btn-default")
        .css({"margin-right" : "5px"});

    // custom css for tables th
    $("th")
        .on('mouseover', () => {
            $(this).css({
                "cursor" : "pointer",
                "outline" : "none"
            });
        });

    // custom css for datatables filter
    $("#dataTableBuilder_filter")
        .find("label")
        .find("input")
        .removeClass("input-sm")
        .attr({"placeholder" : "Faça sua pesquisa!"});

    // custom css for datatables paginate
    $(".dataTables_paginate")
        .find("ul.pagination")
        .find("li.paginate_button")
        .addClass("bg-red");

    // setting the jquery ui sortbalke configurations
    $("#sortable")
        .sortable()
        .disableSelection();
};

// a function to format dates
const date_formatted = (date) => {
    // mounting the year
    let year    = date.getYear() + 1900;

    // mounting the month
    let month   = (1 + date.getMonth()).toString();
    month       = month.length > 1 ? month : '0' + month;

    // mounting the day
    let day     = date.getDate().toString();
    day         = day.length > 1 ? day : '0' + day;

    // mounting the hours
    let hours   = date.getHours();
    hours       = hours.length > 1 ? hours : '0' + hours;

    // mounting the minutes
    let minutes = date.getMinutes().toString();
    minutes     = minutes.length > 1 ? minutes : '0' + minutes;

    // returing the string
    return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':00';
};

// confirmation for exclusions btns
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
            }
        });
    return false;
};

// creating a function that return the url
const URL = () => window.location.protocol + '//' + window.location.host + '/';

$(document).ready(() => {
    // Initiating some functions
    init();

    // Searching in the sidebar
    const q     = $("input[name=q]");
    const menu  = $("#menu");
    q.on('keyup', () => {
       const val = q.val();
       menu
           .children("li")
           .children("a")
           .children("span")
           .each(function(){
               const currentLiText = $(this).text();
               const showCurrentLi = currentLiText.indexOf(val) !== -1;
               $(this).parent().toggle(showCurrentLi);
           });
    });

    // Searching in the options list
    const o       = $("input[name=o]");
    const options = $("#options_list");
    o.on('keyup', () => {
        const val = o.val();
        options
            .children("li")
            .children("strong")
            .each(function(){
                const currentLiText = $(this).text();
                const showCurrentLi = currentLiText.indexOf(val) !== -1;
                $(this).parent().toggle(showCurrentLi);
            });
    });

    // Showing/Hiding the options btn in new option create
    const btnotpions = $(".btn-options");
    btnotpions.on('click', () => {
        const divoptions = $("#options");
        divoptions.toggle();
    });

    // Getting the table attributes
    const tablesselect = $("select[name=table]");
    tablesselect.on('change', () => {
        $.ajax({
            url: URL() + 'admin/api/get-attributes',
            type: 'GET',
            data: {
                _token: "IvzRD8Tx4NayjumiI2cea9nIEV0w3XY7NcXSULxZ",
                table: tablesselect.val()
            },
            success: (res) => {
                if( res ){
                    const attributes = $("#attributes");
                    attributes.html("<hr>");

                    res.forEach((item) => {
                       const html = `
                            <div class="checkbox checkbox-success checkbox-inline" style="margin-left: 5px;">
                                <input type="checkbox" name="fields[]" id="check_` + item.name + `" value="` + item.name + `">
                                <label for="check_` + item.name + `"> ` + item.name + `</label>
                            </div>
                        `;
                       attributes.append(html);
                    });

                    const buttonAPI = $(".btn-generate-api");
                    buttonAPI.removeAttr("disabled");
                }
            },
            error: () => {
                swal("Oops!", "Ocoreu algum erro :/", "error");
            }
        });
    });

    // Generating API
    const buttonAPI = $(".btn-generate-api");
    buttonAPI.on('click', () => {
        const fields = [];
        const checkboxes = $("input[type=checkbox]:checked").each(function(){
            fields.push($(this).val());
        });

        $.ajax({
            url: URL() + 'admin/api/generate',
            type: 'GET',
            data: {
                _token: "IvzRD8Tx4NayjumiI2cea9nIEV0w3XY7NcXSULxZ",
                table:  $("select[name=table]").val(),
                type:   $("select[name=type]").val(),
                fields: fields
            },
            success: (res) => {
                if( res ){
                    const modal = $("#myModal");
                    modal.modal('toggle');

                    const modalBody = $(".modal-body");
                    modalBody.text(res);
                }
            },
            error: () => {
                swal("Oops!", "Ocoreu algum erro :/", "error");
            }
        });
    });
});
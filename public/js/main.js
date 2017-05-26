(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

// ES6 - FUNCIONAL
var init = function init() {
    // all selects with the select picker plugin
    $('select').selectpicker();

    // all forms with the validator plugin
    $("[data-toggle=validator]").validator();

    // all the input files with the default filestyle plugin
    // $(":file")  .filestyle('buttonText', 'Procurar Imagem')
    //     .filestyle('icon', false)
    //     .filestyle('buttonBefore', 'false');

    // masks to many input types
    $('input[type=datetime]').mask('00-00-0000 00:00:00-0');

    // error type for datatables
    $.fn.dataTable.ext.errMode = 'throw';

    // custom configuration for datatables
    $("#dataTableBuilder_processing").html("<i class='fa fa-spinner fa-spin'></i> Aguarde, processando informação! Se demorar demais, <a style='color: #571C36 !important;' class='btn-refresh-page' style='cursor: pointer'>clique aqui</a> para recarregar a página").css({
        "padding": "10px",
        "background": "#eee",
        "margin-top": "5px"
    });

    //apenas algumas alterações no css do datatables, ja que o padrão deles foge um pouco do nosso :D
    $("table").addClass("table-striped table-hover table-responsive");

    // custom css for datatables button
    $(".dt-button").removeClass("dt-button").addClass("btn btn-default").css({ "margin-right": "5px" });

    // custom css for tables th
    $("th").on('mouseover', function () {
        $(undefined).css({
            "cursor": "pointer",
            "outline": "none"
        });
    });

    // custom css for datatables filter
    $("#dataTableBuilder_filter").find("label").find("input").removeClass("input-sm").attr({ "placeholder": "Faça sua pesquisa!" });

    // custom css for datatables paginate
    $(".dataTables_paginate").find("ul.pagination").find("li.paginate_button").addClass("bg-red");

    // setting the jquery ui sortbalke configurations
    $("#sortable").sortable().disableSelection();
};

// a function to format dates
var date_formatted = function date_formatted(date) {
    // mounting the year
    var year = date.getYear() + 1900;

    // mounting the month
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    // mounting the day
    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    // mounting the hours
    var hours = date.getHours();
    hours = hours.length > 1 ? hours : '0' + hours;

    // mounting the minutes
    var minutes = date.getMinutes().toString();
    minutes = minutes.length > 1 ? minutes : '0' + minutes;

    // returing the string
    return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':00';
};

// confirmation for exclusions btns
var confirmation = function confirmation(page, id) {
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
        showLoaderOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: URL() + 'admin/' + page + '/' + id,
                type: 'DELETE',
                data: {
                    _method: "DELETE",
                    _token: "IvzRD8Tx4NayjumiI2cea9nIEV0w3XY7NcXSULxZ"
                },
                success: function success(res) {
                    if (res) {
                        swal({
                            title: "Sucesso!",
                            text: "Item deletado",
                            showConfirmButton: false,
                            type: "success"
                        });
                        setTimeout(function () {
                            return location.reload();
                        }, 1000);
                    }
                },
                error: function error() {
                    swal("Oops!", "Ocoreu algum erro :/", "error");
                }
            });
        }
    });
    return false;
};

// creating a function that return the url
var URL = function URL() {
    return window.location.protocol + '//' + window.location.host + '/';
};

$(document).ready(function () {
    // Initiating some functions
    init();

    // Searching in the sidebar
    var q = $("input[name=q]");
    var menu = $("#menu");
    q.on('keyup', function () {
        var val = q.val();
        menu.children("li").children("a").children("span").each(function () {
            var currentLiText = $(this).text();
            var showCurrentLi = currentLiText.indexOf(val) !== -1;
            $(this).parent().toggle(showCurrentLi);
        });
    });

    // Searching in the options list
    var o = $("input[name=o]");
    var options = $("#options_list");
    o.on('keyup', function () {
        var val = o.val();
        options.children("li").children("strong").each(function () {
            var currentLiText = $(this).text();
            var showCurrentLi = currentLiText.indexOf(val) !== -1;
            $(this).parent().toggle(showCurrentLi);
        });
    });

    // Showing/Hiding the options btn in new option create
    var btnotpions = $(".btn-options");
    btnotpions.on('click', function () {
        var divoptions = $("#options");
        divoptions.toggle();
    });
});

},{}]},{},[1]);

//# sourceMappingURL=main.js.map

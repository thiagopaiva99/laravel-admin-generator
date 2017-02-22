
// ADMIN vvvv

$(function(){
    var URL = get_url();

    //validte de formularios
    $("#form_time_slot")       .validator();
    $("#form_edit_profile")    .validator();
    $("#form_new_plan")        .validator();
    $("#form_edit_spec")       .validator();
    $("#form_edit_exam")       .validator();
    $("#form_new_exam")        .validator();
    $("#form_new_local")       .validator();
    $("#form_edit_local")      .validator();
    $("#form_new_closed_date") .validator();
    $("#form_new_profile")     .validator();

    //selects personalizados
    $('.select-clinics-select').selectpicker()

    //fazendo os flashes sumirem apos um tempo
    $(".alert").delay(4000).slideUp(500);

    $('[data-toggle="confirmation"]').confirmation();
    $('.confirmation').confirmation();

    $("input[name=slot_time_start]").focusout(function(){
        $("input[name=slot_time_end]").val($("input[name=slot_time_start]").val()).attr({ "min" : $("input[name=slot_time_start]").val() });
    });

    $("#start_datetime").focusout(function(){
        $("input[name=end_datetime]").val($("input[name=start_datetime]").val()).attr({ "min" : $("input[name=start_datetime]").val() });
    });

    $("#private").on('change', function(){
        $(this).val($(this).prop('checked'));
    });

    $("select[name=local_id]").on('change', function(){
        var id = $(this).val();

        /*$.ajax({
            url  : URL + 'admin/get-health-plans/' + id,
            type : 'GET',
            data : {},
            success: function(data){
                var html = "";
                $.each(data, function(key, value){
                    html += '<option value="' + data[key].id + '">' + data[key].name + '</option>';
                });

                $("select[name=medics_select]").html(html);
                $('.select_plans').selectpicker('render').selectpicker('refresh');
            }
        });*/
    });

    $("select[name=local]").on('change', function(){
        var id = $(this).val();

        /*$.ajax({
            url  : URL + 'admin/get-health-plans/' + id,
            type : 'GET',
            data : {},
            success: function(data){
                var html = "";
                $.each(data, function(key, value){
                    html += '<option value="' + data[key].id + '">' + data[key].name + '</option>';
                });

                $("select[name=medics_select]").html(html);
                $('.select_plans').selectpicker('render').selectpicker('refresh');
            }
        });*/
    });

    //quando o select de tipo trocar vai fazer a mudança
    $("select[name=slot_type]").on('change', function(){
        if($(this).val() == 1){
            $("#div_day_slot").hide(0);
            $("#div_day_of_week").show(0);

            $("input[name=slot_date]").removeAttr("required");
            $("select[name=day_of_week]").attr({"required" : "true"});
        }else{
            $("#div_day_slot").show(0).removeAttr("required");
            $("#div_day_of_week").hide(0);

            $("input[name=slot_date]").attr({"required" : "true"});
            $("select[name=day_of_week]").removeAttr("required");
        }
    });

    //datatables
    $.fn.dataTable.ext.errMode = 'throw';

    $("#dataTableBuilder_processing")
        .html("<i class='fa fa-spinner fa-spin'></i> Aguarde, processando informação! Se demorar demais, <a style='color: #571C36 !important;' class='btn-refresh-page' style='cursor: pointer'>clique aqui</a> para recarregar a página")
        .css({
            "padding" : "10px",
            "background" : "#eee",
            "margin-top" : "5px"
        });

    $(".btn-refresh-page").on('click', function(){
        location.reload();
    });

    $("#preferred_user").change(function(){
        if($(this).val() == '0'){
            $(this).val('1');
        }else{
            $(this).val('0');
        }
    });

    $("select[name=user_type]").on('change', function(){
        if($(this).val() == 3){
            $(".facebook-input").slideDown(150);
        }else{
            $(".facebook-input").slideUp(150);
        }

        if($(this).val() == 2){
            $(".cpf").slideDown(150);
        }else{
            $(".cpf").slideUp(150);
        }
    });

    $("select[name=user_type]").on('change', function(){
        if($(this).val() == 2){
            $(".select-clinics").slideDown(150);
        }else{
            $(".select-clinics").slideUp(150);
        }
    });

    $("#sub_plan").on('change', function(){
        $("input[name=have_to]").val($(this).prop('checked'));
        $("select[name=health_plan_id]").attr({ "required" : $(this).prop('checked') });
        $(".select_sub_plan").toggle(150);
    });

    $("input[name=slot_time_end]").on('focusout', function(){
       // console.log($(this).val() - $("input[name=slot_time_start]").val());
    });

    $("#private").on('change', function () {
        if($("#private").prop('checked') === true){
            // if($("input[name=value_select]").val() == 1){
            //     $(".plan_select").slideUp(150);
            //     $(".slot_count").slideUp(150);
            // }else{
            //     $(".plan_select").slideUp(150);
            // }

            //
            $(".select_plans").removeAttr("required");
        }else{
            // $(".plan_select").slideDown(150);
            // $(".slot_count").slideDown(150);

            //
            $(".select_plans").attr({"required" : "true"});
        }

    });

    $("select[name=local_id]").on('change', function(){
       var id = $(this).val();

        $.ajax({
            url  : URL + 'admin/get-doctors/' + id,
            type : 'GET',
            data : {},
            success: function(data){
                var html = "";
                $.each(data, function(key, value){
                    html += '<option value="' + data[key].id + '" >' + data[key].name + '</option>';
                });

                $("select[name=user_id]").html(html);
                $('.select_plans').selectpicker('render').selectpicker('refresh');
            }
        });
    });

    $("select[name=local]").on('change', function(){
        var id = $(this).val();

        $.ajax({
            url  : URL + 'admin/get-doctors/' + id,
            type : 'GET',
            data : {},
            success: function(data){
                var html = "";
                $.each(data, function(key, value){
                    html += '<option value="' + data[key].id + '" >' + data[key].name + '</option>';
                });

                $("select[name=user_id]").html(html);
                $('.select_plans').selectpicker('render').selectpicker('refresh');
            }
        });
    });

    $("select[name=local_id]").on('change', function(){
        $("input[name=user_id]").val($("select[name=local_id] option:selected").attr("id-user"));
    });

    $("input[name=queue]").on('change', function(){
        $("input[name=value_select]").val($(this).val());
    });

    $("select[name=clinic]").on('change', function(){
        $.ajax({
            url  : URL + 'admin/get-doctors-by-clinic',
            type : 'GET',
            data : {id : parseInt($(this).val())},
            success: function(data){
                var html = '<select class="select_medics_report form-control select_doctors show-menu-arrow" multiple="multiple" data-live-search="true" title="Selecione o médico" data-actions-box="true" required="true" name="doctors[]" tabindex="-98">';

                $.each(data, function(key, value){
                    html += '<option value="' + data[key].id + '" >' + data[key].name + '</option>';
                });
                html += '</select>';

                $(".select_medics_report").html(html);
                $('.select_medics_report').selectpicker('render').selectpicker('refresh');
            }
        });
    });

    $(".btn-save-closed").on('click', function(){

        $(".btn-save-closed").addClass("m-progress").attr("disabled");

        var user_id = $(".btn-save-closed").attr("user-id");


        if(typeof user_id !== typeof undefined && user_id !== false){
            $("input[name=medic_id]").val($(".btn-save-closed").attr("user-id"));
        }else{
            $("input[name=medic_id]").val($("#local option:selected").attr("id-user"));
        }

        var user  = $("input[name=medic_id]").val();
        var local = $("select[name=local_id]").val();
        var start = $("input[name=start_datetime]").val();
        var end   = $("input[name=end_datetime]").val();

        if(start === "" && end === ""){
            swal({
                title: "Selecione uma hora no calendário para fechar!",
                type: "error",
                timer: 1000,
                showConfirmButton: false
            });
            $(".btn-save-closed").removeClass("m-progress").removeAttr("disabled");
        }else{
            $.ajax({
                url  : URL + 'admin/closedDates/inserir',
                type : 'POST',
                data : {
                    '_token'         : 'BzxvxUKwEIOadBadigp0ph6RirWOGkYJf0vyhQ4m',
                    'start_datetime' : start,
                    'end_datetime'   : end,
                    'user_id'        : user,
                    'local_id'       : local
                },
                success: function(data){
                    if(data.id > 0){
                        var closedDate = {
                            id : data.id,
                            allDay: false,
                            title : '',
                            start : data.start_datetime.date,
                            end : data.end_datetime.date,
                            color: '#ff4d4d',
                            textColor: 'white'
                        };


                        $("#weekCalendar").fullCalendar('renderEvent', closedDate, true);
                        $("#weekCalendar").fullCalendar('removeEvents');
                        $("#weekCalendar").fullCalendar('refetchEvents');

                        $(".btn-save-closed").removeClass("m-progress").removeAttr("disabled");

                        $("#modalTimeSlot").modal('hide');

                        setTimeout(function(){
                            swal({
                                title: "Horário fechado com sucesso!",
                                type: "success",
                                timer: 1500,
                                showConfirmButton: false
                            })

                            $(".btn-save-closed").removeClass("m-progress").removeAttr("disabled");
                        }, 500);
                    }

                    if(data === "nao_deu"){
                        swal({
                            title: "Horário não disponivel!",
                            type: "error",
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }
                },
                error: function(data){
                    $(".btn-save-closed").removeClass("m-progress").removeAttr("disabled");
                }
            });
        }
    });

    setTimeout(function(){
        $(".btn-save-timeslot, .btn-save-closed").removeClass("m-progress").removeAttr("disabled");
        $(".alerta").slideUp(450);
    }, 7000);

    $(".btn-save-timeslot").on('click', function(){
        $(".btn-save-timeslot").addClass("m-progress").attr({"disabled" : "disabled"});

        $("input[name=user_id]").val($("#local option:selected").attr("id-user"));

        var local               = $("select[name=local_id]").val();
        var slot_type           = $("select[name=slot_type]").val();
        var slot_date           = $("input[name=slot_date]").val();
        var user_id             = $("input[name=user_id]").val();
        var day_of_week         = $("select[name=day_of_week]").val();
        var slot_time_start     = $("input[name=slot_time_start]").val();
        var slot_time_end       = $("input[name=slot_time_end]").val();
        // var queue_type          = $("input:radio[name=queue]:checked").val();
        //var _private            = $("#private").prop('checked');
        //var plans               = $("select[name=medics_select]").val();
        var slot_count          = $("input[name=slot_count]").val();
        //var plans_selected      = $("input[name=plans_selected]").val();
        var start               = $("input[name=start_date]").val();
        var end                 = $("input[name=end_date]").val();

        $.ajax({
            url  : URL + 'admin/time-slots/inserir',
            type : 'GET',
            data : {
                local_id        : local,
                slot_type       : slot_type,
                slot_date       : slot_date,
                user_id         : user_id,
                day_of_week     : day_of_week,
                slot_time_start : slot_time_start,
                slot_time_end   : slot_time_end,
                // queue           : queue_type,
                //private         : _private,
                //medics_select   : plans,
                slot_count      : slot_count,
                //plans_selected  : plans_selected
            },
            success: function(data){
                if(data == 0){
                    // realizar alguma ação que por enquanto nao e necessario

                    $(".btn-save-timeslot").removeClass("m-progress").removeAttr("disabled");
                }else{
                    if(data.id > 0){
                        $(".btn-save-timeslot").removeClass("m-progress").removeAttr("disabled");
                        $("#modalTimeSlot").modal('hide');

                        $('select[name=day_of_week] option').removeAttr('selected');

                        setTimeout(function(){
                            swal({
                                title: "Horário salvo com sucesso!",
                                type: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });

                            var closedDate = {
                                id : data.id,
                                allDay: false,
                                title : '',
                                start : date_formatted(new Date(start)),
                                end : date_formatted(new Date(end)),
                                color: data.slot_type == 1 ? '#ededed' : '#68c368',
                                textColor: data.slot_type == 1 ? 'black' : 'white'
                            };

                            $("#weekCalendar").fullCalendar('renderEvent', closedDate, true);
                            $("#weekCalendar").fullCalendar('removeEvents');
                            $("#weekCalendar").fullCalendar('refetchEvents');
                        }, 500);
                    }else{
                        console.log(data);
                        swal({
                            title: "Ocorreu algo errado, por favor tente novamente!",
                            type: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $(".btn-save-timeslot").removeClass("m-progress").removeAttr("disabled");
                    }
                }
            },
            error: function(data){

                console.log(data);

                /*if(data === 0){
                    swal({
                        title: "Selecione plano(s) de saúde!",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }else{
                    swal({
                        title: "Ocorreu algo errado!",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                $(".btn-save-timeslot").removeClass("m-progress").removeAttr("disabled");*/
            }
        });
    });

    $(".btn-refresh-calendar").on('click', function(){
        $("#weekCalendar").fullCalendar('removeEvents', []);
        setTimeout(function(){
            $("#weekCalendar").fullCalendar('refresh');
        }, 1500);
    });

    /// quando for trocada a clinica no cadastro de local ja trazer os dados da clinica
    $(".btn-apply-values").on('click', function(){
        swal({
            title: "Dados carregados!",
            type: "success",
            timer: 1000,
            showConfirmButton: false
        });
       $.ajax({
           url  : URL + 'admin/get-local-details',
           type : 'GET',
           data : { id : $("select[name=local_predefined]").val() },
           success: function(data){
               $("input[name=name]").val(data.name);
               $("input[name=phone]").val(data.phone);
               $("input[name=address]").val(data.address).focusout();
               $("input[name=address_complement]").val(data.address_complement);
               $("input[name=amount]").val(data.amount_str.split(" ")[1].replace(",", "."));
               $("input[name=appointment_duration_in_minutes]").val(data.appointment_duration_in_minutes);
           }
       });
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header"
    });

    $(".modal-header").css("cursor", "move");

    $('#modalTimeSlot').on('shown.bs.modal', function () {
        $(".modal-backdrop.in").css({
            "position" : "fixed"
        });
    });

    $('input[name=search_day]').on('keyup', function(e){
       if(e.which === 13){
           if(Date.parse($("input[name=search_day]").val())){
               $('#weekCalendar').fullCalendar('gotoDate', $("input[name=search_day]").val());
           }else{
               swal({
                   title: "Data Inválida!",
                   type: "error",
                   timer: 850,
                   showConfirmButton: false
               });
           }
       }
    });

    $(".btn-load-locals").on('click', function(){
        $.ajax({
            url  : URL + 'admin/get-locals-for-multiple-medics',
            type : 'GET',
            data : { users_id : $("#doctors").val() },
            success: function(data){
                swal({
                    title: "Locais carregados!",
                    type: "success",
                    timer: 1000,
                    showConfirmButton: false
                });
                $("select[name=locals]").removeAttr("disabled");

                var html = "";
                var ids = "";
                $.each(data, function(key, value){
                    if(data[key] !== null){
                        html += '<optgroup label="' + data[key].user.name + '">';
                        html += '   <option value="' + data[key].id + '">' + data[key].name + '</option>';
                        html += '</optgroup>';

                        ids += data[key].user.id + '-' + data[key].id + ',';
                    }
                });

                $('input[name=locals_selected]').val(ids);
                $("select[name=locals]").html(html);
                $('select[name=locals]').selectpicker('render').selectpicker('refresh');
            }
        });
    });

    $(".btn-load-health-plans").on('click', function(){
        /*$.ajax({
            url  : URL + 'admin/get-health-plans-for-multiple-locals',
            type : 'GET',
            data : { locals_id : $("select[name=locals]").val()},
            success: function(data){
                swal({
                    title: "Planos de saúde carregados!",
                    type: "success",
                    timer: 1000,
                    showConfirmButton: false
                });

                var html = "";

                $.each(data, function(key, value){
                    if(data[key] !== null){
                        // html += '<optgroup id="' + data[key][0].localid + '" label="' + data[key][0].localname + '">';
                        for(var i = 0; i < data[key].push(); i++){
                            html += '   <option value="' + data[key][i].id + '" class="">' + data[key][i].name + '</option>';
                        //    ' + data[key][0].localid + '-'
                        }
                        // html += '</optgroup>';
                    }
                });

                $("select[name=medics_select]").html(html);
                $('select[name=medics_select]').selectpicker('render').selectpicker('refresh');
            }
        });*/
    });

    $("#select_health_plans").on('change', function(){
        $("input[name=plans_selected]").val($(this).val());
    });

    $(".select_plans").on('change', function(){
        $("input[name=plans_selected]").val($(this).val());
    });

    $("select[medics_select] option").on('click', function(){

    });

    $("select[name=clinics]").on('change', function(){
        console.log($(this).val())
        if($("input[name=address]").val() == ''){
            $.ajax({
                url  : URL + 'admin/get-clinic-address?clinic_id=' + $(this).val(),
                type : 'GET',
                data : {},
                success: function(data){
                   $("input[name=address]").val(data.address);
                }
            });
        }
    });

    /*$(document).on('click', function(e){
        if(e.pageX < $("#eventContent").offset().left || e.pageX > ($("#eventContent").offset().left + $("#eventContent").width()) || e.pageY < $("#eventContent").offset().top || e.pageY > ($("#eventContent").offset().top + $("#eventContent").height())){
            $("#eventContent").fadeOut();
        }
    });*/
});

$(document).ready(function(){
    var URL = get_url();

    $("select[name=locals]").selectpicker();

    $("input[name=duration]").val($("select[name=local_id]").val());
    $(".facebook-input").hide(0);

    if($(".select-clinics").val() == 2){
        $(".select-clinics").show(0);
    }else{
        $(".select-clinics").hide(0);
    }
    $(".cpf").hide(0);
    $(".crm").hide(0);

    $('[data-toggle="popover"]').popover();

    $("input[name=crm]").removeAttr("required");
    $("input[name=cpf]").removeAttr("required");

    $("select[name=user_id] option:first").attr('selected','selected');

    $("input[name=slot_count]").val(10);

    //iniciando o toggle
    $("#checkbox_toggle").bootstrapToggle();

    //iniciando o multiselect
    $('.select_plans').selectpicker();
    $('.select_doctors').selectpicker();

    $(":file").filestyle('buttonText', 'Procurar Imagem');
    $(":file").filestyle('icon', false);
    $(":file").filestyle('buttonBefore', 'false');

    //escondendo o input do dia por que vem padrão selecionado
    $("#div_day_slot").hide(0);
    $("input[name=slot_date]").removeAttr("required");

    $(".fc-bg").css({"background-color" : "green"});

    //aplicando mascara nos campos
    $('input[type=datetime]').mask('00-00-0000 00:00:00-0');
    $("input[name=cpf]").mask("000.000.000-00");
    //$('input[name=slot_date]').mask('00-00-0000');
    $('input[name=phone]').mask('(00) 0000-00000').focusout(function(){
        if($(this).val().length > 14){
            $(this).mask('(00) 00000-0000');
        }else{
            $(this).mask('(00) 0000-0000');
        }
    });

    $("input[name=user_id]").val($("select[name=local_id] option:selected").attr("id-user"));

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

    setTimeout(function(){
        $(".fc-content").css({
           "background" : "red",
            "border-color" : "red"
        });
    },100);

        if(window.location.href == URL + 'admin/timeSlots/create' || window.location.href == URL + 'admin/calendar' || window.location.href.split("/")[6] === 'edit' || window.location.href.search("create") > 0 || window.location.href.search("calendar") > 0 || window.location.href.search("clinic") > 0) {
            var id = $("select[name=local_id]").val();


            /*$.ajax({
                url  : URL + 'admin/get-health-plans/' + id,
                type : 'GET',
                data : {},
                success: function(data){
                    var html = "";
                    $.each(data, function(key, value){
                        html += '<option value="' + data[key].id + '" >' + data[key].name + '</option>';
                    });

                    $("select[name=medics_select]").html(html);
                    $('.select_plans').selectpicker('render').selectpicker('refresh');
                }
            });*/
        }

    if(window.location.href.split("/")[6] === 'edit'){
        if($("#private").prop('checked') === true){
            // $(".plan_select").hide(0);
            // $(".slot_count") .hide(0);
        }
    }

    if($("#private").prop('checked') === true){
        $(".select_plans").removeAttr("required");
    }else{
        $(".select_plans").attr({"required" : "true"});
    }
});

function confirm(elem){
    $('.confirmation').confirmation();
    return false;
}

function verificar_null(obj, msg){
    if(obj !== null && obj !== undefined && obj !== ""){
        return obj;
    }else{
        return msg;
    }
}

function get_url(){
    var page = window.location.protocol + '//' + window.location.host + '/';
    return page;
}

function has_event(date_start, date_end) {
    var allEvents = [];
    allEvents = $('#weekCalenar').fullCalendar('clientEvents');

    var event = $.grep(allEvents, function (n, i) {
        if(n.start > date_start && n.end < date_end){
            return n.id;
        }else{
            return false;
        }
    });

    return event.length > 0;
}

function check_browser_version(){
    var rv = -1;

    if (navigator.appName == 'Microsoft Internet Explorer'){
        var ua = navigator.userAgent,
            re  = new RegExp("MSIE ([0-9]{1,}[\\.0-9]{0,})");

        if (re.exec(ua) !== null){
            rv = parseFloat( RegExp.$1 );
        }
    }
    else if(navigator.appName == "Netscape"){
        if(navigator.appVersion.indexOf('Trident') === -1) rv = 12;
        else rv = 11;
    }

    return rv;
}

function date_formatted(date) {
    var year = date.getYear() + 1900;
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    var hours = date.getHours();
    hours = hours.length > 1 ? hours : '0' + hours;

    var minutes = date.getMinutes().toString();
    minutes = minutes.length > 1 ? minutes : '0' + minutes;

    return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':00';
}

function fechar_plano(time_slot_detail_id, time_slot_id, new_date){
    $("#eventContent > div").html("");
    $("#eventContent > div").html('<center><br><i class="fa fa-spinner fa-spin"></i> Fechando horário...<br><br></center>');

    $.ajax({
        url  : get_url() + 'admin/close-plan',
        type : 'GET',
        data : { time_slot_detail_id : time_slot_detail_id, time_slot_id : time_slot_id, new_slot_date : new_date.split(" ")[0]},
        success: function(data){
            if(data > 0){
                swal({
                    title: "Horário fechado para este plano!",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                });

                $("#weekCalendar").fullCalendar('removeEvents');
                $("#weekCalendar").fullCalendar('refetchEvents');
                $('#eventContent').fadeOut(50);
            }else{
                swal({
                    title: "Ocorreu um erro ao fechar o horário para o plano!",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    });
}

function close_popup(){
    $('#eventContent').fadeOut();
    setTimeout(function(){
        $("#eventContent > div").html('<center><br><i class="fa fa-spinner fa-spin"></i> Carregando...<br><br></center>');
    }, 300);
}

function close_all_slot(){
    var start = $("input[name=close_slot_start]").val();
    var end   = $("input[name=close_slot_end]").val();

    $.ajax({
       url  : get_url() + 'admin/closedDates/inserir',
       type : 'post',
       data : {'_token' : 'BJiAwfkXly6OtMuVYuw2pYK7tB8ufgm2VjoRrXZv', 'user_id' : $("#local option:selected").attr("id-user"), 'local_id' : $("#local").val(), 'start_datetime' : start, 'end_datetime' : end},
       success: function(data){
           if(data.id > 0){
               $("#eventContent").fadeOut(50);
               $("#weekCalendar").fullCalendar("removeEvents");
               $("#weekCalendar").fullCalendar('refetchEvents');
           }
       }
   });
}

function close_all_slot_doctor(){
    var start = $("input[name=close_slot_start]").val();
    var end   = $("input[name=close_slot_end]").val();

    console.log(start);
    console.log(end);
    console.log($("#local").val());

    $.ajax({
        url  : get_url() + 'admin/closedDates/inserir',
        type : 'post',
        data : {'_token' : 'BJiAwfkXly6OtMuVYuw2pYK7tB8ufgm2VjoRrXZv', 'local_id' : $("#local").val(), 'start_datetime' : start, 'end_datetime' : end},
        success: function(data){
            if(data.id > 0){
                $("#eventContent").fadeOut(50);
                $("#weekCalendar").fullCalendar("removeEvents");
                $("#weekCalendar").fullCalendar('refetchEvents');
            }
        }
    });
}

function delete_time_slot(){
    $("#eventContent").fadeOut(50);

    $.ajax({
        url  : get_url() + 'admin/delete-time-slot',
        type : 'GET',
        data : { time_slot_id : $("input[name=time_slot_id]").val() },
        success: function(data){
            $("#weekCalendar").fullCalendar("removeEvents");
            $("#weekCalendar").fullCalendar("refetchEvents");
        }
    });
}

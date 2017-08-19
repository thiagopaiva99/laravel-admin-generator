<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
    var $ = jQuery;
    $(function(){
        $(".btn-generate").on('click', function(){
            var name = $("input[name=name]").val();

            var str = "";

            str += "{\n\"fieldInput\":\"id:increments\",\n\"htmlType\": \"\",\n\"validations\": \"\",\n\"searchable\": false},";

            $(".div-generate").each(function(index){
                var field_name = $(this).find(".field_name").val();
                var field_type = $(this).find("#field_type_" + (index + 1)).val();
                var field_dbtype = $(this).find("#field_dbtype_" + (index + 1)).val();
                var field_validations = $(this).find("#field_validations_" + (index + 1)).val().join(":");
                var field_searchable = $(this).find("#field_searchable_" + (index + 1)).val();

                str += "{\n";
                str += "    \"fieldInput\": \"" + field_name + ":" + field_dbtype + "\",\n";
                str += "    \"htmlType\": \"" + field_type + "\",\n";
                str += "    \"validations\": \"" + field_validations + "\",\n";
                str += "    \"searchable\": \"" + field_searchable + "\"\n";

                if( index == ($(".div-generate").size() - 1) ){
                    str += "}\n";
                }else{
                    str += "},\n";
                }

            });

            $.ajax({
                url: '/admin/pages',
                method: 'post',
                data: {
                    name: name,
                    str: str
                },
                success: function(data){
                    console.log(data);
                }
            });
        });

        $(".btn-new-field").on('click', function(){
            $(".btn-generate").removeAttr("disabled");

            var size = $(".div-generate").size() + 1;

            var div = '<div class="col-md-12 form-group div-generate">';
                div += '    <div class="col-md-3">';
                div += '        <label>Insira o nome do campo:</label>';
                div += '        <input class="form-control field_name" placeholder="Insira o nome do campo">';
                div += '    </div>';
                div += '    <div class="col-md-2">';
                div += '        <label>Selecione o dbType:</label>';
                div += '        <select id="field_dbtype_' + size + '" class="form-control field_type select_plans select-specializations show-menu-arrow" required="true" data-live-search="true" title="Selecione o tipo" data-actions-box="true">';
                div += '            <option value="string">String</option>';
                div += '            <option value="integer">Int</option>';
                div += '            <option value="double">Double</option>';
                div += '            <option value="float">Float</option>';
                div += '            <option value="text">Text</option>';
                div += '            <option value="boolean">Boolean</option>';
                div += '            <option value="date">Date</option>';
                div += '        </select>';
                div += '    </div>';
                div += '    <div class="col-md-2">';
                div += '        <label>Selecione o tipo:</label>';
                div += '        <select id="field_type_' + size + '" class="form-control field_type select_plans select-specializations show-menu-arrow" required="true" data-live-search="true" title="Selecione o tipo" data-actions-box="true">';
                div += '            <option value="text">Texto</option>';
                div += '            <option value="textarea">Textarea</option>';
                div += '            <option value="email">Email</option>';
                div += '            <option value="date">Date</option>';
                div += '            <option value="number">Numero</option>';
                div += '            <option value="password">Senha</option>';
                div += '            <option value="file">Arquivo</option>';
                div += '        </select>';
                div += '    </div>';
                div += '    <div class="col-md-2">';
                div += '        <label>Selecione as validaçoes:</label>';
                div += '        <select id="field_validations_' + size + '" class="form-control select_plans select-specializations show-menu-arrow" multiple="multiple" required="true" data-live-search="true" title="Selecione as validaçoes" data-actions-box="true">';
                div += '            <option value="required">Required</option>';
                div += '            <option value="unique">Unique</option>';
                div += '            <option value="email">Email</option>';
                div += '        </select>';
                div += '    </div>';
                div += '    <div class="col-md-2">';
                div += '        <label>O campo e pesquisavel?</label>';
                div += '        <select id="field_searchable_' + size + '" class="form-control select_plans select-specializations show-menu-arrow" required="true" data-live-search="true" title="" data-actions-box="true">';
                div += '            <option value="true">Sim</option>';
                div += '            <option value="false">Nao</option>';
                div += '        </select>';
                div += '    </div>';
                div += '</div>';

            $(div).appendTo($(".appended"));
           $('select').selectpicker();
       });
    });
</script>
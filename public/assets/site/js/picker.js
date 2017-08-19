jQuery(document).ready(function($){

    // $.removeCookie("dataMinima");
    // console.log($.cookie("dataMinima"))

    var dateBeginSelect = $('input[name=data_inicio]');
    var dateEndSelect = $('input[name=data_final]');

    var $dataMinima = "";

    $('input[name=data_inicio]').change(function(){
        // $.removeCookie("dataMinima");
        // $.cookie("dataMinima", $(this).val());
        // console.log($.cookie("dataMinima"));
    });

        if (dateBeginSelect.length && dateEndSelect.length) {

            var dateBeginInput = dateBeginSelect.pickadate({

                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyy-mm-dd',
                selectYears: 120,
                min: new Date()

            });
            var dateBegin = dateBeginInput.pickadate('picker');
            var timeBeginInput = $('input[name=hora_inicio]').pickatime({

                format: 'H:i',
                formatSubmit: 'H:i',
                interval: 30,

            });
            var timeBegin = timeBeginInput.pickatime('picker');

            $('<style type="text/css">.separated_picker input {display: none;}</style>').appendTo($('head'));
            $('input[name=start_datetime]').click(function (event) {
                event.stopPropagation();
                dateBegin.open();
            });

            var dateEndInput = dateEndSelect.pickadate({
                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyy-mm-dd',
                selectYears: 120,
                onOpen: function () {
                    var dateArray = $('input[name=data_inicio]').val().split("-");
                    var date = new Date(dateArray[0],(dateArray[1] - 1),dateArray[2]);
                    this.set('min', date)
                }

            });

            dateBegin.on({
                close: function () {
                    timeBegin.open();
                }
            });

            var dateEnd = dateEndInput.pickadate('picker');
            var timeEndInput = $('input[name=hora_final]').pickatime({

                format: 'H:i',
                formatSubmit: 'H:i',
                interval: 30,
                onOpen: function(){
                    if($('input[name=data_final]').val() == $('input[name=data_inicio]').val()){
                        var hourArray = $('input[name=hora_inicio]').val().split(':');
                        var hour = hourArray[0] + ':' + hourArray[1];
                        this.set('min', hour);
                    }
                }

            });
            var timeEnd = timeEndInput.pickatime('picker');
            timeBegin.on({
                close: function () {
                    if(timeBegin.get().split(":")[0] < 10){
                        $('input[name=start_datetime]').val(dateBegin.get() + ' 0' + timeBegin.get() + ':00');
                    }else{
                        $('input[name=start_datetime]').val(dateBegin.get() + ' ' + timeBegin.get() + ':00');
                    }
                }
            });
            $('input[name=end_datetime]').click(function (event) {
                event.stopPropagation();
                dateEnd.open();
            });
            dateEnd.on({
                close: function () {
                    timeEnd.open();
                }
            });
            timeEnd.on({
                close: function () {
                    if(timeEnd.get().split(":")[0] < 10) {
                        $('input[name=end_datetime]').val(dateEnd.get() + ' 0' + timeEnd.get() + ':00');
                    }else{
                        $('input[name=end_datetime]').val(dateEnd.get() + ' ' + timeEnd.get() + ':00');
                    }
                }
            });
        }
});

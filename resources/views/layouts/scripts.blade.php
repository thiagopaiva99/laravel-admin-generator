<script>
    $(function(){
        $.ajax({
            url: '{{ url('/admin/get-menus') }}',
            method: 'GET',
            success: function(data){
                var html = "";

                for(var i = 0; i < data.length; i++){
                    if(document.URL.search(data[i].active) > 0){
                        html += '<li class="active"><a href="/admin/' + data[i].link_to + '"><i class="' + data[i].icon + '"></i> <span>' + data[i].menu + '</span></a></li>'
                    }else{
                        html += '<li><a href="/admin/' + data[i].link_to + '"><i class="' + data[i].icon + '"></i> <span>' + data[i].menu + '</span></a></li>'
                    }
                }

                $(html).appendTo($("#menu"));
            }
        });
    });
</script>
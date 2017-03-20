<script>
    $(function(){
       $.ajax({
           url: '{{ url('/admin/get-menus') }}',
           method: 'GET',
           success: function(data){
               var html = "";

               for(var i = 0; i < data.length; i++){
                   if(document.URL.search(data[i].active) > 0){
                       html += '<li class="active"><a href="' + data[i].link_to + '"><i class="' + data[i].icon + '"></i> ' + data[i].menu + '</a></li>'
                   }else{
                       html += '<li><a href="' + data[i].link_to + '"><i class="' + data[i].icon + '"></i> ' + data[i].menu + '</a></li>'
                   }
               }

               $("#menu").html(html);
           }
       });
    });
</script>
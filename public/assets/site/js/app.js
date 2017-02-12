jQuery(document).ready(function($){

    setTimeout(function(){
        $(".dismiss-alert").slideUp(350);
    }, 4000);

    var phone = $('input[name=phone]');

    phone.mask('(99) 9999?9-9999');

    phone.on("blur", function() {
        var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );

        if( last.length == 3 ) {
            var move = $(this).val().substr( $(this).val().indexOf("-") - 1, 1 );
            var lastfour = move + last;
            var first = $(this).val().substr( 0, 9 );

            $(this).val( first + '-' + lastfour );
        }
    });

    var animate = true;
    var time = 800;
    var next;

    var screen = $('.phone-screen').find('.screen');
    var button = $('.information').find('ul.list').find('a');

    var show = screen.eq(0);

    screen.each(function(){

        $(this).addClass('hidden');

    });

    show.removeClass('hidden');

    button.click(function(event){

        event.preventDefault();

        var clicked = $(this);
        var href = clicked.attr('href');

        if (animate) {

            animate = false;

            button.each(function(){

                $(this).parent().removeAttr('class');

            });

            if (href.replace('#','') != show.attr('id')) {

                next = $(href);
                clicked.parent().addClass('show');

            } else {

                next = screen.eq(0);

            }

            show.animate({left:'-100%'}, time);
            next.css({display:'block',visibility:'visible',position:'absolute',left:'100%',top:0}).animate({left:0}, time);

            setTimeout(function(){

                show.stop(true,true).removeAttr('style').addClass('hidden');
                next.stop(true,true).removeAttr('style').removeClass('hidden');

                show = next;
                animate = true;

            }, time);

        }

    })

});

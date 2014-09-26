(function($) {
    
    $(function() {
        
        $('.book-now').click(function() {

           $overlay = $('<div />');
           $overlay.addClass('op-overlay');

           $main = $('<div />');
           $main.addClass('op-main');


           $close = $('<div />');
           $close.addClass('op-close');

           IFRAMEURL = $(this).data('url'); 
           $id = $(this).data('rel');
           //IFRAMEURL = IFRAMEURL+'/'+$id;
           $iframe = $('<iframe />');
           $iframe.attr({
               'src':IFRAMEURL,
               'width':'100%',
               'height':'500px',
               'border':0
           });

           $main.append($close);
           $main.append($iframe);
           $overlay.append($main);
           $overlay.show();
           $('body').append($overlay);

            $('.op-close').click(function() {
               $overlay.remove();
               $overlay.hide();
            });
        });


        $('body').on('keyup',function(e) {
            if(e.keyCode == 27) {
               $overlay.remove();
               $overlay.hide();
            }
        });


        $('.href-link').click(function(e) {
            e.preventDefault();
            idhref = $(this).attr('href');
            
            scrolltoppos = $(idhref).offset().top;
            
            $('html,body,root').animate({'scrollTop':scrolltoppos});


        })
        
    });
    
})(jQuery)
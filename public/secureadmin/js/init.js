function setLocation(url) {
    window.location = url;
}

$(function(e) {

    $('.confirm-data').click(function(e) {
        
        confirm_act = window.confirm('Are you sure to perform this action ?');
        
        if(!confirm_act) {
            e.preventDefault();
            return;
        }
        
        
    })    
    
    
    $('input[name=checkall]').click(function() {
        
        rel = $(this).data('rel');
        
        if($(this).is(':checked')) {
            $('.'+rel).attr('checked',true);
        }
        else {
            $('.'+rel).attr('checked',false);            
        }
    
    });
    
});

jQuery(document).ready(function() {
    jQuery(".field-name-field-conversation .field-items .full-comment").hide();
    jQuery(".field-name-field-conversation .field-label").click ( function () { 
        if(jQuery(this).hasClass('collapsed') || jQuery(this).hasClass('expanded')) {
            jQuery(this).toggleClass('expanded');
            jQuery(this).toggleClass('collapsed');
            jQuery(this).parent().find( ".field-items .full-comment").slideToggle('slow');
        }
    } );
});


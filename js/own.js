jQuery( function() {
  jQuery( '.economy-owner' ).click (function() {

    var button = this;
    var action = 'own';
    var confirmmessage = "Are you sure you want to become responsible for this proposal?";
    if (jQuery(button).hasClass('owned')) {
        action = 'unown';
        confirmmessage = "Are you sure? This will detach you from the proposal so other economy person can take care of it.";
    }
    var target = jQuery( this ).attr( 'href' ) + '/' + action;

    if (confirm(confirmmessage)) {
      jQuery.ajax({
        url: target,
        dataType: 'json',
        success: function(data) {
          console.log('SUCCESS');
          console.log( data );
          if (action = 'own') {
            jQuery(button).text('Yes');
            jQuery(button).removeClass('not-owned');
            jQuery(button).addClass('owned');
          } else {
            jQuery(button).text('No');
            jQuery(button).removeClass('owned');
            jQuery(button).addClass('not-owned');
          }
        },
        error: function() {
          alert( 'An error occurred while processing your request' );
        }
      });
    }
    
    return false;
  });
});

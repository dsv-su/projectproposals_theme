jQuery( function() {
    jQuery(' .economy-owner' ).on('click', this, function() {

    var button = this;
    var action = 'own';
    var confirmmessage = "Are you sure you want to become responsible for this proposal?";
    if (jQuery(button).hasClass('owned')) {
        action = 'unown';
        confirmmessage = "Are you sure? This will detach you from the proposal so other economy person can take care of it.";
    }
    var target = jQuery( this ).attr( 'href' ) + '/' + action;
    var imageurl = jQuery(button).children('img').attr('src').slice(0,-11);
    var ownername = jQuery( this ).parents().eq(1).find( ".owner .field-item" );

    if (confirm(confirmmessage)) {
      jQuery.ajax({
        url: target,
        dataType: 'json',
        success: function(data) {
          if (action == 'own') {
            jQuery(button).removeClass('not-owned');
            jQuery(button).children('img').attr('src', imageurl + 'exclude.png');
            jQuery(button).children('img').attr('alt', 'Unassign the proposal from the current economy person');
            jQuery(button).children('img').attr('title', 'Unassign the proposal from the current economy person');
            jQuery(button).addClass('owned');
            jQuery(ownername).text(data);
          } else {
            jQuery(button).removeClass('owned');
            jQuery(button).addClass('not-owned');
            jQuery(button).children('img').attr('src', imageurl + 'include.png');
            jQuery(button).children('img').attr('alt', 'Assign the proposal to me');
            jQuery(button).children('img').attr('title', 'Assign the proposal to me');
            jQuery(ownername).text(data);
          }
        },
        error: function() {
          alert( 'You cannot do this.' );
        }
      });
    }
    
    return false;
  });
});

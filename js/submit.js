jQuery( function() {
  jQuery( '#edit-proposalform-proposal-submit' ).click( function() {
    if ( jQuery( '#edit-proposalform-proposal-title' ).val() == null
      || jQuery( '#edit-proposalform-proposal-title' ).val() == '' ) {
      alert( 'A title is required!' );
      return false;
    } else if ( jQuery( '#edit-proposalform-proposal-dsv-person-in-charge' ).val() == null
      || jQuery( '#edit-proposalform-proposal-dsv-person-in-charge' ).val() == '' ) {
      alert( 'An author is required!' );
      return false;
    } else if ( jQuery( '#edit-proposalform-proposal-duration' ).val() == null
      || jQuery( '#edit-proposalform-proposal-duration' ).val() == '' ) {
      alert( 'A duration is required!' );
      return false;
    }
  });
});

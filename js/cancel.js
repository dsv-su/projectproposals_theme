jQuery( function() {
  jQuery( '#edit-proposalform-proposal-cancel' ).click( function() {
    // Empty all fields and set values
    jQuery( '#edit-proposalform-proposal-edit-node' ).val( '' );
    jQuery( '#edit-proposalform-proposal-title' ).val( '' );
    jQuery( '#edit-proposalform-proposal-dsv-person-in-charge' ).val( '' );
    jQuery( '#edit-proposalform-proposal-deadline-datepicker-popup-0' ).val( '' );
    jQuery( '#edit-proposalform-proposal-duration' ).val( '' );

    // "Is DSV coordinating" radio buttons
    jQuery( '#edit-proposalform-proposal-is-dsv-coordinating-0' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-is-dsv-coordinating-1' ).attr( 'checked', false );

    jQuery( '#edit-proposalform-proposal-other-coordinator' ).val( '' );
    jQuery( '#edit-proposalform-proposal-program-call-target' ).val( '' );

    // "Other coordinator" checkbox
    jQuery( '#edit-proposalform-proposal-co-financing-needed' ).attr( 'checked', false );

    jQuery( '#edit-proposalform-proposal-co-financing-covered-by' ).val( '' );
    jQuery( '#edit-proposalform-proposal-percent-oh-costs-covered' ).val( '' );
    jQuery( '#edit-proposalform-proposal-funding-organization' ).val( '' );
    jQuery( '#edit-proposalform-proposal-total-budget-amount-for-co' ).val( '' );
    jQuery( '#edit-proposalform-proposal-total-budget-amount-for-ds' ).val( '' );

    // Right side checkboxes and radios
    jQuery( '#edit-proposalform-proposal-ok-from-dsv-economy' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-forskningsservice-informed' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-ok-from-uno' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-sent-to-birgitta-o' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-currency-kr' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-currency-' ).attr( 'checked', false );
    jQuery( '#edit-proposalform-proposal-currency---2' ).attr( 'checked', false );

    // Reset add/edit area header
    jQuery( '#proposal-form h3' ).html( 'Add new proposal' );

    // Hide the cancel button
    jQuery( '#edit-proposalform-proposal-cancel' ).attr( 'hidden', 'true' );

    // Activate all edit buttons
    jQuery( '.disabled' ).removeAttr( 'disabled' );
    jQuery( '.disabled' ).addClass( 'edit' );
    jQuery( '.edit' ).removeClass( 'disabled' );

    // Fold the "add area" back up
    jQuery( '.ctools-collapsible-handle' ).trigger( 'click' );

    // Restore visiblity to the other proposals
    jQuery( 'div.proposal' ).fadeTo( 'slow', 1 );

    return false;
  });
});
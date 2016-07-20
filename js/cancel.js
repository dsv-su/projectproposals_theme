jQuery( function() {
    jQuery(' .cancel' ).on('click', this, function() {

    var button = this;
    var action = 'cancel';
    var confirmmessage = "Are you sure? This will cancel your proposal and all stakeholders will be informed. " +
        "You will be able to un-cancel it later though.";
    if (jQuery(button).hasClass('cancelled')) {
        action = 'uncancel';
        confirmmessage = "Are you sure? This will un-cancel your proposal and all stakeholders will be informed.";
    }

    if (confirm(confirmmessage)) {

        this.className.replace('cancel ','');
        var target = jQuery( this ).attr( 'href' ) + '/' + action;
        var title = jQuery( this ).parents().eq(2).children( '.proposal-header' ).children( 'h2:first' );
        var proposaldiv = jQuery( this ).parents().eq(2);
        var nodeid = jQuery(proposaldiv).attr('id').substring(5);

        // Find all buttons that we need to alter depending on cancel-status.
        var approve = jQuery(proposaldiv).find( ".approve:not(.hidden)");
        var firstnotapproved = jQuery(approve).parent().find( ".not-approved" );

        var notapproved = jQuery(proposaldiv).find( ".approved:not(.hidden)").parent().next().find( ".not-approved" );
        if (!notapproved.length) {  
            notapproved = jQuery(proposaldiv).find( ".not-approved:first" );
        }
        var firstapproved = jQuery(notapproved).parent().find( ".approve" );

        var edit = jQuery( this ).parents().eq(2).find( ".edit" );

        jQuery.ajax({
          url: target,
          dataType: 'json',
          success: function(data) {
            if (action == 'cancel') {
                jQuery(title).append(' (Cancelled)');
                jQuery(button).text('Uncancel proposal');
                jQuery(button).addClass('cancelled');
                jQuery(proposaldiv).fadeTo( 'slow', 0.3 );
                jQuery(approve).addClass('hidden');
                jQuery(firstnotapproved).removeClass('hidden');
                //jQuery(approve).replaceWith('<span class="not-approved">No</span>');
                jQuery(edit).replaceWith('');
            } else {
                jQuery(title).text(function(_,txt) {return txt.slice(0, -12);});
                jQuery(button).text('Cancel proposal');
                jQuery(button).removeClass('cancelled');
                jQuery(firstapproved).removeClass('hidden');
                jQuery(notapproved).addClass('hidden');
                jQuery(proposaldiv).fadeTo( 'slow', 1 );
                //jQuery(firstnotapproved).replaceWith('<a href="node/approve/' + nodeid + '" class="approve '+ approveby +'">Approve</a>');
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

/*jQuery( function() {
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
*/
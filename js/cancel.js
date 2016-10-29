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
        var proposaldiv = jQuery( this ).parents().eq(1);
        var title = jQuery(proposaldiv).children( '.proposal-header' ).children( 'h2:first' );
        var nodeid = jQuery(proposaldiv).attr('id').substring(5);

        // Find buttons that we need to disable when cancelling.
        var approve = jQuery(proposaldiv).find( ".approve:not(.hidden)");
        // Find the relative 'Not approved' or 'Not sent' element.
        var approvenegative = jQuery(approve).parent().find( ".not-approved" );

        // Find buttons that we need to turn back to 'edit' mode when uncancelling.
        var canapprove = jQuery(proposaldiv).find( ".not-approved.haspermission" );
        // Find the relative 'approve' element.
        var canapprovenegative = jQuery(canapprove).parent().find( ".approve" );

        var edit = jQuery(proposaldiv).find( ".edit" );

        var economyown = jQuery(proposaldiv).find( ".economy-owner" );
        var imageurl = jQuery(button).children('img').attr('src').slice(0,-10);

        jQuery.ajax({
          url: target,
          dataType: 'json',
          success: function(data) {
            if (action == 'cancel') {
                jQuery(proposaldiv).fadeTo( 'slow', 0.3 );
                jQuery(title).append(' (Cancelled)');
                jQuery(button).children('img').attr('src', imageurl + 'reload.png');
                jQuery(button).children('img').attr('alt', 'Uncancel this proposal');
                jQuery(button).children('img').attr('title', 'Uncancel this proposal');
                jQuery(button).addClass('cancelled');
                jQuery(approve).addClass('hidden');
                jQuery(approvenegative).removeClass('hidden');
                //jQuery(approve).replaceWith('<span class="not-approved">No</span>');
                jQuery(edit).addClass('hidden');
                jQuery(economyown).addClass('hidden')
                jQuery(proposaldiv).find( ".fourth-row" ).children().hide();
            } else {
                jQuery(proposaldiv).fadeTo( 'slow', 1 );
                jQuery(title).text(function(_,txt) {return txt.slice(0, -12);});
                jQuery(button).children('img').attr('src', imageurl + 'cancel.png');
                jQuery(button).children('img').attr('alt', 'Cancel this proposal');
                jQuery(button).children('img').attr('title', 'Cancel this proposal');
                jQuery(button).removeClass('cancelled');
                jQuery(canapprovenegative).removeClass('hidden');
                jQuery(canapprove).addClass('hidden');
                jQuery(economyown).removeClass('hidden');
                jQuery(edit).removeClass('hidden');
                //jQuery(firstnotapproved).replaceWith('<a href="node/approve/' + nodeid + '" class="approve '+ approveby +'">Approve</a>');
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

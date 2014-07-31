jQuery( function() {
  jQuery( '.edit' ).click( function() {
    var disabled = jQuery( this ).attr( 'disabled' );
    if ( disabled == 'true') {
      return false;
    }

    jQuery.ajax({
      url: jQuery( this ).attr( 'href' ),
      dataType: 'json',
      success: function( data ) {
        var checkboxes = new Array();
        checkboxes[1] = 'edit-proposalform-proposal-co-financing-needed';
        checkboxes[2] = 'edit-proposalform-proposal-ok-from-dsv-economy';
        checkboxes[3] = 'edit-proposalform-proposal-forskningsservice-informed';
        checkboxes[4] = 'edit-proposalform-proposal-ok-from-uno';
        checkboxes[5] = 'edit-proposalform-proposal-sent-to-birgitta-o';

        jQuery.each( data, function( key, value ) {
          // We need special treatment for some fields
          if ( key == 'edit-proposalform-proposal-is-dsv-coordinating' ) {
            if ( value == 1 ) {
              document.getElementById( key + '-1' ).checked = true;
              document.getElementById( key + '-0' ).checked = false;
            } else {
              document.getElementById( key + '-1' ).checked = false;
              document.getElementById( key + '-0' ).checked = true;
            }

          // If this is a checkbox...
          } else if ( jQuery.inArray( key, checkboxes ) > -1 ) {
            if ( value == 1 ) {
              document.getElementById( key ).checked = true;
            } else {
              document.getElementById( key ).checked = false;
            }

          // Currency needs extra special treatment!
          } else if ( key == 'edit-proposalform-proposal-currency') {
            switch ( value ) {
              case 'kr':
                document.getElementById( key + '-kr' ).checked = true;
                document.getElementById( key + '-' ).checked = false;
                document.getElementById( key + '---2' ).checked = false;
                break;
              case '$':
                document.getElementById( key + '-kr' ).checked = false;
                document.getElementById( key + '-' ).checked = true;
                document.getElementById( key + '---2' ).checked = false;
                break;
              case '€':
                document.getElementById( key + '-kr' ).checked = false;
                document.getElementById( key + '-' ).checked = false;
                document.getElementById( key + '---2' ).checked = true;
                break;
            }
          }

          // If all other checks fail, just plainly assign values
          else {
            jQuery( '#' + key ).val( value );
          }

        });

        // Change header text so that user knows what he/she is doing
        jQuery( '#proposal-form h3' ).html( 'Edit proposal' );

        // Inactivate all other edit buttons
        jQuery( '.edit' ).attr( 'disabled', true );
        jQuery( '.edit' ).addClass( 'disabled' );
        jQuery( '.disabled' ).removeClass( 'edit' );

        // Activate the cancel button within the edit area
        jQuery( '#edit-proposalform-proposal-cancel' ).removeAttr( 'hidden' );

        // Scroll to the top to show the editing view
        jQuery( 'html, body' ).animate( { scrollTop: 0 }, 'fast' );

        // "Click" the header to get the area to show (if it's not already showing)
        if ( jQuery( '.ctools-toggle-collapsed' )[0] ) {
          jQuery( '.ctools-collapsible-handle' ).trigger( 'click' );
        }

        // Make all other proposals a bit opaque so that the user's focus grabs the edit area
        jQuery( 'div.proposal' ).fadeTo( 'slow', 0.5 );

      },
      error: function( data ) {
        alert( 'An error occurred while processing your request' );
      }
    });

    return false;
  });
});
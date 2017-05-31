jQuery( function() {
  jQuery(' .approve' ).on('click', this, function() {
    var approvebytemp = this.className.replace('approve ','');
    var approveby = approvebytemp.replace(' haspermission','');

//    var nid = jQuery(this).parents().eq(2).attr('id').replace('node-', '');
    var target = jQuery( this ).attr( 'href' ) + '/' + approveby;
    var button = this;
    var yesnoneighbourbutton = jQuery(this).parents
    var confirmmessage = "Are you sure you want to approve this proposal?";

    if (approveby == 'request-dsv-economy') {
      confirmmessage = "Are you sure you want to send this proposal to DSV economy? Ensure that you have filled in all neccessary information.";
    }

    if (approveby == 'request-vice-head') {
      confirmmessage = "Are you sure you want to send this proposal to Vice head? Ensure that you have filled in all neccessary information.";
    }

    if (approveby == 'final') {
      confirmmessage = "Do you confirm that you have sent the files to registrator?";
    }

    if (approveby == 'funding-no') {
      confirmmessage = "Do you want to report that your proposal have NOT been granted funding by the Funding Organization?\n\nIMPORTANT: Please also send the decision letter to register@dsv.su.se.";
    }

    if (approveby == 'funding-yes') {
      confirmmessage = "Do you want to report that your proposal have been granted funding by the Funding Organization?\n\nIMPORTANT: Please also send the decision letter to register@dsv.su.se";
    }

    if (jQuery(button).hasClass('disabled')) {
      alert('You need to complete your proposal by uploading attachments before sending.');
      return false;
    }

    if (confirm(confirmmessage)) {
      jQuery.ajax({
        url: target,
        dataType: 'json',
        success: function(data) {
          //jQuery(button).parent().find( '.ok-from-' + approveby ).find('.field-item').text( "Yes");
          if (approveby == 'request-dsv-economy' || approveby == 'request-vice-head' || approveby == 'final') {
            jQuery(button).text('Sent');
            jQuery(button).addClass('approved');
          } else if (approveby == 'funding-no') {
            jQuery(button).text('No');
            jQuery(button).addClass('not-approved');
          } else {
            jQuery(button).text('Yes');
            jQuery(button).addClass('approved');
          }
          jQuery(button).siblings("a").addClass('hidden');
          jQuery(button).removeClass('approve');
        },
        error: function() {
          alert( 'You cannot do this.' );
        }
      });
    }
    
    return false;
  });
});

/*jQuery( function() {
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
        checkboxes[0] = 'edit-proposalform-proposal-co-financing-needed';
        checkboxes[1] = 'edit-proposalform-proposal-ok-from-dsv-economy';
        checkboxes[2] = 'edit-proposalform-proposal-forskningsservice-informed';
        checkboxes[3] = 'edit-proposalform-proposal-ok-from-uno';
        checkboxes[4] = 'edit-proposalform-proposal-sent-to-birgitta-o';
        checkboxes[5] = 'edit-proposalform-proposal-ok-from-unit-head';

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

          } else if ( key == 'edit-proposalform-proposal-attachment-unit-upload' ) {
html = '<div class="form-control" id="lol">';
value.forEach(function(item) {
    html += '<span class="file"><a href="' + item[1]+ '">' + item[0] + "</a><br/></span>";
});
html += '<input type="hidden" value="22, 23" name="proposalform_proposal_attachment_unit_already[fid]"></input></div>';
jQuery( '#' + key ).parent().parent().parent().prepend(html);

                  console.log(value[0]);
                 console.log(value[1]);
                 // jQuery( '#' + key ).parent().prepend(html);
            //jQuery( '#' + key ).attr( 'default_value', value );
            //jQuery( '#' + key ).val( value );
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
*/
<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */

  global $base_url;

  // Find out if user can edit this proposal
  $editable = false;
  $economy = false;
  $unithead = false;
  $vicehead = false;
  $admin = false;
  $cancelled = false;
  $secretary = false;

  if ($user->uid == $node->uid) {
    // User is the owner/author of this proposal
    $editable = true;

  } else if (isset($user->roles[3])) {
    // User is an administrator
    $editable = true;
    $admin = true;

  } else if (isset($user->roles[5])) {
    // User is the vice prefect
    $editable = true;
    $vicehead = true;

  } else if (isset($user->roles[6])) {
    // User is the institution secretary
    $secretary = true;
    $editable = true;

  } else if (isset($user->roles[7]) && isset($node->field_unit_head['und'][0]['uid']) && $user->uid == $node->field_unit_head['und'][0]['uid']) {
    // User is the unit head
    $editable = true;
    $unithead = true;

  } else if (isset($user->roles[8])) {
    // User is Economy
    $economy = true;
    $editable = true;
  }
  $cancellable = true;
  if (!$editable) {
    $cancellable = false;
  }

    $last_two_logins = db_query("SELECT login, hostname, one_time
                   FROM {login_history}
                   WHERE uid = :uid
                   ORDER BY login DESC
                   LIMIT 2", array(':uid' => $user->uid))->fetchAll();
    if (!isset($last_two_logins[1])) {
        $lastlogin = $last_two_logins[0]->login;
    } else {
        $lastlogin = $last_two_logins[1]->login;
    }
    $new = false;
    $lastrevision = array_values(node_revision_list($node))[0];
    $lasteditor = user_load($lastrevision->uid);

    if ($lastrevision->timestamp >= $lastlogin && $editable && $lasteditor->uid <> $user->uid) {
        $new = true;
    }
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes;
    if (isset($node->field_cancelled['und'][0]['value']) &&
        $node->field_cancelled['und'][0]['value']) { print ' dimmed'; }
    if ($new) { print ' updatesavailable';}
    ?> clearfix"<?php print $attributes; ?>>

  <div class="proposal-header">
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><?php print $title;
      if (isset($node->field_cancelled['und'][0]['value']) && $node->field_cancelled['und'][0]['value']) {
        if (!$admin) {
            $editable = false;
        }
        print ' (Cancelled)';
        $cancelled = true;
        $cancelledclass = ($cancelled ? 'hidden' : '');
      } else {
        if ($new) {
            print ' (Updated)';
        }
        $cancelledclass = '';
      }?></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <!-- Node author -->
    <div class="author">
        <?php
        print '<span class="field-label">Main researcher: </span>';
        if (!empty($node->field_dsv_person_in_charge['und'][0]['user']->realname)) {
            print $node->field_dsv_person_in_charge['und'][0]['user']->realname;
        } else {
            print $node->field_dsv_person_in_charge['und'][0]['user']->name;
        }
        ?>
    </div>

    <!-- Deadline -->
    <div class="deadline">
      <?php print render($content['field_deadline']); ?>
    </div>

    <!-- Duration -->
    <div class="duration">
      <?php print render($content['field_duration']); ?>
    </div>

    <!-- Editors -->
    <!--
    <div class="editors">
        <?php 
        print '<span class="field-label">Editors: </span>';
        $editors = array_unique(array_map(create_function('$o', 'return $o->uid;'), node_revision_list($node)));

        if (($key = array_search(1, $editors)) !== false) {
            unset($editors[$key]);
        }

        if (($key = array_search($uid, $editors)) !== false) {
            unset($editors[$key]);
        }

        $lasteditor = end($editors);
        if (!$editors) {
            print 'none';
        }
        foreach ($editors as $key => $uid) {
            $editor = user_load($uid);
            print $editor->realname;
            if ($uid == $lasteditor) {
                print '';
            } else {
                print ', ';
            }
        }
        ?>
    </div>
    -->
    <!-- This section is only for economy people. -->
    <div class="owner">
        <span class="field-label">Economy owner: </span>
        <span class="field-item">
        <?php
            if (isset($node->field_economy_owner['und'][0]['uid']) && $node->field_economy_owner['und'][0]['uid']) {
                $tempuser = user_load($node->field_economy_owner['und'][0]['uid']);
                print $tempuser->realname;
            } else {
                print 'not yet assigned';
            }
           // print '</br>';
           /* if ($economy || $admin) {
                if ($user->uid == $node->field_economy_owner['und'][0]['uid']) {
                    print '<br><a href="node/economy-own/' . $node->nid . '" class="economy-owner owned '.$cancelledclass.'">Unassign the proposal from me</a>';
                } else {
                    print '<br><a href="node/economy-own/' . $node->nid . '" class="economy-owner not-owned '.$cancelledclass.'"> Assign the proposal to me</a>';
                }
            }*/
        ?>
        </span>
    </div>


    <!-- Cancelling -->
    <?php

//TO CLEAN UP!
$editors = array_map(create_function('$o', 'return $o->uid;'), node_revision_list($node));
$lasteditor = user_load(array_values($editors)[0]);
  if ($lasteditor->uid == $node->uid) {
    // User is the owner/author of this proposal
  } else if (isset($lasteditor->roles[3])) {
    // User is an administrator
  } else if (isset($lasteditor->roles[5])) {
    // User is the vice prefect
  } else if (isset($lasteditor->roles[7])) {
    // User is the unit head
  } else if (isset($lasteditor->roles[8])) {
    // User is Economy
  }

        if ($cancellable) {
            if ($cancelled) {
                print '<br><a href="node/cancel/' . $node->nid . '" class="cancel cancelled">
                    <img src="'.$base_url.'/'.drupal_get_path('theme', 'projectproposals_theme').'/images/reload.png'.'" title="Uncancel proposal" alt="Uncancel this proposal"></a>';
            } else {
                print '<br><a href="node/cancel/' . $node->nid . '" class="cancel">
                    <img src="'.$base_url.'/'.drupal_get_path('theme', 'projectproposals_theme').'/images/cancel.png'.'" title="Cancel proposal" alt="Cancel this proposal"></a>';
            }
        }
    ?>

    <?php

    if ($economy || $admin) {
        if (!isset($node->field_economy_owner['und'][0]['uid']) || $user->uid !== $node->field_economy_owner['und'][0]['uid']) {
            print '<a href="node/economy-own/' . $node->nid . '" class="economy-owner not-owned '. $cancelledclass .'">
                <img src="'.$base_url.'/'.drupal_get_path('theme', 'projectproposals_theme').'/images/user-include.png'.'" title="Assign the proposal to me" alt="Assign the proposal to me"></a>';
            } else {
                print '<a href="node/economy-own/' . $node->nid . '" class="economy-owner owned '. $cancelledclass .'">
                    <img src="'.$base_url.'/'.drupal_get_path('theme', 'projectproposals_theme').'/images/user-exclude.png'.'" title="Unassign the proposal from the current economy person" alt="Unassign the proposal from the current economy person"></a>';
            }
    } 
    ?>

  </div>

    <div class="content" <?php print $content_attributes; ?>>
      <?php
        // We hide the comments and links now so that we can render them later.
        //hide($content['comments']);
        hide($content['links']);

        // First row
        // -------------------------------
        print '<div class="first-row">';

        // DSV coordinating
        print '<div class="dsv-coordinating">';
        print render($content['field_is_dsv_coordinating']);
        print '</div>';

        // Other coordinator
        print '<div class="other-coordinator">';
        print render($content['field_other_coordinator']);
        print '</div>';

        // Coapplicatns
        print '<div class="coapplicants">';
        print render($content['field_coapplicants']);
        print '</div>';

        print '</div>';
        // End first row
        // -------------------------------


        // Second row
        // -------------------------------
        print '<div class="second-row">';

        // Program/Call/Target
        print '<div class="program-call-target">';
        print render($content['field_program_call_target']);
        print '</div>';

        // Co-financing
        if (!empty($node->field_co_financing_covered_by['und'][0]['value'])) {
            $content['field_co_financing_needed']['#title'] = "Co-financing";
            $content['field_co_financing_needed'][0]["#markup"] .= ", covered by " . $node->field_co_financing_covered_by['und'][0]['value'];
        }

        print '<div class="cofinancing">';
        print render($content['field_co_financing_needed']);
        print '</div>';

        // Co-financing covered by
        // print '<div class="cofinancing-covered-by">';
        // print render($content['field_co_financing_covered_by']);
        // print '</div>';

        // OH costs covered
        print '<div class="oh-costs-covered">';
        print render($content['field_percent_oh_costs_covered']);
        print '</div>';

        print '</div>';
        // End second row
        // -------------------------------

        // Third row
        // -------------------------------
        print '<div class="third-row">';

        // Funding organization
        print '<div class="funding-organization">';
        print render($content['field_funding_organization']);
        print '</div>';

        // Total budget for complete project
        print '<div class="total-budget-amount">';
        $content['field_total_budget_amount_for_co'][0]["#markup"] // Add currency
            .= " " . $node->field_currency['und'][0]['value'];
        print render($content['field_total_budget_amount_for_co']);
        print '</div>';

        // Budget amount for DSV
        print '<div class="budget-amount-dsv">';
        $content['field_total_budget_amount_for_ds'][0]["#markup"] // Add currency
            .= " " . $node->field_currency['und'][0]['value'];
        print render($content['field_total_budget_amount_for_ds']);
        print '</div>';

        print '</div>';
        // End of third row
        // -------------------------------


        // Fourth row
        // -------------------------------
        // We only show Attachments/Comments for authors or editors.
        if ($editable) {
        print '<div class="fourth-row">';
            print '<div class="comment">';
            print render($content['field_comment']);
            print '</div>';

            print '<div class="attachments">';
            print render($content['field_attachments']);
            print '</div>';

    //    print '<span class="note">Proposal tips: it has to be approved by Unit head, after that it can be filled in with details and sent to DSV economy.</span>';

        print '</div>';
        }
        // End of fourth row
        // -------------------------------


        // Right section
        // -------------------------------
        print '<div class="right-section">';

        // OK from Unit head
        print '<div class="ok-from-unit-head">';
            print '<span class="field-label">OK from Unit head: </span>';
            //print render($content['field_ok_from_unit_head']);
            if (isset($node->field_ok_from_unit_head['und'][0]['value']) &&
                $node->field_ok_from_unit_head['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $unithead) && !$cancelled) {
                print '<a href="node/approve/'.$node->nid. '" class="approve unit-head">Approve</a>';
                print '<span class="not-approved hidden">No</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve unit-head hidden">Approve</a>';
                print '<span class="not-approved">No</span>';
            }
        print '</div>';

        /*
        // Request to DSV Economy
        print '<div class="request-to-dsv-economy">';
            print '<span class="field-label">Request to DSV economy: </span>';
            if ($node->field_request_to_dsv_economy['und'][0]['value']) {
                print '<span class="approved">Sent</span>';
            } else if (($admin || $user->uid == $node->uid) && !$cancelled
                    && $node->field_ok_from_unit_head['und'][0]['value']) {
                // We disable request-dsv-economy button if some fields are not filled in.
                // We probably need to clarify which fields are mandatory.
                if (!$node->field_attachment_to_dsv_economy['und']) {
                    $disabledclass = ' disabled';
                }
                print '<span class="not-approved hidden">Not sent</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve request-dsv-economy'.$disabledclass.'">Send</a>';
            } else {
                print '<span class="not-approved">Not sent</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve request-dsv-economy hidden'.$disabledclass.'">Send</a>';
            }
            // Economy people shouldn't edit proposals until they're requested to.
            if ($economy & !$node->field_request_to_dsv_economy['und'][0]['value']) {
                $editable = false;
            }
        print '</div>';
        */

        // OK from DSV Economy
        print '<div class="ok-from-dsv-economy">';
            print '<span class="field-label">OK from DSV economy: </span>';
            $haspermission = '';
            if ($admin || $economy) {
                $haspermission = ' haspermission';
            }
            if ($node->field_ok_from_dsv_economy['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $economy) && !$cancelled) {
                print '<span class="not-approved hidden'.$haspermission.'">No</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve dsv-economy'.$haspermission.'">Approve</a>';
            } else {
                print '<span class="not-approved'.$haspermission.'">No</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve dsv-economy hidden'.$haspermission.'">Approve</a>';
            }
        print '</div>';

        // Forskningsservice informed
       // print '<div class="forskningsservice-informed">';
       // print render($content['field_forskningsservice_informed']);
       // print '</div>';
        // OK from Uno

        /* Temporarily disable Request to Vice head. Will re-think it later.
        Probably it can be enabled back just for Economy/Unit, but not for researchers */

        /*
        print '<div class="request-to-vice-head">';
            print '<span class="field-label">Request to Vice head: </span>';
            $haspermission = '';
            if ($admin || $user->uid == $node->uid || $economy || $unithead) {
                $haspermission = ' haspermission';
            }
            if (isset($node->field_request_to_vice_head['und'][0]['value']) && $node->field_request_to_vice_head['und'][0]['value']) {
                print '<span class="approved">Sent</span>';
            } else if (!$cancelled) {
                print '<a href="node/approve/'.$node->nid. '" class="approve request-vice-head'.$haspermission.'">Send</a>';
                print '<span class="not-approved hidden'.$haspermission.'">Not sent</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve request-vice-head hidden'.$haspermission.'">Send</a>';
                print '<span class="not-approved'.$haspermission.'">Not sent</span>';
            }
        print '</div>';
        */
        
        print '<div class="ok-from-vice-head">';
            print '<span class="field-label">OK from Vice head: </span>';
            $haspermission = '';
            if ($admin || $vicehead) {
                $haspermission = ' haspermission';
            }
            if ($node->field_ok_from_uno['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $vicehead) && !$cancelled) {
                print '<a href="node/approve/'.$node->nid. '" class="approve vice-head'.$haspermission.'">Approve</a>';
                print '<span class="not-approved hidden'.$haspermission.'">No</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve vice-head hidden'.$haspermission.'">Approve</a>';
                print '<span class="not-approved'.$haspermission.'">No</span>';
            }
        print '</div>';

        // Sent to Birgitta a.k.a. Final submissions
        print '<div class="final-submissions">';
            print '<span class="field-label">Final submission: </span>';
            $haspermission = '';
            if ($admin || $vicehead) {
                $haspermission = ' haspermission';
            }
            if ($node->field_sent_to_birgitta_o['und'][0]['value']) {
                print '<span class="approved">Sent</span>';
            } else if (($admin || $secretary) && !$cancelled) {
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve final'.$haspermission.'">Send</a>';
                print '<span class="not-approved hidden'.$haspermission.'">Not sent</span>';
            } else {
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve final hidden'.$haspermission.'">Sent</a>';
                print '<span class="not-approved'.$haspermission.'">Not sent</span>';
            }
        print '</div>';

        print '<div class="decision-date">';
            print render($content['field_decision_date']);
        print '</div>';

        // Final approval
        print '<div class="approved-funding">';
            print '<span class="field-label">Final funding granted: </span>';
            $haspermission = '';
            if ($admin || $vicehead) {
                $haspermission = ' haspermission';
            }
            if (isset($node->field_approved_funding['und'][0]['value'])
                && $node->field_approved_funding['und'][0]['value'] == 1) {
                print '<span class="approved">Yes</span>';
            } else if (isset($node->field_approved_funding['und'][0]['value']) && $node->field_approved_funding['und'][0]['value'] == 0) {
                print '<span class="not-approved">No</span>';
            } else if (($admin || $vicehead || $user->uid == $node->uid) && !$cancelled) {
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve funding-yes'.$haspermission.'">Yes</a>';
                print ' ';
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve funding-no'.$haspermission.'">No</a>';
                print '<span class="not-approved hidden'.$haspermission.'">No</span>';
            } else {
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve funding-yes hidden'.$haspermission.'">Yes</a>';
                print ' ';
                print '<a href="'.$base_url.'/'.'node/approve/'.$node->nid. '" class="approve funding-no hidden'.$haspermission.'">No</a>';
                print '<span class="not-approved'.$haspermission.'">No</span>';
            }
        print '</div>';
        print '</div>';

        // End of right-section
        // -------------------------------

      ?>
    </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>
  <?php 
        // If user has permissions to edit this node, show edit button
        if ($editable) {
            print '<a href="'.$base_url.'/'.'node/' . $node->nid . '/edit" class="edit '.$cancelledclass.'">Edit / reply</a>';
        }

    print '<span class="lastedited">Last edited by ' . $lasteditor->realname . ' on ' . format_date($lastrevision->timestamp, 'utan_tider').'</span>';
  ?>

</div>

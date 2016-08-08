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
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; if ($node->field_cancelled['und'][0]['value']) { print ' dimmed';} ?> clearfix"<?php print $attributes; ?>>

  <?php
  // Find out if user can edit this question
  $editable = false;
  $economy = false;
  $unithead = false;
  $vicehead = false;
  $admin = false;
  $cancelled = false;

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
    $editable = true;

  } else if (isset($user->roles[7])) {
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
  ?>

  <div class="proposal-header">
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><?php print $title;
      if ($node->field_cancelled['und'][0]['value']) {
        if (!$admin) {
            $editable = false;
        }
        print ' (Cancelled)';
        $cancelled = true;
        $cancelledclass = ($cancelled ? 'hidden' : '');
      } ?></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <!-- Node author -->
    <div class="author">
      <?php print '<span class="field-label">Researcher: </span>' . $name;
      //print render($content['field_dsv_person_in_charge']); ?>
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
            if ($node->field_economy_owner['und'][0]['uid']) {
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
        if ($cancellable) {
            if ($cancelled) {
                print '<br><a href="node/cancel/' . $node->nid . '" class="cancel cancelled">
                    <img src="'.drupal_get_path('theme', 'projectproposals_theme').'/images/reload.png'.'" title="Uncancel proposal" alt="Uncancel this proposal"></a>';
            } else {
                print '<br><a href="node/cancel/' . $node->nid . '" class="cancel">
                    <img src="'.drupal_get_path('theme', 'projectproposals_theme').'/images/cancel.png'.'" title="Cancel proposal" alt="Cancel this proposal"></a>';
            }
        }
    ?>

    <?php
    if ($economy || $admin) {
        if ($user->uid !== $node->field_economy_owner['und'][0]['uid']) {
            print '<a href="node/economy-own/' . $node->nid . '" class="economy-owner not-owned '. $cancelledclass .'">
                <img src="'.drupal_get_path('theme', 'projectproposals_theme').'/images/user-include.png'.'" title="Assign the proposal to me" alt="Assign the proposal to me"></a>';
            } else {
                print '<a href="node/economy-own/' . $node->nid . '" class="economy-owner owned '. $cancelledclass .'">
                    <img src="'.drupal_get_path('theme', 'projectproposals_theme').'/images/user-exclude.png'.'" title="Unassign the proposal from the current economy person" alt="Unassign the proposal from the current economy person"></a>';
            }
    } 
    ?>

  </div>

    <div class="content" <?php print $content_attributes; ?>>
      <?php
        // We hide the comments and links now so that we can render them later.
        hide($content['comments']);
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

        // Program/Call/Target
        print '<div class="program-call-target">';
        print render($content['field_program_call_target']);
        print '</div>';

        print '</div>';
        // End first row
        // -------------------------------


        // Second row
        // -------------------------------
        print '<div class="second-row">';

        // Co-financing needed
        print '<div class="cofinancing-needed">';
        print render($content['field_co_financing_needed']);
        print '</div>';

        // Co-financing covered by
        print '<div class="cofinancing-covered-by">';
        print render($content['field_co_financing_covered_by']);
        print '</div>';

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
        print '<div class="fourth-row">';

        // We only show Attachments/Comments for authors or editors.
        if ($editable) {

            // Hide attachment to Unit head when Final attachments are uploaded.
            if (!$node->field_final_attachments['und']) {
                print '<div class="unit">';
                    print '<div class="comments-unit">';
                    print render($content['field_comments_to_unit_head']);
                    print '</div>';
                    print '<div class="attachments-unit">';
                    print render($content['field_attachment_unit']);
                    print '</div>';
                print '</div>';
            }

            print '<div class="dsv-economy">';
                print '<div class="comments-dsv-economy">';
                print render($content['field_comments_to_dsv_economy']);
                print '</div>';
                print '<div class="attachments-dsv-economy">';
                print render($content['field_attachment_to_dsv_economy']);
                print '</div>';
            print '</div>';

            print '<div class="vice-head">';
                print '<div class="comments-vice-head">';
                print render($content['field_comments_to_vice_head']);
                print '</div>';
                print '<div class="attachments-vice-head">';
                print render($content['field_attachments_to_vice_head']);
                print '</div>';
            print '</div>';

            print '<div class="final-attachments">';
            print render($content['field_final_attachments']);
            print '</div>';

        print '<span class="note">Proposal tips: it has to be approved by Unit head, after that it can be filled in with details and sent to DSV economy.</span>';

        }

        print '</div>';

        // End of fourth row
        // -------------------------------


        // Right section
        // -------------------------------
        print '<div class="right-section">';

        // OK from Unit head
        print '<div class="ok-from-unit-head">';
            print '<span class="field-label">OK from Unit head: </span>';
            //print render($content['field_ok_from_unit_head']);
            if ($node->field_ok_from_unit_head['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $unithead) && !$cancelled) {
                print '<a href="node/approve/'.$node->nid. '" class="approve unit-head">Approve</a>';
                print '<span class="not-approved hidden">No</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve unit-head hidden">Approve</a>';
                print '<span class="not-approved">No</span>';
            }
        print '</div>';

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
        print '</div>';

        // OK from DSV Economy
        print '<div class="ok-from-dsv-economy">';
            print '<span class="field-label">OK from DSV economy: </span>';
            if ($node->field_ok_from_dsv_economy['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $economy) && !$cancelled
                    && $node->field_ok_from_unit_head['und'][0]['value']
                    && $node->field_request_to_dsv_economy['und'][0]['value']) {
                print '<span class="not-approved hidden">No</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve dsv-economy">Approve</a>';
            } else {
                print '<span class="not-approved">No</span>';
                print '<a href="node/approve/'.$node->nid. '" class="approve dsv-economy hidden">Approve</a>';
            }
        print '</div>';

        // Forskningsservice informed
       // print '<div class="forskningsservice-informed">';
       // print render($content['field_forskningsservice_informed']);
       // print '</div>';
        // OK from Uno

        print '<div class="request-to-vice-head">';
            print '<span class="field-label">Request to Asa: </span>';
            if ($node->field_request_to_vice_head['und'][0]['value']) {
                print '<span class="approved">Sent</span>';
            } else if (($admin || $user->uid == $node->uid) && !$cancelled
                    && $node->field_ok_from_dsv_economy['und'][0]['value']) {
                print '<a href="node/approve/'.$node->nid. '" class="approve request-vice-head">Send</a>';
                print '<span class="not-approved hidden">Not sent</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve request-vice-head hidden">Send</a>';
                print '<span class="not-approved">Not sent</span>';
            }
        print '</div>';
        
        print '<div class="ok-from-vice-head">';
            print '<span class="field-label">OK from Asa: </span>';
            if ($node->field_ok_from_uno['und'][0]['value']) {
                print '<span class="approved">Yes</span>';
            } else if (($admin || $vicehead) && !$cancelled
                    && $node->field_ok_from_dsv_economy['und'][0]['value']
                    && $node->field_request_to_vice_head['und'][0]['value']) {
                print '<a href="node/approve/'.$node->nid. '" class="approve vice-head">Approve</a>';
                print '<span class="not-approved hidden">No</span>';
            } else {
                print '<a href="node/approve/'.$node->nid. '" class="approve vice-head hidden">Approve</a>';
                print '<span class="not-approved">No</span>';
            }
        print '</div>';

        // Sent to Birgitta a.k.a. Final submissions
        print '<div class="final-submissions">';
            if ($node->field_sent_to_birgitta_o['und'][0]['value']) {
                print '<span class="field-label">Final submission:</span> <span class="approved">Yes</span>';
            } else {
                print '<span class="field-label">Final submission:</span> <span class="not-approved">No</span>';
            }
        print '</div>';

        // If user has permissions to edit this node, show edit button
        if ($editable) {
            print '<a href="node/' . $node->nid . '/edit" class="edit '.$cancelledclass.'">Edit</a>';
        }

        print '</div>';
        // End of right-section
        // -------------------------------

      ?>
    </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>

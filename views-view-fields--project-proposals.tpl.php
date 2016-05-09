<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>

<?php foreach ($fields as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <?php
    if (!strcmp($field->label, 'Title')) {
      // Insert header div
      print '<div class="proposal-header-lol">TEST';

    } else if (!strcmp($field->label, 'Is DSV coordinating')) {
      // Insert content div
      print '<div class="proposal-content">';
      // Insert first row div
      print '<div class="first-row">';

    } else if (!strcmp($field->label, 'Co-financing needed')) {
      // Insert second row div
      print '<div class="second-row">';

    } else if (!strcmp($field->label, 'Funding organization')) {
      // Insert third row div
      print '<div class="third-row">';

    } else if (!strcmp($field->label, 'OK from DSV economy')) {
      // Insert right section div
      print '<div class="right-section">';
    }
  ?>

  <?php
    if (!strcmp($field->label, 'Currency DSV budget') || !strcmp($field->label, 'Currency complete project')) {
      // Don't print these...
    } else {
      print $field->wrapper_prefix;
      print $field->label_html;
      print $field->content;

      // Add currency if needed
      if (!strcmp($field->label, 'Budget amount for DSV')) {
        foreach ($fields as $id => $infield) {
          if (!strcmp($infield->label, 'Currency DSV budget')) {
            print $infield->content;
          }
        }
      } else if (!strcmp($field->label, 'Budget amount for complete project')) {
        foreach ($fields as $id => $infield) {
          if (!strcmp($infield->label, 'Currency complete project')) {
            print $infield->content;
          }
        }
      }

      print $field->wrapper_suffix;
    }
  ?>

  <?php
    if (!strcmp($field->label, 'Duration')) {
      // Close row-header div
      print "</div>";

    } else if (!strcmp($field->label, 'Program/Call/Target')) {
      // Close first row div
      print '</div>';

    } else if (!strcmp($field->label, 'Percent OH costs covered')) {
      // Close second row div
      print '</div>';

    } else if (!strcmp($field->label, 'Currency complete project')) {
      // Close third row div
      print '</div>';

    } else if (!strcmp($field->label, 'Edit')) {
      // Close right section div
      print '</div>';
      // Close content div
      print '</div>';
    }
  ?>

<?php endforeach; ?>

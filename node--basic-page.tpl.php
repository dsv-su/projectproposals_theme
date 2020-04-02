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
?>


<?php 
/**********************************************
*******REWORK IN A SEPARATE MODULE!!!!!********
**********************************************/

//fetch data
$nodes = node_load_multiple(array(), array('type' => 'project_proposal'));

$table = array(); 
$table['cols'] = array(
    array('label' => 'Year', 'type' => 'string'),
    array('label' => 'Created', 'type' => 'number'),
    array('label' => 'Ok from Economy & Jelena', 'type' => 'number'),    
    array('label' => 'Sent to Registrator', 'type' => 'number'),
    array('label' => 'Funded', 'type' => 'number')
);

$table_units = array(); // 1st - created, 2nd - funded
$table_units['cols'] = array(
    array('label' => 'Year', 'type' => 'string'),
    array('label' => 'eGovLab', 'type' => 'number'),
    array('label' => 'IDEAL', 'type' => 'number'),
    array('label' => 'SAS', 'type' => 'number'),
    array('label' => 'IS', 'type' => 'number'),
    array('label' => 'ACT', 'type' => 'number'),
    array('label' => 'SPIDER', 'type' => 'number')
);

$table_funds = array();
$table_funds['cols'] = array(
    array('label' => 'Year', 'type' => 'string'),
    array('label' => 'Funds requested (M Sek)', 'type' => 'number'),
    array('label' => 'Funds granted (M Sek)', 'type' => 'number')
);

$rows = array();
$years = array();
$funded_by_year = array();
$created_by_year = array();
$submitted_by_year = array();
$submitted_and_approved = array();
$funds_asked = array();
$funds_granted = array();
$unit = array();
$byarea = array();
$amountarea = array();
$byunit = array();
$amountunit = array();

$unitnamefromid = array(344=>'eGovLab',247=>'IDEAL',191=>'SAS',174=>'IS',65=>'ACT', 170=>'IS', 416=>'SPIDER', 372=>'SPIDER', 128=>'IDEAL', 256 => 'SAS', 191 => 'eGovLab');
$a = array();
foreach ($nodes as $nid => $node) {
    $deadline = $node->field_deadline[LANGUAGE_NONE][0]['value'];
    $unitid = $node->field_unit_head[LANGUAGE_NONE][0]['uid'];
//    $year = date('Y', strtotime($deadline));
    $year = date('Y', $node->created);
    if ($year <2017 or $year >2018 or $node->created<1489653038) {continue;}
    if ($node->field_cancelled[LANGUAGE_NONE][0]['value']) {continue;}
    if (/*$node->field_approved_funding[LANGUAGE_NONE][0]['value'] &&*/
        $node->field_ok_from_dsv_economy[LANGUAGE_NONE][0]['value'] &&
        $node->field_ok_from_uno[LANGUAGE_NONE][0]['value']) 
    {
        if ($node->field_approved_funding[LANGUAGE_NONE][0]['value']) {
            $funded_by_year[$year] += 1;
            if ($node->field_currency['und'][0]['value']=='€') {
                $funds_granted[$year]+=$node->field_total_budget_amount_for_ds['und'][0]['value']*10;
            } elseif ($node->field_currency['und'][0]['value']=='kr'){
                $funds_granted[$year]+=$node->field_total_budget_amount_for_ds['und'][0]['value'];
            }
                    print $year.' '.$node->title.' ('.$tempuser->realname.'): <b>'.$node->field_total_budget_amount_for_ds['und'][0]['value'].' '.$node->field_currency['und'][0]['value'].'</b><br>';
        }
        $tempuser = user_load($node->field_dsv_person_in_charge['und'][0]['uid']);
               // print $tempuser->realname;
    //    print $year.' '.$node->title.' ('.$tempuser->realname.'): <b>'.$node->field_total_budget_amount_for_ds['und'][0]['value'].' '.$node->field_currency['und'][0]['value'].'</b><br>';

        $area = taxonomy_term_load($node->field_research_areas[LANGUAGE_NONE][0]['tid'])->name;
        //var_dump($area);
        $byarea[$year][$area]++;
        $byunit[$year][$unitnamefromid[$unitid]]++;
        if ($node->field_currency['und'][0]['value']=='€') {
            $amountarea[$year][$area]+=$node->field_total_budget_amount_for_ds['und'][0]['value']*10;
            $amountunit[$year][$unitnamefromid[$unitid]]+=$node->field_total_budget_amount_for_ds['und'][0]['value']*10;
        } elseif ($node->field_currency['und'][0]['value']=='kr'){
            $amountarea[$year][$area]+=$node->field_total_budget_amount_for_ds['und'][0]['value'];
            $amountunit[$year][$unitnamefromid[$unitid]]+=$node->field_total_budget_amount_for_ds['und'][0]['value'];
        }

    }

    if ($node->field_sent_to_birgitta_o[LANGUAGE_NONE][0]['value']) {
        $submitted_by_year[$year] += 1;
    }

    if ($node->field_ok_from_dsv_economy[LANGUAGE_NONE][0]['value'] && $node->field_ok_from_uno[LANGUAGE_NONE][0]['value']) {
        $submitted_and_approved[$year] += 1;
        if ($node->field_currency['und'][0]['value']=='€') {
            $funds_asked[$year]+=$node->field_total_budget_amount_for_ds['und'][0]['value']*10;
        } elseif ($node->field_currency['und'][0]['value']=='kr'){
            $funds_asked[$year]+=$node->field_total_budget_amount_for_ds['und'][0]['value'];
        }
    }
    $created_by_year[$year]+= 1;
    //var_dump($unitid);
    $a[] = $unitid;
    if ($unitnamefromid[$unitid]) {
        $unit[$year][$unitnamefromid[$unitid]] += 1;
    }
}
//var_dump($byarea);
//var_dump($amountarea);

print "<h3>Categorised by research areas (2018)</h3>";
foreach ($byarea[2018] as $area => $value) {
    $areaname = $area ? $area : 'Unknown';
    print "Area: $areaname </br>Number of proposals: <b>$value</b> </br>Budget for DSV: <b>".number_format($amountarea[2018][$area])."</b> SEK</br></br>";
}

/*foreach ($byunit[2018] as $unit => $value) {
    print "Unit: $unit </br>Number of proposals: $value </br>Budget for DSV: ".number_format($amountunit[2018][$unit])." SEK</br></br>";
}*/
//var_dump(array_sum($amountunit));
//var_dump(array_sum($amountarea));

print "<h3>Overall for DSV</h3>";
print "<br>Total approved in 2018: ".array_sum($byunit[2018]). " proposals and ".number_format(array_sum($amountunit[2018]))." kr";
print "<br>Total granted in 2018: ".$funded_by_year[2018]." proposals and ".number_format($funds_granted[2018])." kr";
print "<br<br><br>";
print "<br>Total approved in 2017: ".array_sum($byunit[2017]). " proposals and ".number_format($funds_asked[2017])." kr";
print "<br>Total granted in 2017: ".$funded_by_year[2017]." proposals and ".number_format($funds_granted[2017])." kr<br>";


 print "</br></br>";
 print "<h3>Units comparison</h3>";
foreach (array_keys($byunit[2018]) as $unit) {
    print "Unit: $unit </br>
    2017:</br>
    Proposals: <b>".(int)$byunit[2017][$unit]."</b> </br>
    Budget for DSV: <b>".number_format($amountunit[2017][$unit])."</b> SEK</br>
    2018:</br>
    Proposals: <b>".$byunit[2018][$unit]."</b></br>
    Budget for DSV: <b>".number_format($amountunit[2018][$unit])."</b> SEK</br></br>";
}

$a = array_unique($a);
//var_dump($a);
ksort($created_by_year);

foreach ($created_by_year as $year => $value) {
  //  if ($year <> 2018) {continue;}
    $temp = array();
    $temp[] = array('v' => (int) $year);
    $temp[] = array('v' => (int) $created_by_year[$year]);
    $temp[] = array('v' => (int) $submitted_and_approved[$year]);
    $temp[] = array('v' => (int) $submitted_by_year[$year]);
    $temp[] = array('v' => (int) $funded_by_year[$year]);
    $rows[] = array('c' => $temp);
    
}

$table['rows'] = $rows;
$jsonTable = json_encode($table);

$rows=array();
foreach ($funded_by_year as $year => $value) {
    print($funds_asked[$year].'kr<br>');
    print($funds_granted[$year].'kr<br><br>');
    $temp = array();
    $temp[] = array('v' => (int) $year);
    $temp[] = array('v' => (int) $funds_asked[$year]/1000000);
    $temp[] = array('v' => (int) $funds_granted[$year]/1000000);
    $rows[] = array('c' => $temp);
}



$table_funds['rows'] = $rows;
$jsonTable_funds = json_encode($table_funds);
var_dump($jsonTable_funds);

foreach ($created_by_year as $year => $value) {
    $temp = array();
    $temp[] = array('v' => (int) $year);
    $temp[] = array('v' => (int) $unit[$year]['eGovLab']);
    $temp[] = array('v' => (int) $unit[$year]['IDEAL']);
    $temp[] = array('v' => (int) $unit[$year]['SAS']);
    $temp[] = array('v' => (int) $unit[$year]['IS']);    
    $temp[] = array('v' => (int) $unit[$year]['ACT']);
    $temp[] = array('v' => (int) $unit[$year]['SPIDER']);
    $rows_units[] = array('c' => $temp);


 //   $temp[] = array('v' => (string) $value);
 //   $temp[] = array('v' => (int) $unit[$unitnamefromid[$unitid]]);
 //   $rows_units[] = array('c' => $temp);

}

$table_units['rows'] = $rows_units;
$jsonTable_units = json_encode($table_units);

?>

   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
     google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var options = {
          //title: 'Company Performance',
         // curveType: 'function',
         pieHole: 0.4,
          legend: { position: 'bottom' }
        };

        var data = new google.visualization.DataTable(<?php echo $jsonTable_funds?>);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2, { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" }]);

        var chart = new google.visualization.ColumnChart(document.getElementById('bar_funding'));
        chart.draw(view, options);

        var data = new google.visualization.DataTable(<?php echo $jsonTable?>);
              var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2, { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" },
                         3, { calc: "stringify",
                         sourceColumn: 3,
                         type: "string",
                         role: "annotation" },
                         4, { calc: "stringify",
                         sourceColumn: 4,
                         type: "string",
                         role: "annotation" }]);
        var chart = new google.visualization.BarChart(document.getElementById('bar_chart'));
        chart.draw(view, options);

        var data = new google.visualization.DataTable(<?php echo $jsonTable_units?>);
        var chart = new google.visualization.ColumnChart(document.getElementById('pie_chart'));
        chart.draw(data, options);

      }
    </script>
    <h2>Funding requested and approved</h2>
    <div id="bar_funding" style="width: 900px; height: 500px;"></div>
    <h2>Proposals per year (created, approved, submitted, funded)</h2>
    <div id="bar_chart" style="width: 900px; height: 500px;"></div>
    <h2>Proposals created by Unit</h2>
    <div id="pie_chart" style="width: 1000px; height: 500px;"></div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>

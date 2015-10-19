<?php

ini_set('display_errors','On');
//ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

// require_once("../../../config.php");

//require_once("/usr/local/www/apache22/data/moodle_ii.npu.edu.ua_moodle/www/config.php");
//require_once("/usr/local/www/apache24/data/moodle_moodle.ii.npu.edu.ua/www/config.php");
require_once("../../../../config.php");
//if( !is_siteadmin($USER->id) ){die;}

$title = 'Відвідування';
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('base');
$PAGE->navbar->add($title);

require_once('create_form.php');
$mform = new simplehtml_form();

if ($mform->is_cancelled())
{
	redirect($CFG->wwwroot);
} else if ($fromform = $mform->get_data())
{
	require_once("lib.php");

	$table = new html_table();
	$table->head = $hd;
	$table->data = $Adt;

} else
{
	$mform->set_data();
}

echo $OUTPUT->header();


/*******************************************************************************************************************/
$quiz_reordertool = optional_param( 'link',  '', PARAM_TEXT );//optional_param('link', -1, PARAM_BOOL);
// Print the tabs to switch mode.

switch ( $quiz_reordertool )
{
	case "M":
		$currenttab = 'M';
	break;
	default:
		$currenttab = 'B';
	break;
}
//-------------
//$thispageurl
//-------------
$tabs = array(array(
    new tabobject('M', new moodle_url("../M/",
            array('link' => "M")), "Відомість"/*get_string('mijsesiyniy', 'edekanat')*/),

    new tabobject('B', new moodle_url("../B/",
            array('link' => "B")), "Відвідування"/*get_string('vidviduvannya', 'edekanat')*/)
));

print_tabs($tabs, $currenttab);
/******************************************************************************************************************/

$mform->display();
	if( isset($table) )
	{
		echo html_writer::table($table);
	}

echo $OUTPUT->footer();

?>
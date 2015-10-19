<?php
//require_once("/usr/local/www/apache22/data/moodle_ii.npu.edu.ua_moodle/www/config.php");
require_once("../../../../config.php");

//require_once("../../../config.php");
//http://docs.moodle.org/dev/Access_API
/*
if( is_siteadmin($USER->id) )
{
	echo "Yes";
}
else
{
	echo "No";
}
*/
//$refno = optional_param('refno', PARAM_INT);
//required_param



//$numSemestr = optional_param('numSemestr', PARAM_INT);
//$nameGroup  = optional_param('nameGroup',  PARAM_INT);

if($refno>0)
{
	echo $refno;
	die;
}


/*
$courseid=970;
if (!$course = $DB->get_record('course', array('id'=>$courseid)))
{
	print_error('Site is misconfigured');
}
$context = get_context_instance(CONTEXT_COURSE, $course->id);
*/
//require_login($courseid);

/// Otherwise fill and print the form.
$title = 'Відомість';
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('base');
$PAGE->navbar->add($title);
//$PAGE->set_url('/systems/phones/index.php');

require_once('create_form.php');

//Instantiate simplehtml_form 
$mform = new simplehtml_form();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
//Handle form cancel operation, if cancel button is present on form
redirect($CFG->wwwroot); //redirect home page

} else if ($fromform = $mform->get_data()) {
//In this case you process validated data. $mform->get_data() returns data posted in form.
//$toform = new stdClass();
//$toform->numSemestr = $fromform->numSemestr;
//$toform->nameGroup  = $fromform->nameGroup;
//$toform->ShowOrDown = $fromform->ShowOrDown;
//$toform->closedate  = $fromform->closedate;


/**********************************************************************************************/
/**/$semestr=$fromform->numSemestr;															/**/
/**/$group=$fromform->nameGroup;															/**/
/**/ //$DtArr=null;																			/**/		
/**/																						/**/				
/**/
	include_once("down2.php");	
	
	if($fromform->ShowOrDown=="download"){													/**/
	doc_Download($DtArr);																	/**/
	//echo "hello";die;	
}
else{

/*
echo "<pre>";
print_r( $DtArr );
echo "</pre>";
*/

$hd[]='student';
foreach($DtArr as $k=>$v)
{
	$hd[]=$k;
}

foreach($DtArr as $k=>$v)
{
	for( $i=0;$i<sizeof($v);$i++ )
	{
		$Adt[$i]=array($v[$i]->student);
		//array_push($Adt[$i], $v[$i]->grade);
	}
}
/*************************************************************/
foreach($DtArr as $k=>$v)
{
	for( $i=0;$i<sizeof($v);$i++ )
	{
		array_push($Adt[$i], $v[$i]->grade);
	}
}

/*
echo "<pre>***************";
print_r( $Adt );
echo "</pre>";
*/

	$table = new html_table();
	$table->head = $hd;
	$table->data = $Adt;

}																							/**/	
/**********************************************************************************************/

//$options = array('subdirs'=>1, 'maxbytes'=>$CFG->userquota, 'maxfiles'=>-1, 'accepted_types'=>'*', 'return_types'=>FILE_INTERNAL);
//$toform = file_postupdate_standard_filemanager($toform, 'files', $options, $context, 'user', 'private', 0);

//$DB->insert_record('systems_jobs', $toform);

} else {
// this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
// or on the first display of the form.

//Set default data (if any)

$mform->set_data();
//displays the form
}

echo $OUTPUT->header();

/*******************************************************************************************************************/
$quiz_reordertool = optional_param( 'link',  '', PARAM_TEXT );
// optional_param('link', -1, PARAM_BOOL);
// Print the tabs to switch mode.

switch ( $quiz_reordertool )
{
	case "B":
		$currenttab = 'B';
	break;
	default:
		$currenttab = 'M';
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
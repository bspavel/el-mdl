<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');

class simplehtml_form extends moodleform {
//Add elements to form
function definition() {
global $CFG, $DB;

$mform = $this->_form; // Don't forget the underscore! 

$semestr=array
(
	'01 семестр'=>'01 семестр',
	'02 семестр'=>'02 семестр',
	'03 семестр'=>'03 семестр',
	'04 семестр'=>'04 семестр',
	'05 семестр'=>'05 семестр',
	'06 семестр'=>'06 семестр',
	'07 семестр'=>'07 семестр',
	'08 семестр'=>'08 семестр',
	'09 семестр'=>'09 семестр',
	'10 семестр'=>'10 семестр',
	'11 семестр'=>'11 семестр',
	'12 семестр'=>'12 семестр'
);
$mform->addElement('select', 'numSemestr', "Семестр"/*get_string('semesrt', 'block_newblock')*/, $semestr);
//$mform->getElement('md_skills')->setMultiple(true);
// This will select the skills A and B.
//$mform->getElement('numSemestr')->setSelected(array('val1', 'val2'));
$mform->getElement('numSemestr')->setSelected('01 семестр');

/*********************************************************************************************************/

$grp=array();
$sql="SELECT `coh`.`name` AS `group` ".
"FROM `mdl_cohort` AS `coh` ".
"WHERE `coh`.`name` like \"20%\" ".
"ORDER BY `coh`.`name` ASC";

if ($grps_tmp = $DB->get_records_sql($sql))
{
    foreach ($grps_tmp as $k => $v)
	{
		$grp[$k] = $v->group;
	}
}
unset($grps_tmp);unset($k);unset($v);

$mform->addElement('select', 'nameGroup', "Група"/*get_string('group', 'block_newblock')*/, $grp);
//$mform->getElement('nameGroup')->setSelected('01 семестр');

/*********************************************************************************************************/
$list=array
(
	'show'=>'Показати',
	'download'=>'Завантажити'
);

$mform->addElement('select', 'ShowOrDown', "Показати/Завантажити"/*get_string('ShowOrDown', 'block_newblock')*/, $list);


/*
$mform->addElement('date_selector', 'strdt', 'Start Date:', array(
    'startyear' => 2014, 
    'stopyear'  => 2019
));

$mform->addElement('date_selector', 'enddt', 'End Date:', array(
    'startyear' => 2014, 
    'stopyear'  => 2019
));
*/
$this->add_action_buttons($cancel = false, $submitlabel="Генерація");
}
}
?>
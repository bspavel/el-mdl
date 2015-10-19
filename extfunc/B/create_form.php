<?php defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');

require_once($CFG->libdir.'/formslib.php');
class simplehtml_form extends moodleform {
function definition() {
global $CFG, $DB;

$mform = $this->_form;

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
$mform->addElement
				(
					'select',
					'numSemestr',
					"Семестр"/*get_string('semesrt', 'block_newblock')*/,
					$semestr
				);

$mform->getElement('numSemestr')->setSelected('01 Семестр');

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

$mform->addElement
				(
					'select',
					'nameGroup',
					"Група"/*get_string( 'group', 'block_newblock')*/,
					$grp
				);

$mform->addElement('date_selector', 'strdt', 'Start Date:', array(
    'startyear' => 2010, 
    'stopyear'  => 2019
));

$mform->addElement('date_selector', 'enddt', 'End Date:', array(
    'startyear' => 2010, 
    'stopyear'  => 2019
));

$this->add_action_buttons($cancel = false, $submitlabel="Генерація");
}
}

?>
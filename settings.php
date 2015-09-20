<?php defined('MOODLE_INTERNAL') || die();
global $DB, $CFG;
$settings->add
    (
        new admin_setting_configtext
        (
            'block_vidomist_deptboss',
            get_string('bossname0', 'block_vidomist'),
            get_string('bossname1', 'block_vidomist'),
            get_string('defaultbossname', 'block_vidomist'),
            PARAM_TEXT
        )
    );
//--------------------------------------------------------------
$settings->add
(
    new admin_setting_configtext
    (
        'block_vidomist_pc',
        get_string('pc0', 'block_vidomist'),
        get_string('pc1', 'block_vidomist'),
        get_string('defaultpc', 'block_vidomist'),
        PARAM_TEXT
    )
);
$settings->add
(
    new admin_setting_configtext
    (
        'block_vidomist_sc',
        get_string('sc0', 'block_vidomist'),
        get_string('sc1', 'block_vidomist'),
        get_string('defaultsc', 'block_vidomist'),
        PARAM_TEXT
    )
);
//-------------------------------------------------------------
$sql="SELECT `id`, `name`
        FROM
            {$CFG->prefix}course_categories
        WHERE
            `parent` =  '0'
        AND
            `visible`=1
        ORDER by `name` ASC";
unset($options);unset($tmp);unset($v);
$options=array();

if ($tmp = $DB->get_records_sql($sql))
{
    foreach ($tmp as $v)
    {
        $options[$v->id] = $v->name;
    }
}
$settings->add
    (
        new admin_setting_configselect
        (
            'block_vidomist_cohortlist',
            get_string('nazva1_cohort', 'block_vidomist'),
            get_string('nazva2', 'block_vidomist'),
            '50',
            $options
        )
    );
$settings->add
(
    new admin_setting_configselect
    (
        'block_vidomist_speclist',
        get_string('nazva3_spec', 'block_vidomist'),
        get_string('nazva4', 'block_vidomist'),
        '50',
        $options
    )
);
unset($options);unset($tmp);unset($v);
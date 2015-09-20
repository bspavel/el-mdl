<?php
class block_vidomist_edit_form extends block_edit_form {
    protected function specific_definition($mform) {global $DB, $CFG;
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
//        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_vidomist'));
		
//		$mform->addElement('header','general', get_string('pluginname', 'block_vidomist'));
        $mform->addElement('text', 'config_hour', get_string('custominstancename', 'block_vidomist'));
        $mform->setType('config_hour', PARAM_MULTILANG);		
		$mform->addElement('text', 'config_ects', get_string('custominstancename1', 'block_vidomist'));
        $mform->setType('config_ects', PARAM_MULTILANG);		
//		$mform->addElement('text', 'config_spec', get_string('custominstancename2', 'block_vidomist'));
//      $mform->setType('config_spec', PARAM_MULTILANG);
			$catgr=array();
			$sql=
			"SELECT `id`, `fullname` ".
			"FROM `{$CFG->prefix}course` ".
			"WHERE `category`=".
				(int)$CFG->block_vidomist_speclist.
			" AND ".
			" `visible`='1' ".
			"ORDER BY `fullname` ASC";

			if ($grps_tmp = $DB->get_records_sql($sql))
			{
				foreach ($grps_tmp as $v)
				{
					$catgr[$v->id] = $v->fullname;
				}
			}
			unset($grps_tmp);unset($k);unset($v);
		$mform->addElement('select', 'config_spec', get_string('custominstancename2', 'block_vidomist'), $catgr);
		$mform->setType('config_spec', PARAM_INT);
		/*--------------------------------------------*/
			$grp=array();
            $sql=
                "SELECT `id`, `name` AS `grpname` ".
                " FROM `{$CFG->prefix}cohort` ".
                " WHERE `contextid` in
                 (
                    SELECT
                        `id`
                    FROM
                        `{$CFG->prefix}context`
                    WHERE
                        `contextlevel`='40'
                    AND
                        `instanceid`=".(int)$CFG->block_vidomist_cohortlist."
                 )
                ".
                " AND ".
                " `visible`='1' ".
                " ORDER by `name` ASC";

			if ($grps_tmp = $DB->get_records_sql($sql))
			{
				foreach ($grps_tmp as $v)
				{
					$grp[$v->id] = $v->grpname;
				}
			}
			unset($grps_tmp);unset($k);unset($v);
		$mform->addElement('select', 'config_grp', get_string('custominstancename3', 'block_vidomist'), $grp);
		$mform->setType('config_grp', PARAM_INT);
		/*--------------------------------------------*/
		/*$mform->addElement('select', 'config_formnavch', get_string('formnavch', 'block_vidomist'),
		array(
			"denna"  => get_string('denna', 'block_vidomist'),
			"zaochna"  => get_string('zaochna', 'block_vidomist')
		));
		$mform->setType('config_formnavch', PARAM_TEXT);
		--------------------------------------------*/
        $options = array(ENROL_INSTANCE_ENABLED  => get_string('exam', 'block_vidomist'),
                         ENROL_INSTANCE_DISABLED => get_string('zalik', 'block_vidomist'));//1
        $mform->addElement('select', 'config_status', get_string('status', 'block_vidomist'), $options);		
		
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_MULTILANG);
    }
}
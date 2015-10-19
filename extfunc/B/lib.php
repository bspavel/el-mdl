<?php defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');

$semestr=$fromform->numSemestr;
$group=$fromform->nameGroup;
$strdt=$fromform->strdt;
$enddt=$fromform->enddt;

$groupSQL=<<<eof
SELECT 
  u.`id` AS userid,
  u.`username` AS studentid,
  u.`firstname` AS firstname,
  u.`lastname` AS lastname,
  CONCAT_WS(' ', u.`lastname`, u.`firstname`) AS `student`,
  u.`email` AS email
FROM
  mdl_user AS u
WHERE
  u.id IN
	(
		SELECT userid FROM `mdl_cohort_members` AS cm
		INNER JOIN `mdl_cohort` AS coh ON coh.id=cm.cohortid
		WHERE `name`="{$group}"
	)
eof;

$sqlCourse=<<<eof
SELECT c.id AS `id`, c.`fullname` AS `crname`
FROM `mdl_course` AS c
WHERE c.id in
(
    SELECT `refcourse` AS `cid`
	FROM `mdl_subcourse`
	WHERE `id` IN
	(
		SELECT `instance`
		FROM  `mdl_course_modules`
		WHERE `module` IN
		(
			SELECT `id`
			FROM `mdl_modules` 
			WHERE `name` = 'subcourse'
		)
		AND	`section` IN
		(
			SELECT `id`
			FROM `mdl_course_sections`
			WHERE  `course` IN
			(
				SELECT `courseid` FROM `mdl_enrol`
				WHERE `customint1` IN
				(
					SELECT coh.`id` FROM `mdl_cohort` AS coh
					WHERE coh.`name`="{$group}"
				)
			)
			AND	`name` = "{$semestr}"
		)
	)
)
eof;

function vizitUsr($u, $c, $sdt, $edt)
{
	global $DB;
$sql=<<<eof
	SELECT
		 COUNT(log.`id`) AS `cnt`
	FROM
		 mdl_log AS log
	WHERE
		 log.`userid` = {$u}
	AND
		 log.`course` = {$c}
	AND ( log.`time`  BETWEEN {$sdt} AND {$edt} )
eof;

	if ( $dt = $DB->get_records_sql($sql) )
	{	$hd[]='student';
		foreach ($dt as $v)
		{
			$cnt=$v->cnt;
		}
		unset($dt);unset($v);
	}
	
	if($cnt>0){$cnt=$cnt;}else{$cnt=0;}
	return $cnt;
}

if ( $dtC = $DB->get_records_sql($sqlCourse) )
{
	$hd[]='student';
	foreach ($dtC as $v)
	{
		$hd[]=$v->crname;
	}
	unset($v);
}

if ( $dt = $DB->get_records_sql($groupSQL) )
{
	$std=null;
    foreach ($dt as $k => $v)
	{
		unset($std);
		$std = new stdClass();
		$std->userid=$v->userid;
		$std->student=$v->student;
		$grArr[]=$std;
	}
	unset($dt);unset($k);unset($v);unset($std);

	for($i=0;$i<sizeof($grArr);$i++)
	{
		$Adt[$i]=array($grArr[$i]->student);		
		foreach ($dtC as $v)
		{
			array_push
			(
				$Adt[$i],
				vizitUsr
				(
					$grArr[$i]->userid, $v->id,
					$strdt, $enddt
				)
			);
		}		
	}
}
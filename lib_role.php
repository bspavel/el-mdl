<?php
$uRoleQueryCourse="SELECT DISTINCT r.shortname
FROM {$CFG->prefix}user AS u
INNER JOIN {$CFG->prefix}role_assignments AS ra ON ra.userid = u.id
INNER JOIN {$CFG->prefix}role AS r ON ra.roleid = r.id
INNER JOIN {$CFG->prefix}context AS con ON ra.contextid = con.id
INNER JOIN {$CFG->prefix}course AS c ON c.id = con.instanceid AND con.contextlevel = 50
WHERE
c.id={$COURSE->id}
AND u.id = {$USER->id}";

$uRoleGlobal="SELECT DISTINCT r.shortname FROM `{$CFG->prefix}role_assignments`  AS ra
INNER JOIN `{$CFG->prefix}role` AS r ON ra.roleid = r.id
WHERE `userid` = {$USER->id}";

if ($sums = $DB->get_records_sql($uRoleQueryCourse))
{
    foreach ($sums as $v)
    {
        $sumRoleCourse[] = $v->shortname;
    }
}

unset($sums);
unset($v);

if ($sums = $DB->get_records_sql($uRoleGlobal))
{
    foreach ($sums as $v)
    {
        $sumRoleGlobal[] = $v->shortname;
    }
}
unset($sums);
unset($v);

$admin=is_siteadmin($USER->id);

$CrsAcs=false;
foreach($sumRoleCourse as $v)
{
    if
    (
        ($v=='manager') or
        ($v=='coursecreator') or
        ($v=='editingteacher') or
        ($v=='teacher')
    )
        {$CrsAcs=true;}
}
if(!$CrsAcs)
{
    foreach($sumRoleGlobal as $v)
    {
        if
        (
            ($admin==true) or
            ($v=='manager') or
            ($v=='coursecreator') or
            ($v=='editingteacher') or
            ($v=='teacher')
        )
            {$CrsAcs=true;}
    }
}
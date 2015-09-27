<?php
function teacherOrCC($cid,$val='teacher')
{
    global $CFG, $DB;
    $query="SELECT u.firstname, u.lastname
	FROM {$CFG->prefix}user AS u
		JOIN {$CFG->prefix}role_assignments AS ra ON ra.userid = u.id
		JOIN {$CFG->prefix}role AS r ON ra.roleid = r.id
		JOIN {$CFG->prefix}context AS con ON ra.contextid = con.id
		JOIN {$CFG->prefix}course AS c ON c.id = con.instanceid AND con.contextlevel = 50
	WHERE r.shortname='{$val}'
		AND c.id =:courseid";
    $params=array
    (
        'courseid'=>$cid
    );
    if ($sums = $DB->get_records_sql($query,$params))
    {
        foreach ($sums as $v)
        {
            $sumarray[] = $v->firstname.' '.$v->lastname;
        }
    }
    return $sumarray;
}

function getTeachersName($cid)
{
    $TEACHER=null;
    $arrVVV=teacherOrCC($cid);
    unset($v);
    $v=array();
    foreach($arrVVV as $vvv)
    {
        $v[]=$vvv;
    }
    unset($vvv);
    $cntArrV=sizeof($v);
    for($i=0;$i<$cntArrV;$i++)
    {
        if( $i<$cntArrV-1 )
        {$end=', ';}
        else
        {$end=null;}
        $TEACHER.=$v[$i].$end;
    }
    unset($v);
    unset($end);
    return $TEACHER;
}

function getSpecnm($specid)
{
    global $CFG;
    return getSQLsrt
    (
        "SELECT `fullname` AS `dt`
            FROM `{$CFG->prefix}course`
            WHERE `id`=".(int)$specid
    );
}

function getCrname($cid)
{
    global $CFG;
    return getSQLsrt
    (
        "SELECT `fullname` AS `dt`
        FROM `{$CFG->prefix}course`
        WHERE `id`=".(int)$cid
    );
}

function getDeptName($sid)
{
    global $CFG;
    return getSQLsrt
    (
        "SELECT `name` AS `dt` FROM `{$CFG->prefix}course_categories` ".
        "WHERE `id` = (SELECT `category` FROM `{$CFG->prefix}course` ".
            "WHERE `id` ='".(int)$sid."')"
    );
}

function getGROUP($gid)
{
    global $CFG;
    return getSQLsrt
    (
        "SELECT `name` AS `dt`  FROM
    `{$CFG->prefix}cohort` WHERE
    `id`=".(int)$gid
    );
}

function getSemestr($sid,$cid)
{
    global $CFG;
    $sid=(int)$sid;
    $cid=(int)$cid;
    $sqlStr=<<<eof
     SELECT CONVERT(`name`, UNSIGNED INTEGER) AS `dt`
            FROM `{$CFG->prefix}course_sections` where `course`="{$sid}"
            and `id` in
            (
                SELECT `section` FROM `{$CFG->prefix}course_modules`
                WHERE
                  `course`="{$sid}"
                  AND
                  `module` in
                    (
                          SELECT `id`  FROM `{$CFG->prefix}modules` WHERE
                         `name`="subcourse"
                    )
                    AND
                    `instance` in
                    (
                        SELECT `id`  FROM `{$CFG->prefix}subcourse` WHERE
                         `course`="{$sid}"
                         AND
                         `refcourse`="{$cid}"
                    )
            )
eof;
    return getSQLsrt($sqlStr);
}

function getSQLsrt($query)
{
    global $DB;
    if ($sums = $DB->get_records_sql($query))
    {
        foreach ($sums as $v)
        {
            return $v->dt;
        }
    }
}
function dtVariable($cid, $tchData)
{
    global $CFG, $DB;

    $pc=htmlspecialchars($CFG->block_vidomist_pc);
    $sc=htmlspecialchars($CFG->block_vidomist_sc);

    $groupListSQL="
SELECT
  CONCAT_WS(\"-\",u.id,\"ALL\")  AS `uId`,
  u.id AS `userid`,
  u.username AS `studentid`,
  u.firstname AS `firstname`,
  u.lastname AS `lastname`,
  u.email AS `email`,
  gi.grademax AS `itemgrademax`,
 'ALL' AS `flag`,
  g.finalgrade AS `finalgrade`,
  c.fullname
FROM
  {$CFG->prefix}user AS u
  INNER JOIN {$CFG->prefix}grade_grades AS g ON g.userid = u.id
  INNER JOIN {$CFG->prefix}grade_items AS gi ON g.itemid =  gi.id
  INNER JOIN {$CFG->prefix}course AS c ON c.id = gi.courseid
WHERE
  gi.courseid=:cid1
AND
u.id in
(
        SELECT
			`userid`
	FROM
			`{$CFG->prefix}cohort_members`
	WHERE
			`cohortid`=:grid1
)
AND
  gi.`itemtype`=\"course\"
UNION
SELECT
  CONCAT_WS(\"-\",u.id,\"PC\")  AS `uId`,
  u.id AS `userid`,
  u.username AS `studentid`,
  u.firstname AS `firstname`,
  u.lastname AS `lastname`,
  u.email AS `email`,
  gi.grademax AS `itemgrademax`,
  'PC' AS `flag`,
  g.finalgrade AS `finalgrade`,
  c.fullname
FROM
  {$CFG->prefix}user AS u
  INNER JOIN {$CFG->prefix}grade_grades AS g ON g.userid = u.id
  INNER JOIN {$CFG->prefix}grade_items AS gi ON g.itemid =  gi.id
  INNER JOIN {$CFG->prefix}course AS c ON c.id = gi.courseid
WHERE
  gi.courseid=:cid2
AND
u.id in
(
            SELECT
					`userid`
			FROM
					`{$CFG->prefix}cohort_members`
			WHERE
					`cohortid`=:grid2
)
AND
  gi.`itemtype`=\"category\"
AND
 g.itemid in (SELECT id  FROM `{$CFG->prefix}grade_items`
WHERE
courseid=gi.courseid AND
 iteminstance in
 (SELECT  `id` FROM `{$CFG->prefix}grade_categories` where courseid = gi.courseid AND fullname=\"{$pc}\"))
UNION
SELECT
  CONCAT_WS(\"-\",u.id,\"SC\")  AS `uId`,
  u.id AS `userid`,
  u.username AS `studentid`,
  u.firstname AS `firstname`,
  u.lastname AS `lastname`,
  u.email AS `email`,
  gi.grademax AS `itemgrademax`,
  'SC' AS `flag`,
  g.finalgrade AS `finalgrade`,
  c.fullname
FROM
  {$CFG->prefix}user AS u
  INNER JOIN {$CFG->prefix}grade_grades AS g ON g.userid = u.id
  INNER JOIN {$CFG->prefix}grade_items AS gi ON g.itemid =  gi.id
  INNER JOIN {$CFG->prefix}course AS c ON c.id = gi.courseid
WHERE
  gi.courseid=:cid3
AND
u.id in
(
            SELECT
					`userid`
			FROM
					`{$CFG->prefix}cohort_members`
			WHERE
					`cohortid`=:grid3
)
AND
  gi.`itemtype`=\"category\"
AND
 g.itemid in (SELECT id  FROM `{$CFG->prefix}grade_items`
WHERE
courseid =gi.courseid AND
 iteminstance in
 (SELECT  `id` FROM `{$CFG->prefix}grade_categories` where courseid = gi.courseid AND fullname=\"{$sc}\"))";

    $params=array
    (
        'cid1'=>$cid,
        'grid1'=>$tchData->GROUPID,
        'cid2'=>$cid,
        'grid2'=>$tchData->GROUPID,
        'cid3'=>$cid,
        'grid3'=>$tchData->GROUPID
    );

    if ($sums = $DB->get_records_sql($groupListSQL, $params))
    {
        foreach($sums as $itemid => $v)
        {

            $dtObj              = new StdClass();
            $dtObj->userid      = $v->userid;
            $dtObj->studentid   = $v->studentid;
            $dtObj->firstname   = $v->firstname;
            $dtObj->lastname    = $v->lastname;
            $dtObj->email       = $v->email;
            $dtObj->fullname    = $v->fullname;

            if("ALL"==$v->flag){
                $dtObj->allgd=$v->finalgrade;
            }elseif("PC"==$v->flag){
                $dtObj->pcgd=$v->finalgrade;
            }elseif("SC"==$v->flag){
                $dtObj->scgd=$v->finalgrade;
            }

            $sumarray[$itemid]=$dtObj;
            unset($dtObj);
        }
        $resArr=array();
        foreach($sumarray as $key=>$val)
        {
            $dtObj              = new StdClass();
            $dtObj->userid      = $val->userid;
            $dtObj->studentid   = $val->studentid;
            $dtObj->firstname   = $val->firstname;
            $dtObj->lastname    = $val->lastname;
            $dtObj->email       = $val->email;
            $dtObj->fullname    = $val->fullname;

            $keyNum=explode("-",$key)[0];

            $dtObj->scgd  = $sumarray[$keyNum.'-ALL']->allgd;
            $dtObj->pcgd  = $sumarray[$keyNum.'-PC']->pcgd;
            $dtObj->allgd = $sumarray[$keyNum.'-SC']->scgd;

            $resArr[$keyNum]=$dtObj;
            unset($dtObj);
        }
        unset($key);
        unset($val);
        unset($sums);
        unset($v);
        unset($sumarray);
    }
    return $resArr;
}
//***
$DOPZ=0;$NEDOPZ=0;
$MINSIX=0;$MAXSIX=0;
$DOPE=0;$NEDOPE=0;
//---
$DEVCTO=0;
$EIGHTNINE=0;$SEVNINE=0;
$SIXNINE=0;$SIXFOUR=0;
$THIRNINE=0;$ZEROTHIRFOUR=0;
$USERZALIK=0;$USEREXAM=0;
//***
function doc_Download($arr, $cid)
{
    global  $USERZALIK, $USEREXAM, $tchData, $DOPZ, $NEDOPZ, $DOPE, $NEDOPE,
            $DEVCTO, $MINSIX, $MAXSIX, $EIGHTNINE, $SEVNINE, $SIXNINE,
            $SIXFOUR, $THIRNINE, $ZEROTHIRFOUR; $DEVCTO=0;$EIGHTNINE=0;
    $SEVNINE=0;$SIXNINE=0;$SIXFOUR=0;$THIRNINE=0;$ZEROTHIRFOUR=0;
    if(is_array($arr))
    {
        $CNTUSR=sizeof($arr);
        require_once("lib_cartFiles.php");
        $head=simbolReplace($head,$tchData);
        $datashow=$head;
        $k=0;
        foreach($arr as $v)
        {
            $k++;
            $FullName=trim($v->lastname).' '.trim($v->firstname);
            //$v->allgd=(int)$v->allgd;
            $grd=$v->allgd;
            if( (($grd>=90) and ($grd<=100) )or( ($grd>100) ) ){++$DEVCTO;}
            if( ($grd>=80) and ($grd<=89) ){++$EIGHTNINE;}
            if( ($grd>=70) and ($grd<=79) ){++$SEVNINE;}
            if( ($grd>=65) and ($grd<=69) ){++$SIXNINE;}
            if( ($grd>=60) and ($grd<=64) ){++$SIXFOUR;}
            if( ($grd>=34) and ($grd<=59) ){++$THIRNINE;}
            if( ($grd>=0) and ($grd<=33) ){++$ZEROTHIRFOUR;}
            if($grd>=60)
            {
                ++$DOPZ;++$MAXSIX;++$DOPE;
            }
            else if($grd<=59)
            {
                ++$NEDOPZ;++$MINSIX;++$NEDOPE;
            }

            $datashow.=trReplace
            (
                $tr,
                $k,
                $FullName,
                null,
                round($v->pcgd),//total
                round($v->scgd),//exam
                round($v->allgd),
                FourGrd($v->allgd),
                ECTS($v->allgd, $cid),
                null
            );
        }
        if( $tchData->OREXAM=='exam' )
        {
            $DOPZ=null;
            $NEDOPZ=null;
            $MINSIX=null;
            $MAXSIX=null;
            $USERZALIK=null;
            $USEREXAM=$CNTUSR;
        }
        else
        {
            $DOPE=null;
            $NEDOPE=null;
            $USEREXAM=null;
            $USERZALIK=$CNTUSR;
        }
        $bottom=simbolReplace($bottom,$tchData);
        $datashow.=$bottom;/*iconv('ANSI','utf-8',$datashow);*/
        //$datashow=iconv('cp1251', 'utf-8', $datashow);//utf8_encode($datashow);
        header("Content-type: application/x-force-download");
        //header("content-type:application/rtf");//mb_internal_encoding("utf-8");; charset=utf-8
        header("content-disposition:attachment; filename=\"cart_".date("Y-m-d-H-i-s",time()).".doc\"");
        echo $datashow;
        exit;
    }else {echo "empty data";}
}
function  trReplace
(
    $str, $NUM=null,
    $SNP=null, $NUMZALBK=null,
    $ALLNOTEXAM=null, $EXAM=null,
    $CTO=null, $FOUR=null,
    $ECTS=null, $SIGN=null
)
{
    $tr=array
    (
        "NUM"=>$NUM,
        "SNP"=>$SNP,
        "NUMZALBK"=>$NUMZALBK,
        "ALLNOTEXAM"=>$ALLNOTEXAM,
        "EXAM"=>$EXAM,
        "CTO"=>$CTO,
        "FOUR"=>$FOUR,
        "ECTS"=>$ECTS,
        "SIGN"=>$SIGN
    );
    return strtr($str,$tr);
}
function simbolReplace($str,$tchData)
{
    global
    $DOPZ,
    $NEDOPZ, $DOPE,
    $NEDOPE, $DEVCTO,
    $MINSIX, $MAXSIX,
    $EIGHTNINE, $SEVNINE,
    $SIXNINE, $SIXFOUR,
    $THIRNINE,$ZEROTHIRFOUR,
    $USERZALIK, $USEREXAM;

    if( $tchData->OREXAM=='exam' )
    {
        $oEX='Екзамен';//get_string('exam', 'block_vidomist')
    }
    else
    {
        $oEX='Залік';//get_string('zalik', 'block_vidomist')
    }
    $tr=array
    (
        "DEPT"=>$tchData->DEPTNAME,
        "COURSENAME"=>$tchData->CRNAME,
        "yrandyr"=>$tchData->NYST.'-'.$tchData->NYED,
        "NOWYR"=>date('Y.m.d',time()),
        "FORMNAVCH"=>$tchData->FORMNAVCH,
        "SPECNAME"=>$tchData->SPECNAME,
        "NONEDITTEACHER"=>$tchData->TEACHER,
        "CNTECTS"=>$tchData->CNTECTS,
        "OREXAM"=>$oEX,
        "CNTHOUR"=>$tchData->CNTHOUR,
        "CNTUSRZ"=>$USERZALIK,
        "CNTUSRE"=>$USEREXAM,
        "MINSIX"=>$MINSIX,
        "MAXSIX"=>$MAXSIX,
        "DOPZ"=>$DOPZ,
        "NEDOPZ"=>$NEDOPZ,
        "DOPE"=>$DOPE,
        "NEDOPE"=>$NEDOPE,
        "DEVCTO"=>$DEVCTO,
        "EIGHTNINE"=>$EIGHTNINE,
        "SEVNINE"=>$SEVNINE,
        "SIXNINE"=>$SIXNINE,
        "SIXFOUR"=>$SIXFOUR,
        "THIRNINE"=>$THIRNINE,
        "ZEROTHIRFOUR"=>$ZEROTHIRFOUR,
        "YRNOW"=>date('Y',time()),
        "NUMSEM"=>$tchData->NUMSEM,
        "COURSE"=>$tchData->COURSE,
        "GROUP"=>$tchData->GROUP,
        "DEPTBOSS"=>$CFG->block_vidomist_deptboss
    );
    return strtr($str,$tr);
}
function ECTS($gr, $cid)
{
    global $DB, $CFG;
    $grd=round($gr);
    $query="
    SELECT
        `grl`.`lowerboundary` AS `lb`,
        `grl`.`letter` AS `letter`
    FROM `{$CFG->prefix}grade_letters` AS `grl`
    INNER JOIN `{$CFG->prefix}context` AS `con` ON `con`.`id`=`grl`.`contextid`
    WHERE `instanceid`=:cid
    ";
    if ($arr = $DB->get_records_sql($query, array('cid'=>(int)$cid)))
    {
        foreach ($arr as $val)
        {
            $dtObj = new StdClass();
            $dtObj->letter=$val->letter;
            $dtObj->min=$val->lb;
            $dtObj->max=null;
            $ltArr[]=$dtObj;
            unset($dtObj);
        }
        unset($val);
    }
    for($i=0,$cntArrEl=sizeof($ltArr);$i<$cntArrEl;$i++)
    {
        $k=$i+1;
        if($k!=$cntArrEl)
        {$max=($ltArr[$k]->min)-0.01;}
            else{$max=100;}
        $ltArr[$i]->max=$max;
    }
    for($i=0,$cntArrEl=sizeof($ltArr);$i<$cntArrEl;$i++)
    {
        if( ($grd>=$ltArr[$i]->min) && ($grd<=$ltArr[$i]->max) )
            {return $ltArr[$i]->letter;}
    }
    return null;
}
function FourGrd($gr)
{
    $grd=round($gr);
    if( (($grd>=90) and ($grd<=100) )or( ($grd>100) ) )  {return 'Відміно';}
    if( ($grd>=70) and ($grd<=89) )  {return 'Добре';}
    if( ($grd>=60) and ($grd<=69) )  {return 'Задовільно';}
    if( ($grd>=0)  and ($grd<=59) )  {return 'Незадовільно';}
}

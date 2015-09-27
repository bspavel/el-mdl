<?php
ini_set('display_errors','On');
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__).'/../../config.php');

require_once("lib_role.php");if(!$CrsAcs) die;//<--------------------------------------edit
require_once("lib_func.php");
/*************************************************/
$cid = required_param('cid', PARAM_INT);$cid=(empty($cid))?'':$cid;
/**************/
$tchData=new stdClass();
$tchData->CNTHOUR=optional_param( 'hour',   1,  PARAM_INT );
$tchData->CNTECTS=optional_param( 'ects',   1,  PARAM_INT );
$tchData->SPECNAMEID=optional_param( 'spec',  '', PARAM_TEXT );//id
$tchData->GROUPID=optional_param( 'formnagroup',  '', PARAM_TEXT );//id
$tchData->OREXAM=optional_param('status', 0, PARAM_INT );
if($tchData->OREXAM==0){$tchData->OREXAM='exam';}
/***/
$TEACHER=getTeachersName($cid);
$tchData->SPECNAME=getSpecnm($tchData->SPECNAMEID);
$tchData->CRNAME=getCrname($cid);
$tchData->GROUP=getGROUP($tchData->GROUPID);
/***/
list($crGrYr, $fnvch, $grOkr, $subGr)  = explode("-", $tchData->GROUP);
switch (mb_substr($fnvch, -1))
{
    case 'д':   $tchData->FORMNAVCH='денна'; break;
    case 'з':   $tchData->FORMNAVCH='зоачна'; break;
    case 'в':   $tchData->FORMNAVCH='вечірня'; break;
    case 'н':   $tchData->FORMNAVCH='дистанційна'; break;
}
$tchData->NUMSEM=getSemestr($tchData->SPECNAMEID,$cid);
$tchData->DEPTNAME=getDeptName($tchData->SPECNAMEID);

switch ($tchData->NUMSEM)
{
    case '01': case '02': $tchData->COURSE='I';$nvCrYr=1; break;
    case '03': case '04': $tchData->COURSE='II';$nvCrYr=2; break;
    case '05': case '06': $tchData->COURSE='III';$nvCrYr=3; break;
    case '07': case '08': $tchData->COURSE='IV';$nvCrYr=4; break;
    case '09': case '10': $tchData->COURSE='V';$nvCrYr=5; break;
    case '11': case '12': $tchData->COURSE='VI';$nvCrYr=6; break;
}
$grCode=mb_substr($grOkr, 0, -1);
$tchData->GROUP=$nvCrYr.$subGr.$grCode;

$sj=$fnvch*1/0.5;
switch ($sj)
{
    case '4': case '3': $sj=9; break;
    case '8': case '7': $sj=7; break;
    case '12': case '11': case '10': case '9': case '8': default: $sj=1; break;
}
for($i=$sj,$val=$crGrYr;$i<=12;$i++)
{
    $arrYr[$i]=( $i % 2 )?$val:$val++;
}
foreach($arrYr as $k=>$v)
{
    if( $k % 2 )
    {
        $syr=$v;
        $eyr=$v;
        $syr--;
    }else
    {
        $syr=$v;
        $eyr=$v;
        $eyr++;
    }
    $arrNvchYr[$k]=$syr.'-'.$eyr;
}
$tchData->NYST=explode("-",$arrNvchYr[(int)$tchData->NUMSEM])[0];
$tchData->NYED=explode("-",$arrNvchYr[(int)$tchData->NUMSEM])[1];

//echo "<pre>"; print_r( $tchData ); echo "<br />"; print_r( dtVariable($cid, $tchData) ); echo "</pre>";die;

doc_Download( dtVariable($cid, $tchData), $cid );
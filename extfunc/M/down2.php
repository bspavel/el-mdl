<?php 
//ini_set('display_errors','On');
//ini_set("display_errors","1");
//ini_set("display_startup_errors","1");
//ini_set('error_reporting', E_ALL);

//require_once(dirname(__FILE__).'/../../../config.php');

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
/*
$sql=<<<eof
SELECT 
  u.`id` AS userid,
  u.`username` AS studentid,
  u.`firstname` AS firstname,
  u.`lastname` AS lastname,
  CONCAT_WS(' ', u.`lastname`, u.`firstname`) AS `student`,
  u.`email` AS email,
  gi.`grademax` AS itemgrademax,
  g.`finalgrade` AS finalgrade,
  c.`fullname` AS `course`
FROM
  mdl_user AS u
  INNER JOIN mdl_grade_grades AS g ON g.userid = u.id
  INNER JOIN mdl_grade_items AS gi ON g.itemid =  gi.id
  INNER JOIN mdl_course AS c ON c.id = gi.courseid
WHERE
  gi.courseid IN
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
AND
	u.id IN 
	(
		SELECT userid FROM `mdl_cohort_members` AS cm
		INNER JOIN `mdl_cohort` AS coh ON coh.id=cm.cohortid
		WHERE `name`="{$group}"
	)
AND
gi.`iteminstance` IN (SELECT `id` FROM `mdl_grade_categories` WHERE `fullname`="ПК")
AND
gi.`itemtype`="category"
eof;
*/







$sql=<<<eof
SELECT 
  @row := @row + 1 AS `cnt`,
  u.`id` AS userid,
  u.`username` AS studentid,
  u.`firstname` AS firstname,
  u.`lastname` AS lastname,
  CONCAT_WS(' ', u.`lastname`, u.`firstname`) AS `student`,
  u.`email` AS email,
  gi.`grademax` AS itemgrademax,
  g.`finalgrade` AS finalgrade,
  c.`fullname` AS `course`
FROM
  mdl_user AS u
  INNER JOIN mdl_grade_grades AS g ON g.userid = u.id
  INNER JOIN mdl_grade_items AS gi ON g.itemid =  gi.id
  INNER JOIN mdl_course AS c ON c.id = gi.courseid
  JOIN (SELECT @row := 0) AS tmp
WHERE
  gi.courseid IN
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
AND
	u.id IN 
	(
		SELECT userid FROM `mdl_cohort_members` AS cm
		INNER JOIN `mdl_cohort` AS coh ON coh.id=cm.cohortid
		WHERE `name`="{$group}"
	)
AND
gi.`iteminstance` IN (SELECT `id` FROM `mdl_grade_categories` WHERE `fullname`="ПК")
AND
gi.`itemtype`="category"
eof;


























$std=null;
if ($dt = $DB->get_records_sql($groupSQL))
{
    foreach ($dt as $k => $v)
	{
		unset($std);
		$std = new stdClass();
		$std->userid=$v->userid;
		$std->student=$v->student;
		$std->grade=0;
		$grArr[]=$std;
	}
}
unset($dt);unset($k);unset($v);unset($std);

//$std=null;
if ($dt = $DB->get_records_sql($sql))
{$uDtArr=$dt;

//************************************************
/*
echo "<pre>";
	print_r($uDtArr);
echo "</pre>";
*/
//************************************************

    //foreach ($dt as $k => $v)

	foreach ($dt as $v)
	{
		$dtx[]=$v;
	}

	//for($i=0;$i<sizeof($dtx);$i++ )
	//{			
/*		unset($XxX);
				$XxX	=	gratMark
									(
										$dtx[$i]->course,
										$grArr,
										$dt
									);//$std;
		$DtArr[$dtx[$i]->course]=$XxX;/*rand(5, 15);/
*/	

unset($DtArr1);

$grArr1=$grArr;
$grArr2=$grArr;
$grArr3=$grArr;

$dt1=$dt;
$dt2=$dt;
$dt3=$dt;





for( $i=0;$i<sizeof($dtx);$i++ )
{
	$rrr=gratMark( $dtx[$i]->course,  $grArr1, $dt1 );
	$DtArr[$dtx[$i]->course]=$rrr;unset($rrr);
}



/***************************************************/
//echo "<pre>";					
/*print_r($DtArr);*/
/*print_r($dtx);*/
//echo "</pre>";
/***************************************************/





/************************************************************/
if( !isset($DtArr) )
{
//	echo "hello";
	die;
}
/************************************************************/


/*



//echo "<pre>";
	
	$rrr=gratMark( $dtx[0]->course,  $grArr1, $dt1 );
	$DtArr[$dtx[0]->course]=$rrr;unset($rrr);
	
	
	
//$a1=serialize($DtArr1);
//file_put_contents('xxx.txt', serialize($DtArr1)); 
//	print_r($DtArr1);
	unset($DtArr1);unset($dt1);unset($grArr1);
//echo "/********************************************************************************";
unset($a2);
	$DtArr[$dtx[1]->course]=gratMark( $dtx[1]->course,	 $grArr2, $dt2 );
//$a2=serialize($DtArr2);
//	print_r($DtArr2);
	unset($DtArr2);unset($dt2);unset($grArr2);
//echo "/***********************************************************************************";
unset($a3);
	$DtArr[$dtx[2]->course]=gratMark( $dtx[2]->course,	 $grArr3, $dt3 );
//$a3=serialize($DtArr3);
//	print_r($DtArr3);
	unset($DtArr3);unset($dt3);unset($grArr3);
//echo "</pre>";
//unset($DtArr);
//$DtArr=array_merge($a2, $a3);

*/

	
	//}
}

function gratMark($course, $grArr, $dt)
{
	foreach( $dt as $v )
	{
		if( $v->course==$course )
		{
			$grArr=xxxxx( $v, $grArr );
			/*echo "<pre>
				----------------->>>";
				print_r($grArr);
			echo "<<<----------------
				</pre>";				
			break;*/
		}
	}
	return $grArr;
}

function xxxxx( $dt, $grArr )
{

foreach( $grArr as $v )
	{

/*
	echo "*****";
	echo"VId:".$v->userid;
	echo "===";
	echo"DtId".$dt->userid;
	echo "*****"."\n";
*/
/**********************************/
		if($v->userid==$dt->userid)
		{

/**********************************************************/
//		$v->grade=round($dt->finalgrade);
//		echo '['.$v->userid.'------'.$v->grade.']';
//		continue;
/**********************************************************/

		unset($std);
		$std = new stdClass();
		$std->userid=$v->userid;
		$std->student=$v->student;
		$std->grade=round($dt->finalgrade);
		$grArrUser[]=$std;

	//unset($std);
	
		}else if ( $v->grade>0 )
		{
			unset($std);
			$std = new stdClass();
			$std->userid=$v->userid;
			$std->student=$v->student;
			$std->grade=$v->grade;
			$grArrUser[]=$std;
		}
		 else
		{		
			unset($std);
			$std = new stdClass();
			$std->userid=$v->userid;
			$std->student=$v->student;
			$std->grade=0;
			$grArrUser[]=$std;
		}
/************************************/





	}	

	//unset($dt);unset($k);unset($v);
	return $grArrUser;
}






















































/*

function AaA( $dt, $user, $course )
{
	foreach( $dt as $v )
	{
		if( ( $v->course==$course ) && ( $v->userid==$user ) )
		{
			return $v->finalgrade;
		}
	}
	return 0;
}
//**********************************************************************
function AxA( $dt, $user, $course )
{
	foreach( $dt as $v )
	{
		if( ( $v->course==$course ) && ( $v->userid==$user ) )
		{
			return $course.'---'.$v->finalgrade.'---'.$user.'---------|';
		}
	}
	//return 0;
}

*/







/*
    foreach ($DtArr as $key => $val)
	{

		for
		(
			$ij=0;
			$ij<sizeof($val);
			$ij++
		)
		{
		
		foreach( $dt as $kv=>$vv )
			{
			
			/*
				if
				(
					(
						$key==$vv->course
					)
					&&
					(
						$DtArr[$key][$ij]->userid==$kv
					)
				)
				{
					$DtArr[$key][$ij]->grade=round($vv->finalgrade);
				}
			/
			
				$aAa=AaA
				(
					$dt,
					$DtArr[$key][$ij]->userid,
					$vv->course
				);
				
				if($aAa>0)
				{
					$DtArr[$key][$ij]->grade=round($aAa);
				
					$Arrtmp[]=AxA
					(
						$dt,
						$DtArr[$key][$ij]->userid,
						$vv->course
					);
			
				}
				unset($aAa);
				

				
				
				
				

			}
		}
	}

*/




/*
echo "<pre>
			*
			*
			*
			*
			*
			*
";
print_r($DtArr);//$dt  //$Arrtmp
echo "</pre>";

*/

/*

echo "___________________";
	print_r($DtArr1);
echo "_____________________";

echo "___________________";
	print_r($DtArr2);
echo "_____________________";

echo "___________________";
	print_r($DtArr3);
echo "_____________________";*/
//-----------------------------------------------
/*
    echo "<pre>";
    print_r($DtArr);echo"<hr />";//a1
	echo "*************************";
//    print_r(unserialize($a3));echo"<hr />";//a2
//    print_r(unserialize($a2)); //$grArr
    echo "</pre>";
    die;
*/
//-----------------------------------------------

//$a1 = file('xxx.txt');
//$a1=unserialize($a1[0]);

//$DtArr=unserialize($a1);






/*
    echo "<pre>";
	echo "*************************";
		print_r($a1);
		echo"<hr />";
	echo "*************************";
    echo "</pre>";
    die;

*/





//$DtArr=array_merge( unserialize($a3), $a1 );

//$DtArr = array_merge( $DtArrS, unserialize($a3) );






unset($dt);unset($k);unset($v);unset($std);
$sql=<<<eof
SELECT `fullname` AS `cname` FROM `mdl_course`
WHERE `id`
IN
(
	SELECT `courseid` AS `cid`
	FROM `mdl_enrol`
	WHERE `customint1` IN
	(
	   SELECT coh.`id` FROM `mdl_cohort` AS `coh`
	   WHERE coh.`name`="{$group}"
	)
)
eof;
if ($dt = $DB->get_records_sql($sql))
{
    foreach ($dt as $v)
	{
		$SPEC=$v->cname;
	}
}
unset($dt);unset($k);unset($v);unset($std);



/*
echo"<pre>";
echo 'SPEC: '.$SPEC.'<br />';
echo 'CEMECTP: '.$semestr.'<br />';
echo 'Group: '.$group.'<br />';
print_r($DtArr);echo"<hr />";
foreach ($DtArr as $k=>$v)
{
	$sizeDtArr=sizeof($DtArr[$k]);break;
}
echo '--->>>'.$sizeDtArr.'<<<---';
echo"</pre>";die;
*/







































function doc_Download($arr,$dept=null)
{
	global 	$SPEC, $semestr, $group;
	if(is_array($arr))
	{	

		foreach ($arr as $k=>$v)
		{
			$CNTUSR=sizeof($arr[$k]);break;
		}
		
		
		require_once("cartFileMejses.php");
		$head=simbolReplace($head);
		$datashow=$head;
		$k=0;
		$trLine=null;
		$ij=0;
		foreach($arr as $k=>$v)		
		{
			++$ij;
			$trLesson=trLesson($trLesson,$k,$ij);			
			if($ij==1)
			{
				for
				(
					$i=0,$num=1;
					/*$i<sizeof($v)*/
					$i<18;/*err >=19*/
					$i++,$num++
				)
				{
					$trLine.=trReplace
						(
							$tr,$num,
							$v[$i]->student,
							$v[$i]->grade,
							"A{$num}A2","A{$num}A3","A{$num}A4",
							"A{$num}A5","A{$num}A6","A{$num}A7",
							"A{$num}A8","A{$num}A9","A{$num}A10"
							/*	 $ij	*/
						);			
				}
			}else
			{			
				for
				(
					$i=0,$num=1;
					/*$i<sizeof($v)*/
					$i<18;/*err >=19*/
					$i++,$num++
				)
				{
					$trLine=trReplaceOth
						(
							$trLine,$num,						
							$v[$i]->grade,
							$ij
						);
				}			
			}
		}
		$sum=sizeof($arr);
		for
		(
			$ij=10;
			$ij<11;
			$ij--
		)
		{
			if($ij<$sum){break;}
			$trLesson=trLesson
			(
				$trLesson,
				null,
				$ij
			);
		}
		
		/****************/
		
		
		for
		(
			$ij=10;
			$ij<11;
			$ij--
		)
		{
			if($ij<$sum){break;}
				for
				(
					$i=0,$num=1;
					/*$i<sizeof($v)*/
					$i<18;/*err >=19*/
					$i++,$num++
				)
				{
					$trLine=trReplaceOth
						(
							$trLine,$num,
							null,
							$ij
						);
				}
		}
		
		
		/****************/	
		
		$datashow.=$trLesson;
		$datashow.=$trLine;		
		$bottom=simbolReplace($bottom);		
		$datashow.=$bottom;/*iconv('ANSI','utf-8',$datashow);*/
//		$datashow=iconv('cp1251', 'utf-8', $datashow);//utf8_encode($datashow);

		header("Content-type: application/x-force-download");//header("content-type:application/rtf");//mb_internal_encoding("utf-8");; charset=utf-8
		header("content-disposition:attachment; filename=\"cart_".date("Y-m-d-H-i-s",time()).".xls\"");

		echo $datashow;
		exit;
	}
}


function  trLesson
				(
					$str,
					$lesson,
					$j
				)
				
{
if($j>9){$A='A';}else{$A=null;}
	$tr=array
	(
		"LESSON".$A.$j=>$lesson
	);
	return strtr($str,$tr);
}


function trReplaceOth
					(
						$str,$num,						
						$grade,
						$ij
					)
{
	$tr=array
	(
		'A'.$num.'A'.$ij=>$grade
	);
	return strtr($str,$tr);
}					

function  trReplace
				(
					$str,
					$NUM,
					$SNP,
					$A3,$A4=null,
					
					$A5=null,$A6=null,$A7=null,
					$A8=null,$A9=null,$A10=null,$A11=null,$A12=null,

					$A13=null,
					$A14=null
				)
{
	$tr=array
	(
		"A1A1"=>$NUM,
		"A1A2"=>$SNP,

		"A1A3"=>$A3, "A1A4"=>$A4, "A1A5"=>$A5, "A1A6"=>$A6, "A1A7"=>$A7,
		"A1A8"=>$A8, "A1A9"=>$A9, "A1A10"=>$A10, "A1A11"=>$A11, "A1A12"=>$A12,

		"A1A13"=>$A13,
		"A1A14"=>$A14
	);
	return strtr($str,$tr);
}

//---------------------------------------------------------------------------------
function simbolReplace($str,$COURSENAME=null)
{
	global 	$SPEC, $group, $semestr;
	$nowYrplus1=date('Y',time())-1;
	$nowYrplus1.='-'.date('Y',time());
	$tr=array
	(
		"DEPT"=>'Інститут інформатики',
		"COURSENAME"=>$COURSENAME,
		"yrandyr"=>$nowYrplus1,
		"NOWYR-NOWYR+1"=>$nowYrplus1,
		"NOWDATE"=>date('Y.m.d',time()),
		"SPEC"=>$SPEC,
		"GRP"=>$group,
		"SEMESTR"=>$semestr
	);	
    return strtr($str,$tr);
}
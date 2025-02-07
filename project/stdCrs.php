<?php
include_once("connection.php");
$st1 = $con->prepare("
SELECT 
c.id crs_id,
concat(ma.Coll_Major_No,ma.Major_No,c.Crs_Level,c.Course_No) crs_no, 
c.Course_Name c_name, 
crs_type.Type ty, 
c.crs_hours h1, 
p.Course_Name p_name
FROM 
courses c LEFT JOIN courses p ON(p.ID=c.Pre_ID) INNER JOIN majors ma ON(ma.ID=c.Crs_Mjr_No), 
majors m, 
std_plan, 
crs_type 
WHERE 
std_plan.course_id=c.ID 
AND 
std_plan.crs_type=crs_type.ID 
AND 
std_plan.major_id=m.ID 
AND 
((SELECT substring(students.Std_No,2,4)from students where students.id=?) BETWEEN c.First_Year AND c.Last_Year) 
AND 
m.major_no=(SELECT substring(students.Std_No,8,2)from students where students.id=?)
AND 
c.ID not in (
SELECT 
std_crs.crs_id
FROM
std_crs
WHERE
std_crs.std_id=?
AND
std_crs.degree >= 68
)
and
c.ID not in (
SELECT 
std_crs.crs_id
FROM
std_crs,courses
WHERE
std_crs.std_id=?
AND
std_crs.degree BETWEEN 50 and 68
AND
std_crs.crs_id=courses.id
AND
courses.Crs_Hours = 0
AND
std_crs.status  = 1
)
AND
(
c.Pre_ID in (
SELECT 
std_crs.crs_id
FROM
std_crs
WHERE
std_crs.std_id=?
)
or
c.Pre_ID is null
)
AND
(
c.Pre_ID not in (
SELECT 
std_crs.crs_id
FROM
std_crs,courses
WHERE
std_crs.std_id=?
AND
std_crs.degree < 50
AND
std_crs.crs_id=courses.id
AND
courses.Crs_Hours = 0
AND
std_crs.status  = 1
)
or
c.Pre_ID is null
)
AND
(
c.Pre_ID not in (select subjects.crs_id from subjects,std_crs_sem WHERE subjects.id=std_crs_sem.sub_id and std_id=?)
OR
c.Pre_ID in (select subjects.crs_id from subjects,std_crs_sem WHERE subjects.id=std_crs_sem.sub_id and std_id=?)
or
c.Pre_ID is null
)
AND
c.ID not IN (select subjects.crs_id from subjects,std_crs_sem WHERE subjects.id=std_crs_sem.sub_id and std_crs_sem.std_id=? and std_crs_sem.degree> 68)
AND 
c.ID not IN (select subjects.crs_id from subjects,std_crs_temp WHERE subjects.id=std_crs_temp.sub_id and std_crs_temp.std_id=?)
order by c.id
");
$st1->bind_param("iiiiiiiiii",$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"]);
$st1->execute();
$rs1 = $st1->get_result();
$num1 = $rs1->num_rows;
$st3 = $con->prepare("
SELECT 
concat(majors.Coll_Major_No,majors.Major_No,courses.Crs_Level,courses.Course_No) crs_no,
courses.Course_Name crs_name,
std_crs.degree degree,
courses.crs_hours hr,
semesters.sem_year year,
semesters.sem_no sem_no,
std_crs.status status
FROM
courses,
majors,
semesters,
std_crs
WHERE
courses.Crs_Mjr_No=majors.ID
AND
courses.id=std_crs.crs_id
AND
semesters.id=std_crs.sem_id
AND
std_crs.std_id=?
ORDER BY
std_crs.sem_id;
");
$st3->bind_param("i",$_GET["userid"]);
$st3->execute();
$rs3 = $st3->get_result();
$num3 = $rs3->num_rows;
$st4 = $con->prepare("
SELECT 
uni_man_hr,
uni_op_hr,
col_man_hr,
spc_man_hr,
spc_op_hr
FROM 
majors,majors_hour
WHERE 
majors.id=majors_hour.mjr_id and
((SELECT substring(students.Std_No,2,4)from students where students.id=?) 
BETWEEN from_Year AND to_Year)
AND majors.major_no=(SELECT substring(students.Std_No,8,2)from students where students.id=?)
");
$st4->bind_param("ii",$_GET["userid"],$_GET["userid"]);        
$st4->execute();
$rs4 = $st4->get_result();
$row4 = $rs4->fetch_assoc();
$st5 = $con->prepare("
SELECT 
concat(ma.Coll_Major_No,ma.Major_No,c.Crs_Level,c.Course_No) crs_no, 
c.Course_Name c_name, 
crs_type.Type ty, 
c.crs_hours h1, 
p.Course_Name p_name 
FROM 
courses c LEFT JOIN courses p ON(p.ID=c.Pre_ID) INNER JOIN majors ma ON(ma.ID=c.Crs_Mjr_No), 
majors m, 
std_plan, 
crs_type 
WHERE 
std_plan.course_id=c.ID 
AND 
std_plan.crs_type=crs_type.ID 
AND std_plan.major_id=m.ID 
AND 
((SELECT substring(students.Std_No,2,4)from students where students.id=?) BETWEEN c.First_Year AND c.Last_Year) 
AND 
m.major_no=(SELECT substring(students.Std_No,8,2)from students where students.id=?)
order by c.id;
");
$st5->bind_param("ii",$_GET["userid"],$_GET["userid"]);
$st7 = $con->prepare("
SELECT 
concat(ma.Coll_Major_No,ma.Major_No,c.Crs_Level,c.Course_No) crs_no, 
c.Course_Name c_name, 
crs_type.Type ty, 
c.crs_hours h1, 
p.Course_Name p_name 
FROM 
courses c LEFT JOIN courses p ON(p.ID=c.Pre_ID) INNER JOIN majors ma ON(ma.ID=c.Crs_Mjr_No), 
majors m, 
std_plan, 
crs_type 
WHERE 
std_plan.course_id=c.ID 
AND 
std_plan.crs_type=crs_type.ID 
AND std_plan.major_id=m.ID 
AND 
((SELECT substring(students.Std_No,2,4)from students where students.id=?) BETWEEN c.First_Year AND c.Last_Year) 
AND 
m.major_no=(SELECT substring(students.Std_No,8,2)from students where students.id=?)
AND
c.id in (select crs_id from subjects where id in (select sub_id from std_crs_temp where std_id=?) and crs_id not in (select crs_id from std_crs where std_id=?) )
order by c.id;
");
$st7->bind_param("iiii",$_GET["userid"],$_GET["userid"],$_GET["userid"],$_GET["userid"]);
$st7->execute();
$rs7 = $st7->get_result();
$num7 = $rs7->num_rows;
$count1 = 0;
$count2 = 0;
$count3 = 0;
$count4 = 0;
$count5 = 0;
if($num7 > 0)
{
    while($row7 = $rs7->fetch_assoc())
    {
        if($row7["ty"] =="جامعة اجباري")
        {
            $count1  +=  $row7["h1"];
        }
        else  if($row7["ty"] == "جامعة اختياري")
        {
            $count2  +=  $row7["h1"];
        }
        else  if($row7["ty"] == "كلية اجباري")
        {
            $count3  +=  $row7["h1"];
        }
        else  if($row7["ty"] == "تخصص اجباري")
        {
            $count4  +=  $row7["h1"];
        }
        else  if($row7["ty"] == "تخصص اختياري")
        {
            $count5  +=  $row7["h1"];
        }
    } 
}
if($num3 > 0)
{
    while($row3 = $rs3->fetch_assoc())
    {
        $st5->execute();
        $rs5 = $st5->get_result();
        while($row5 = $rs5->fetch_assoc())
        {
            if($row5["crs_no"] == $row3["crs_no"] && $row3["status"] != 0 && $row3["degree"] != NULL)
            {
                if($row5["ty"] =="جامعة اجباري")
                {
                    $count1  +=  $row5["h1"];
                }
                else  if($row5["ty"] == "جامعة اختياري")
                {
                    $count2  +=  $row5["h1"];
                }
                else  if($row5["ty"] == "كلية اجباري")
                {
                    $count3  +=  $row5["h1"];
                }
                else  if($row5["ty"] == "تخصص اجباري")
                {
                    $count4  +=  $row5["h1"];
                }
                else  if($row2["ty"] == "تخصص اختياري")
                {
                    $count5  +=  $row5["h1"];
                }
            }
        }
    }
}
$st9 = $con->prepare("select std_ori_hr,std_ori_day from students where id=?");
$st9->bind_param("i",$_GET["userid"]);
$st9->execute();
$rs9 = $st9->get_result();
$row9 = $rs9->fetch_assoc();
$day1 = 0;
$day2 = 0;
$day3 = 0;
$hour = 12;
$choose = 0;
if($row9["std_ori_hr"]==1)
{
    $choose = 1;
    if($row9["std_ori_day"]==1)
    {
        $day1 = 1;
    }
    else if($row9["std_ori_day"]==2)
    {
        $day2 = 2; 
    }
    else if($row9["std_ori_day"]==3)
    {
        $day3 = 3;
    }
    else if($row9["std_ori_day"]==4)
    {
        $day1 = 1;
        $day2 = 2;
        $day3 = 3;
    }
}
else if($row9["std_ori_hr"]==2)
{
    $choose = 2;
    if($row9["std_ori_day"]==1)
    {
        $day1 = 1;
    }
    else if($row9["std_ori_day"]==2)
    {
        $day2 = 2; 
    }
    else if($row9["std_ori_day"]==3)
    {
        $day3 = 3;
    }
    else if($row9["std_ori_day"]==4)
    {
        $day1 = 1;
        $day2 = 2;
        $day3 = 3;
    }
}
else if($row9["std_ori_hr"]==3)
{
    $choose = 3;
    if($row9["std_ori_day"]==1)
    {
        $day1 = 1;
    }
    else if($row9["std_ori_day"]==2)
    {
        $day2 = 2; 
    }
    else if($row9["std_ori_day"]==3)
    {
        $day3 = 3;
    }
    else if($row9["std_ori_day"]==4)
    {
        $day1 = 1;
        $day2 = 2;
        $day3 = 3;
    }
}
$str = "";
if($day1 != 0)
    $str = "and subjects.days in( ".$day1.")";
if($day2 != 0)
    $str = "and subjects.days in( ".$day2.")";
if($day3 != 0)
    $str = "and subjects.days in( ".$day3.")";
if($day1 != 0 && $day2 != 0 && $day3 != 0)
{
    $str = "and subjects.days in( ".$day1.",".$day2.",".$day3.")";
}
switch($choose)
{
    case 1:{
        $str=$str." and subjects.from_hour<=12";
    }break;
    case 2:{
        $str=$str." and subjects.from_hour>=12";
    }break;
}
$st2 = $con->prepare("
    SELECT DISTINCT
    concat(majors.Coll_Major_No,majors.Major_No,courses.Crs_Level,courses.Course_No) crs_no,
    subjects.id sub_id,
    courses.Course_Name crs_name,
    subjects.class_num c_num,
    teachers.t_name t_name,
    subjects.from_hour h1,
    subjects.to_hour h2,
    courses.crs_hours h3,
    sub_days.days days
    FROM
    courses,
    majors,
    subjects,
    sub_days,
    teachers
    WHERE
    courses.Crs_Mjr_No=majors.ID
    AND
    courses.ID=subjects.crs_id
    AND
    teachers.id=subjects.crs_teach
    AND
    subjects.days=sub_days.id
    ".$str."
    ORDER BY subjects.crs_id;
    ");
    $st6 = $con->prepare("select degree from std_crs where status!=0 and crs_id=? and std_id=?");
    $state1 = '<tr style="background:radial-gradient(50% 50% at 50% 50%, var(--main-color) 30%, var(--alt-color) 150%); color: var(--alt-2-color);">'.
    '<td style="vertical-align: middle;" colspan="8">'.'نوع المتطلب : جامعة اجباري</td>'.
    '</tr>';
    $state2 = '<tr style="background:radial-gradient(50% 50% at 50% 50%, var(--main-color) 30%, var(--alt-color) 150%); color: var(--alt-2-color);">'.
    '<td style="vertical-align: middle;" colspan="8">'.'نوع المتطلب : جامعة اختياري</td>'.
    '</tr>';
    $state3 = '<tr style="background:radial-gradient(50% 50% at 50% 50%, var(--main-color) 30%, var(--alt-color) 150%); color: var(--alt-2-color);">'.
    '<td style="vertical-align: middle;" colspan="8">'.'نوع المتطلب : كلية اجباري</td>'.
    '</tr>';
    $state4 = '<tr style="background:radial-gradient(50% 50% at 50% 50%, var(--main-color) 30%, var(--alt-color) 150%); color: var(--alt-2-color);">'.
    '<td style="vertical-align: middle;" colspan="8">'.'نوع المتطلب : تخصص اجباري</td>'.
    '</tr>';
    $state5 = '<tr style="background:radial-gradient(50% 50% at 50% 50%, var(--main-color) 30%, var(--alt-color) 150%); color: var(--alt-2-color);">'.
    '<td style="vertical-align: middle;" colspan="8">'.'نوع المتطلب : تخصص اختياري</td>'.
    '</tr>';
    $len1 = strlen($state1);
    $len2 = strlen($state2);
    $len3 = strlen($state3);
    $len4 = strlen($state4);
    $len5 = strlen($state5);
    if($num1 > 0)
    {
        while($row1 = $rs1->fetch_assoc())
        {
            $st2->execute();
            $rs2 = $st2->get_result();
            while($row2 = $rs2->fetch_assoc())
            {
                if($row1["crs_no"] == $row2["crs_no"])
                {

                    if($row1["ty"] =="جامعة اجباري")
                    {
                        if($count1 != $row4["uni_man_hr"])
                        {
                            $state1 = $state1.'<tr id='.$row2["sub_id"].'>'.
                            '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                            '</tr>';
                        }
                        else 
                        {
                            $st6->bind_param("ii",$row1['crs_id'],$_GET["userid"]);
                            $st6->execute();
                            $rs6 = $st6->get_result();
                            $num6 = $rs6->num_rows;
                            $row6 = $rs6->fetch_assoc();
                            if($num6 > 0)
                            {
                                if($row6["degree"] < 68)
                                {
                                    $state1 = $state1.'<tr id='.$row2["sub_id"].'>'.
                                    '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                                    '</tr>';
                                } 
                            }
                        }
                    }
                    else if($row1["ty"] == "جامعة اختياري")
                    {
                        if($count2 != $row4["uni_op_hr"])
                        {
                            $state2 = $state2.'<tr id='.$row2["sub_id"].'>'.
                            '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                            '</tr>';
                        }
                        else 
                        {
                            $st6->bind_param("ii",$row1['crs_id'],$_GET["userid"]);
                            $st6->execute();
                            $rs6 = $st6->get_result();
                            $num6 = $rs6->num_rows;
                            $row6 = $rs6->fetch_assoc();
                            if($num6 > 0)
                            {
                                if($row6["degree"] < 68)
                                {
                                    $state2 = $state2.'<tr id='.$row2["sub_id"].'>'.
                                    '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                                    '</tr>';
                                } 
                            }
                        }
                    }
                    else if($row1["ty"] == "كلية اجباري")
                    {
                        if($count3 != $row4["col_man_hr"])
                        {
                            $state3 = $state3.'<tr id='.$row2["sub_id"].'>'.
                            '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                            '</tr>';
                        }
                        else 
                        {
                            $st6->bind_param("ii",$row1['crs_id'],$_GET["userid"]);
                            $st6->execute();
                            $rs6 = $st6->get_result();
                            $num6 = $rs6->num_rows;
                            $row6 = $rs6->fetch_assoc();
                            if($num6 > 0)
                            {
                                if($row6["degree"] < 68)
                                {
                                    $state3 = $state3.'<tr id='.$row2["sub_id"].'>'.
                                    '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                                    '</tr>';
                                } 
                            }
                        }
                    }
                    else if($row1["ty"] == "تخصص اجباري")
                    {
                        if($count4 != $row4["spc_man_hr"])
                        {
                            $state4 = $state4.'<tr id='.$row2["sub_id"].'>'.
                            '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                            '</tr>';
                        }
                        else 
                        {
                            $st6->bind_param("ii",$row1['crs_id'],$_GET["userid"]);
                            $st6->execute();
                            $rs6 = $st6->get_result();
                            $num6 = $rs6->num_rows;
                            $row6 = $rs6->fetch_assoc();
                            if($num6 > 0)
                            {
                                if($row6["degree"] < 68)
                                {
                                    $state4 = $state4.'<tr id='.$row2["sub_id"].'>'.
                                    '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                                    '</tr>';
                                } 
                            }
                        }
                    }
                    else if($row1["ty"] == "تخصص اختياري")
                    {
                        if($count5 != $row4["spc_op_hr"])
                        {
                            $state5 = $state5.'<tr id='.$row2["sub_id"].'>'.
                            '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                            '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                            '</tr>';
                        }
                        else 
                        {
                            $st6->bind_param("ii",$row1['crs_id'],$_GET["userid"]);
                            $st6->execute();
                            $rs6 = $st6->get_result();
                            $num6 = $rs6->num_rows;
                            $row6 = $rs6->fetch_assoc();
                            if($num6 > 0)
                            {
                                if($row6["degree"] < 68)
                                {
                                    $state5 = $state5.'<tr id='.$row2["sub_id"].'>'.
                                    '<td style="vertical-align: middle;">'.$row2["days"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h2"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h1"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["c_num"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["t_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["h3"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_name"].'</td>'.
                                    '<td style="vertical-align: middle;">'.$row2["crs_no"].'</td>';
                                    '</tr>';
                                } 
                            }
                        }
                    }
                }
            }
        }
        if(strlen($state1) != $len1)
        {
            echo $state1;
        }
        if(strlen($state2) != $len2)
        {
            echo $state2;
        }
        if(strlen($state3) != $len3)
        {
            echo $state3;
        }
        if(strlen($state4) != $len4)
        {
            echo $state4;
        }
        if(strlen($state5) != $len5)
        {
            echo $state5;
        }
        
    }
    else
    {
        echo'<tr>';
        echo '<td style="height: 208px; vertical-align: middle;" colspan="8">لا يوجد نتائج</td>';
        echo '</tr>';
    }
?>
<?php
$sub_menu = "600100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");

if ($w == 'u')
    check_demo();

auth_check($sub_menu);

check_admin_token();

$pu_id = trim($_POST['pu_id']);


$sql_common = "  pu_subject = '{$_POST['pu_subject']}',
                 pu_content = '{$_POST['pu_content']}',
		         pu_url = '{$_POST['pu_url']}',
                 pu_sido = '{$_POST['sido']}',
                 pu_gugun = '{$_POST['gugun']}',
                 pu_age = '{$_POST['pu_age']}',
                 pu_sex = '{$_POST['pu_sex']}',
                 pu_grade = '{$_POST['pu_grade']}',
                 pu_url = '{$_POST['pu_url']}',
                 mb_id = '{$member['mb_id']}',
                 category_name = '{$_POST['category_name']}',
                 mb_name = '{$member['mb_name']}' ";

if ($w == '')
{
    sql_query(" insert into push_data set {$sql_common}, pu_datetime = NOW() ");
	$pu_id = sql_insert_id();
}
else if ($w == 'u')
{


    $sql = " update push_data
                set {$sql_common}
                where pu_id = '{$pu_id}' ";
    
    echo $sql;

    //sql_query($sql);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

//goto_url('./push_send_form.php?'.$qstr.'&amp;w=u&amp;pu_id='.$pu_id, false);
?>
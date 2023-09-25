<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($_POST['act_button'] == "수정")
{
		$sql = " update shop_roulette
					set point1  = '{$_POST['point1']}',
						point2  = '{$_POST['point2']}',
						point3  = '{$_POST['point3']}',
						point4  = '{$_POST['point4']}',
						point5  = '{$_POST['point5']}',
						point6  = '{$_POST['point6']}',
						point7  = '{$_POST['point7']}',
						point8  = '{$_POST['point8']}',
						point9  = '{$_POST['point9']}',
						point10  = '{$_POST['point10']}',
						point_per1  = '{$_POST['point_per1']}',
						point_per2  = '{$_POST['point_per2']}',
						point_per3  = '{$_POST['point_per3']}',
						point_per4  = '{$_POST['point_per4']}',
						point_per5  = '{$_POST['point_per5']}',
						point_per6  = '{$_POST['point_per6']}',
						point_per7  = '{$_POST['point_per7']}',
						point_per8  = '{$_POST['point_per8']}',
						point_per9  = '{$_POST['point_per9']}',
						point_per10  = '{$_POST['point_per10']}'
				  where no = '1' ";
		sql_query($sql);
} 
else {
	alert();
}

goto_url(MS_ADMIN_URL."/config.php?code=roulette");
?>
<?php
include_once("./_common.php");

check_demo();

check_admin_token();

for($i=0; $i<count($_POST['gs_id']); $i++)
{
    $sql = " select count(*) as cnt 
				from shop_goods_type
			   where mb_id = '".encrypted_admin()."' 
			     and gs_id = '{$_POST['gs_id'][$i]}' ";
    $row = sql_fetch($sql);
	if($row['cnt']) {
		$sql = "update shop_goods_type
				   set it_type1 = '{$_POST['it_type1'][$i]}',
					   it_type2 = '{$_POST['it_type2'][$i]}',
					   it_type3 = '{$_POST['it_type3'][$i]}',
					   it_type4 = '{$_POST['it_type4'][$i]}',
					   it_type5 = '{$_POST['it_type5'][$i]}',
					   it_type6 = '{$_POST['it_type6'][$i]}',
					   it_type7 = '{$_POST['it_type7'][$i]}',
					   it_type8 = '{$_POST['it_type8'][$i]}',
					   it_type9 = '{$_POST['it_type9'][$i]}',
					   it_type10 = '{$_POST['it_type10'][$i]}',
					   it_type11 = '{$_POST['it_type11'][$i]}',
					   it_type12 = '{$_POST['it_type12'][$i]}',
					   it_type13 = '{$_POST['it_type13'][$i]}',
					   it_type14 = '{$_POST['it_type14'][$i]}',
					   it_type15 = '{$_POST['it_type15'][$i]}',
					   it_type16 = '{$_POST['it_type16'][$i]}',
					   it_type17 = '{$_POST['it_type17'][$i]}',
					   it_type18 = '{$_POST['it_type18'][$i]}',
					   it_type19 = '{$_POST['it_type19'][$i]}',
					   it_type20 = '{$_POST['it_type20'][$i]}'
				 where mb_id = '".encrypted_admin()."'
				   and gs_id = '{$_POST['gs_id'][$i]}' ";
		sql_query($sql);
	} else {
		$sql = "insert into shop_goods_type
				   set mb_id = '".encrypted_admin()."',
					   gs_id = '{$_POST['gs_id'][$i]}',
					   it_type1 = '{$_POST['it_type1'][$i]}',
					   it_type2 = '{$_POST['it_type2'][$i]}',
					   it_type3 = '{$_POST['it_type3'][$i]}',
					   it_type4 = '{$_POST['it_type4'][$i]}',
					   it_type5 = '{$_POST['it_type5'][$i]}',
					   it_type6 = '{$_POST['it_type6'][$i]}',
					   it_type7 = '{$_POST['it_type7'][$i]}',
					   it_type8 = '{$_POST['it_type8'][$i]}',
					   it_type9 = '{$_POST['it_type9'][$i]}',
					   it_type10 = '{$_POST['it_type10'][$i]}',
					   it_type11 = '{$_POST['it_type11'][$i]}',
					   it_type12 = '{$_POST['it_type12'][$i]}',
					   it_type13 = '{$_POST['it_type13'][$i]}',
					   it_type14 = '{$_POST['it_type14'][$i]}',
					   it_type15 = '{$_POST['it_type15'][$i]}',
					   it_type16 = '{$_POST['it_type16'][$i]}',
					   it_type17 = '{$_POST['it_type17'][$i]}',
					   it_type18 = '{$_POST['it_type18'][$i]}',
					   it_type19 = '{$_POST['it_type19'][$i]}',
					   it_type20 = '{$_POST['it_type20'][$i]}'
					    ";
		sql_query($sql);
	}
}

goto_url(MS_ADMIN_URL."/goods.php?{$q1}&page={$page}");
?>
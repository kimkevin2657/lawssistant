<?php
include_once("./_common.php");

check_demo();
check_admin_token();

for($i=0; $i<count($_POST['gs_id']); $i++)
{
	if($_POST['gcate'][$i]){
		$len = strlen($_POST['gcate'][$i]);
		$sql = " select count(*) as cnt 
					from shop_goods_cate
				   where gs_id = '{$_POST['gs_id'][$i]}'
				   and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
		$row = sql_fetch($sql);
		if($row['cnt']) {
			if($len == '3'){
				$sql = "update shop_goods_cate
						   set rank1 = '{$_POST['rank1'][$i]}'
						 where gs_id = '{$_POST['gs_id'][$i]}'
						 and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
				sql_query($sql);
			}elseif($len == '6'){
				$sql = "update shop_goods_cate
						   set rank2 = '{$_POST['rank2'][$i]}'
						 where gs_id = '{$_POST['gs_id'][$i]}'
						 and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
				sql_query($sql);
			}elseif($len == '9'){
				$sql = "update shop_goods_cate
						   set rank3 = '{$_POST['rank3'][$i]}'
						 where gs_id = '{$_POST['gs_id'][$i]}'
						 and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
				sql_query($sql);
			}elseif($len == '12'){
				$sql = "update shop_goods_cate
						   set rank4 = '{$_POST['rank4'][$i]}'
						 where gs_id = '{$_POST['gs_id'][$i]}'
						 and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
				sql_query($sql);
			}elseif($len == '15'){
				$sql = "update shop_goods_cate
						   set rank4 = '{$_POST['rank4'][$i]}'
						 where gs_id = '{$_POST['gs_id'][$i]}'
						 and (left(gcate,{$len}) = '{$_POST['gcate'][$i]}')";
				sql_query($sql);
			}
		} else {

		}
	}else{
		alert('카테고리를 선택후 랭킹입력을 하여 주시기 바랍니다. ');
	}
}

goto_url(MS_ADMIN_URL."/goods.php?{$q1}&page={$page}");
?>
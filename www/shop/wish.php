<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/wish.php');
}

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '찜한상품';
include_once("./_head.php");

$sql  = " select a.wi_id, a.wi_time, a.gs_id, b.* 
            from shop_wish a left join shop_goods b ON ( a.gs_id = b.index_no )
		   where a.mb_id = '{$member['id']}' 
		   order by a.wi_id desc ";
$result = sql_query($sql);
$wish_count = sql_num_rows($result);

Theme::get_theme_part(MS_THEME_PATH,'/wish.skin.php');

include_once("./_tail.php");
?>
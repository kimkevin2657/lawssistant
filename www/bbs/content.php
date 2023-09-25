<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MBBS_URL.'/content.php?co_id='.$co_id);
}

$co	= sql_fetch("select * from shop_content where co_id = '$co_id'");
if(!$co["co_id"]){
	alert('�ڷᰡ �����ϴ�.', MS_URL);
}

$ms['title'] = $co['co_subject'];
include_once("./_head.php");
Theme::get_theme_part(MS_THEME_PATH,'/content.skin.php');
include_once("./_tail.php");
?>
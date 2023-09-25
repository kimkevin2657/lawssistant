<?php
if(!defined('_MALLSET_')) exit;

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

// admin 세션 변수에 등록
$admin_id = get_session('admin_ss_mb_id');
if($admin_id) { 
	set_session('ss_mb_id', $admin_id);

	$member = sql_fetch("select * from shop_member where id = '".$admin_id."'");
	
	// 초기화
	unset($admin_id);
	set_session('admin_ss_mb_id', '');
}
if($member['grade'] > 5){
if(!is_admin()) {
	alert('관리자 권한이 없습니다.', MS_URL);
}
}
?>
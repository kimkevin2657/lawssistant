<?php
include_once("./_common.php");

if(!$is_member) { $member['grade'] = 99; }

/* if($bo_table == "approval"){
	if($member['grade'] < $board['write_priv']) {
		alert("권한이 없습니다.", MS_BBS_URL."/board.php?bo_table=approval");
	}
}else{
	if($board['write_priv'] < 99) {
		if($member['grade'] > $board['write_priv']) {
			alert("권한이 없습니다.", MS_BBS_URL."/list.php?boardid=$boardid");
		}
	}
} */

if($board['write_priv'] < 99) {
	if($member['grade'] > $board['write_priv']) {
		alert("권한이 없습니다.", MS_BBS_URL."/list.php?boardid=$boardid");
	}
}


if($board['topfile']) {
	@include_once($board['topfile']);
}

if($board['content_head']) {
	echo $board['content_head'];
}

if($board['width'] <= 100) {
	$board['width'] = $board['width'] ."%";
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$bo_img_url = MS_BBS_URL.'/skin/'.$board['skin'];
$from_action_url = MS_HTTPS_BBS_URL."/write_update.php";

include_once(MS_BBS_PATH."/skin/{$board['skin']}/write.php");

if($board['content_tail']) {
	echo $board['content_tail'];
}

if($board['downfile']) {
	@include_once($board['downfile']);
}
?>
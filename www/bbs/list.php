<?php
include_once("./_common.php");

if($boardid){

	if(MS_IS_MOBILE) {
		goto_url(MS_MBBS_URL.'/board_list.php?boardid='.$boardid);
	}

}else{

	if(MS_IS_MOBILE) {
		include_once(MS_BBS_PATH."/mobile/skin/{$board['skin']}/list.php");
	}

}

if(!$is_member) { $member['grade'] = 99; }

if(!$_GET['bo_table']){
	if($board['list_priv'] < 99) {
		if($member['grade'] > $board['list_priv']) {
			alert('권한이 없습니다.');
		}
	}
}
if($board['topfile']) {	
	if($sfl=='writer') {	
		if(!$stx){	
			$stx = $member['index_no'];	
		}	
	} else {
		include_once($board['topfile']);	
	}
}

if($board['content_head']) {	
	echo $board['content_head'];
}

if($board['width'] <= 100) {	
	$board['width'] = $board['width'] ."%";	
}

$bo_img_url = MS_BBS_URL.'/skin/'.$board['skin'];

include_once(MS_BBS_PATH."/skin/{$board['skin']}/list.php");

if($board['content_tail']) {	
	echo $board['content_tail'];
}

if($board['downfile']) {	
	include_once($board['downfile']);
}
?>
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
	if($board['bo_list_level'] < 99) {
		if($member['grade'] > $board['bo_list_level']) {
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

if($board['bo_table_width'] <= 100) {	
	$board['bo_table_width'] = $board['bo_table_width'] ."%";	
}

$bo_img_url = MS_BBS_URL.'/skin/'.$board['bo_skin'];

include_once(MS_BBS_PATH."/skin/{$board['bo_skin']}/list.skin.php");

if($board['content_tail']) {	
	echo $board['content_tail'];
}

if($board['downfile']) {	
	include_once($board['downfile']);
}
?>
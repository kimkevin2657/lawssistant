<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
    alert("로그인 후 작성 가능합니다.");
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$bk_ix = trim(strip_tags($_POST['bk_ix']));
$booking_id = trim(strip_tags($_POST['booking_id']));
$mb_id = trim(strip_tags($_POST['mb_id']));
$score = trim(strip_tags($_POST['score']));

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}

if(!get_magic_quotes_gpc()) {
	$memo = addslashes($_POST['memo']);
}

if($_FILES['review_img']['name']){ //파일 업로드가 있을 경우만 추가 

	$fileName = $_FILES['review_img']['name'];
	$mb_img_path = "../review_img/"; //경로 변경
	$fileName = $bk_ix."_"."review"."_".$fileName;
	$filePath = "../review_img/".$fileName;
	move_uploaded_file($_FILES['review_img']['tmp_name'], $filePath);

	//$thumb = thumbnail($fileName, $mb_img_path, $mb_img_path, 720, 560, true, true);
	$thumb = thumbnail($fileName, $mb_img_path, $mb_img_path, 173, 173, true, true);
	$thumbPath = $thumb; //경로변경


}

$sql = "insert into review_list 
			set bk_ix = '$bk_ix', 
			booking_id = '$booking_id', 
			mb_id = '$member[id]',
			memo = '$memo',
			score = '$score',
			review_file = '{$fileName}',
			thumbnail_img = '{$thumbPath}',
			reg_time = '".MS_TIME_YMDHIS."' ";

sql_query($sql);

//sql_query("update shop_goods set m_count = m_count+1 where index_no='$gs_id'");

alert_close("정상적으로 등록 되었습니다.");
?>
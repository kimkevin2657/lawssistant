<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
    alert("�α��� �� �ۼ� �����մϴ�.");
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// ������ ������ ���� �ٽ� �Է����� ���ؼ� �������� �Ѵ�.
	set_session("ss_token", "");
} else {
	alert("�߸��� ���� �Դϴ�.");
	exit;
}

$gs_id = trim(strip_tags($_POST['gs_id']));
$seller_id = trim(strip_tags($_POST['seller_id']));
$score = trim(strip_tags($_POST['score']));

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("���뿡 �ùٸ��� ���� �ڵ尡 �ټ� ���ԵǾ� �ֽ��ϴ�.");
}

if(!get_magic_quotes_gpc()) {
	$memo = addslashes($_POST['memo']);
}

$sql = "insert into shop_goods_review 
		   set gs_id = '$gs_id', 
			   mb_id = '$member[id]',
			   memo = '$memo',
			   score = '$score',
			   reg_time = '".MS_TIME_YMDHIS."',
			   seller_id = '$seller_id',
			   pt_id = '$pt_id' ";
sql_query($sql);

sql_query("update shop_goods set m_count = m_count+1 where index_no='$gs_id'");

alert_close("���������� ��� �Ǿ����ϴ�.");
?>
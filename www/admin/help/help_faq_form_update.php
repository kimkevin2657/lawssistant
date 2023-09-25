<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['cate']	  = $_POST['faq_cate'];
$value['subject'] = $_POST['subject'];
$value['memo']	  = $_POST['memo'];

if($w == "") {
	$value['wdate'] = MS_TIME_YMDHIS;
	insert("shop_faq",$value);
	$index_no = sql_insert_id();

	goto_url(MS_ADMIN_URL."/help.php?code=faq_from&w=u&index_no=$index_no");

} else if($w == "u") {
	update("shop_faq",$value," where index_no='$index_no'");

	goto_url(MS_ADMIN_URL."/help.php?code=faq_from&w=u&index_no=$index_no$qstr&page=$page");
}
?>
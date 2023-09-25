<?php
include_once("./_common.php");

if(MS_IS_MOBILE) {
	goto_url(MS_MSHOP_URL.'/coupon.php');
}

if(!$config['coupon_yes']) {
    alert("��������� ���� �Ǿ����ϴ�.");
}

if(!$is_member) {
	goto_url(MS_BBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = '��������';
include_once("./_head.php");

$u_part = array();
$u_part[0] = "��ü��ǰ";
$u_part[1] = "�Ϻ� ��ǰ ��밡��";
$u_part[2] = "�Ϻ� ī�װ� ��밡��";
$u_part[3] = "�Ϻ� ��ǰ ���Ұ�";
$u_part[4] = "�Ϻ� ī�װ� ���Ұ�";

$sql_common = " from shop_coupon_log ";
$sql_search = " where mb_id = '$member[id]' ";
$sql_order  = " order by cp_wdate desc ";

$selected1 = '';
$selected2 = '';

if($sca) {
	// ���Ϸ� �� ���Ѹ��� ����
	$sql_search .= " and mb_use='1' or ( (cp_inv_type='0' and cp_inv_edate != '9999999999' and cp_inv_edate < curdate()) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) < now()) ) ";

	$selected2 = ' class="active"';
} else {
	// ��밡���� ����
	$sql_search .= " and mb_use='0' and ( (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ) ";

	$selected1 = ' class="active"';
}

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // ��ü ������ ���
if($page == "") { $page = 1; } // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

Theme::get_theme_part(MS_THEME_PATH,'/coupon.skin.php');

include_once("./_tail.php");
?>
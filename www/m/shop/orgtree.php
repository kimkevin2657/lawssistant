<?php
include_once("./_common.php");

if(!$is_member) {
    goto_url(MS_MBBS_URL.'/login.php?url='.$urlencode);
}

$ms['title'] = "조직도 트리";
include_once("./_head.php");

$sql_common = " from shop_coupon_log ";
$sql_search = " where mb_id = '{$member['id']}' ";

// 사용가능한 쿠폰
$sql_search.= " and mb_use='0' and ( ";
$sql_search.= " (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) ";
$sql_search.= " or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ";
$sql_search.= " ) ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$cp_count = $row['cnt'];

$mb_id     = $member['id'];
?>
<?php
$orgChart = new Organization($mb_id);
$orgChart->tree();
?>
<?php
include_once("./_tail.php");
?>
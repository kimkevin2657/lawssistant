<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "배송준비")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 2) continue;

		change_order_status_3($od_no);
	}
}
else if($_POST['act_button'] == "배송중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 3) continue;

		change_order_status_4($od_no, $delivery, $delivery_no);

		$od_sms_baesong[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_baesong as $key=>$recv) {
		icode_order_sms_send($recv, 4, $key);
	}
}
else if($_POST['act_button'] == "배송완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_no		 = $_POST['od_no'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_no);
		if($od['dan'] != 4) continue;

		change_order_status_5($od_no, $delivery, $delivery_no);

		$od_sms_delivered[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_delivered as $key=>$recv) {
		icode_order_sms_send($recv, 6, $key);
	}
}
else if($_POST['act_button'] == "운송장번호수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set delivery	= '{$_POST['delivery'][$k]}'
					  , delivery_no = '{$_POST['delivery_no'][$k]}'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "반품 처리완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set return_status = 'Y'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "반품 처리중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set return_status = 'N'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "교환 처리완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set exchange_status = 'Y'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "교환 처리중")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_order
					set exchange_status = 'N'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "반품 취소후 배송중으로 처리")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];

		$sql = " update shop_order
					set dan = '4'
					  , return_status = 'N'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
}
else if($_POST['act_button'] == "교환 취소후 배송중으로 처리")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];

		$sql = " update shop_order
					set dan = '4'
					  , exchange_status = 'N'
				  where od_no = '{$_POST['od_no'][$k]}' ";
		sql_query($sql);
	}
} else {
	alert();
}

goto_url(MS_MYPAGE_URL."/page.php?$q1&page=$page");
?>
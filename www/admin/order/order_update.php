<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "입금완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 1) continue;
		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;

		change_order_status_ipgum($od_id);

		icode_order_sms_send($od['cellphone'], 3, $od_id);
	}
}
else if($_POST['act_button'] == "주문취소")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 1) continue;
		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;

		$sql = " select od_no from shop_order where od_id = '$od_id' order by index_no ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			change_order_status_6($row['od_no']);
		}

		icode_order_sms_send($od['cellphone'], 5, $od_id);
	}
}
else if($_POST['act_button'] == "배송준비")
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
else if($_POST['act_button'] == "구매확정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_status_final($od_no);
	}
}
else if($_POST['act_button'] == "구매확정취소")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_no = $_POST['od_no'][$k];

		change_status_final_cancel($od_no);
	}
}
else if($_POST['act_button'] == "선택삭제")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if(!in_array($od['dan'], array(1,6)))
			alert('입금대기, 주문취소 상태의 상품만 삭제 가능합니다.');

		$sql = " select od_no from shop_order where od_id = '$od_id' order by index_no ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			order_delete($row['od_no'], $od_id); // 주문서 삭제
		}
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

goto_url(MS_ADMIN_URL."/order.php?$q1&page=$page");
?>
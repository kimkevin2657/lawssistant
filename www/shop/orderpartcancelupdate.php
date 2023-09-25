<?php
include_once('./_common.php');

$tax_mny = preg_replace('/[^0-9]/', '', $_POST['mod_tax_mny']);
$free_mny = preg_replace('/[^0-9]/', '', $_POST['mod_free_mny']);

if(!$tax_mny && !$free_mny)
    alert('과세 취소금액 또는 비과세 취소금액을 입력해 주십시오.');

if(!trim($mod_memo))
    alert('요청사유를 입력해 주십시오.');

// 주문정보
$sql = " select * from shop_order where od_id = '$od_id' and od_no = '$od_no' ";
$od = sql_fetch($sql);

$item = sql_fetch("select * from shop_goods where index_no = '{$od['gs_id']}'");

if(!$od['od_id'])
    alert('주문정보가 존재하지 않습니다.');

if($od['paymethod'] == '계좌이체' && substr($od['receipt_time'], 0, 10) >= MS_TIME_YMD)
    alert('실시간 계좌이체건의 부분취소 요청은 결제일 익일에 가능합니다.');

// 금액비교
$amount = get_order_spay($od_id); // 결제정보 합계
$od_receipt_price = $amount['useprice'] - $amount['refund'];

if(($tax_mny && $free_mny) && ($tax_mny + $free_mny) > $od_receipt_price)
    alert('과세, 비과세 취소금액의 합을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

if($tax_mny && $tax_mny > $od_receipt_price)
    alert('과세 취소금액을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

if($free_mny && $free_mny > $od_receipt_price)
    alert('비과세 취소금액을 '.display_price($od_receipt_price).' 이하로 입력해 주십시오.');

// 가맹점 PG결제 정보
$default = set_minishop_value($od['od_settle_pid']);


$subject_1 = '주문취소';
$message_1 = $od['name'].'님의
'.$od['od_id'].'
'.$item['gname'].'의 주문이 취소되었습니다.

이용해주셔서 감사합니다.';

/*
결제 단계
value="1"> 입금대기</label>
value="2"> 입금완료</label>
value="3"> 배송준비</label>
value="4"> 배송중</label>
value="5"> 배송완료</label>
value="6"> 취소</label>
value="7"> 반품</label>
value="8"> 교환</label>
value="9"> 환불</label>
*/

if($od['dan'] == '2'){
	if ($od['famiwel_op_no'] > '0' ) {
		$dan = '6';
		$rete = famiwel_status_send_go($dl_comcode,$od['famiwel_od_id'],$od['famiwel_op_no'],$dan);
	}
	aligo_sms('TE_4901', $od['cellphone'], $od['name'], $subject_1, $message_1);
	change_order_status_6($od_no);
}else{

goto_url(MS_SHOP_URL."/orderinquiryview.php?od_id=$od_id");
}


// PG사별 부분취소 실행
include_once(MS_SHOP_PATH.'/'.strtolower($od['od_pg']).'/orderpartcancel.inc.php');


goto_url(MS_SHOP_URL."/orderinquiryview.php?od_id=$od_id");
?>

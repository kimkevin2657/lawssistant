<?php
include_once('./_common.php');
include_once(MS_LIB_PATH.'/mailer.lib.php');

$od_id = $_POST['od_id'];

$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.", MS_MURL);
}

$i_price = (int)get_session('tot_price'); // 결제금액
$i_usepoint = (int)get_session('use_point'); // 쇼핑포인트결제액

if(!$i_price) {
    alert("결제할 금액이 없습니다.", MS_MURL);
}

if(in_array($od_settle_case, array('무통장','쇼핑포인트'))) {
    alert("올바른 방법으로 이용해 주십시오.", MS_MURL);
}

// 삼성페이 요청으로 왔다면 현재 삼성페이는 이니시스 밖에 없으므로 $default['de_pg_service'] 값을 변경한다.
if( $od_settle_case == '삼성페이' && !empty($_POST['P_HASH']) ){
    $default['de_pg_service'] = 'inicis';
}

// 결제등록 완료 체크
if($od_settle_case != 'KAKAOPAY') {
    if($default['de_pg_service'] == 'kcp' && ($_POST['tran_cd'] == '' || $_POST['enc_info'] == '' || $_POST['enc_data'] == ''))
        alert('결제등록 요청 후 주문해 주십시오.', MS_MSHOP_URL.'/orderkcp.php?od_id='.$od_id);

    if($default['de_pg_service'] == 'lg' && !$_POST['LGD_PAYKEY'])
        alert('결제등록 요청 후 주문해 주십시오.', MS_MSHOP_URL.'/orderlg.php?od_id='.$od_id);

    if($default['de_pg_service'] == 'inicis' && !$_POST['P_HASH'])
        alert('결제등록 요청 후 주문해 주십시오.', MS_MSHOP_URL.'/orderinicis.php?od_id='.$od_id);
}

$od_tno = '';

if($od_settle_case == "계좌이체")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include MS_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'easypay':
            include MS_MSHOP_PATH.'/easypay/easypay_result.php';
            break;
        case 'inicis':
            include MS_MSHOP_PATH.'/inicis/pay_result.php';
            break;
		case 'kcp':
            include MS_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $bank_name  = iconv("cp949", "utf-8", $bank_name);
            break;
    }

    $od_tno             = $tno;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_deposit_name    = $od_name;
    $od_bank_account    = $bank_name;
    $pg_price           = $amount;
	$od_status			= '2';
}
else if($od_settle_case == "가상계좌")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include MS_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'easypay':
            include MS_MSHOP_PATH.'/easypay/easypay_result.php';
            break;
        case 'inicis':
            include MS_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        case 'kcp':
            include MS_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $bankname   = iconv("cp949", "utf-8", $bankname);
            $depositor  = iconv("cp949", "utf-8", $depositor);
            break;
    }

    $od_tno             = $tno;
	$od_app_no          = $app_no;
    $od_bank_account    = $bankname.' '.$account;
    $od_deposit_name    = $depositor;
    $pg_price           = $amount;
	$od_status			= '1';
}
else if($od_settle_case == "휴대폰")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include MS_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'easypay':
            include MS_MSHOP_PATH.'/easypay/easypay_result.php';
            break;
        case 'inicis':
            include MS_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        case 'kcp':
            include MS_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            break;
    }

    $od_tno             = $tno;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $commid.' '.$mobile_no;
    $pg_price           = $amount;
	$od_status			= '2';
}
else if($od_settle_case == "신용카드")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include MS_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'easypay':
            include MS_MSHOP_PATH.'/easypay/easypay_result.php';
            break;
        case 'inicis':
            include MS_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        case 'kcp':
            include MS_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $card_name  = iconv("cp949", "utf-8", $card_name);
            break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
	$od_status			= '2';
}
else if($od_settle_case == "간편결제")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include MS_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'easypay':
            include MS_MSHOP_PATH.'/easypay/easypay_result.php';
            break;
        case 'inicis':
            include MS_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        case 'kcp':
            include MS_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $card_name  = iconv("cp949", "utf-8", $card_name);
            break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
	$od_status			= '2';
}
else if($od_settle_case == "삼성페이")
{
    // 이니시스에서만 지원
    include MS_MSHOP_PATH.'/inicis/pay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_status			= '2';
}
else if($od_settle_case == "KAKAOPAY")
{
    include MS_SHOP_PATH.'/kakaopay/kakaopay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
	$od_status			= '2';
}
else
{
    die("od_settle_case Error!!!");
}

$od_pg = $default['de_pg_service'];
if($od_settle_case == 'KAKAOPAY')
    $od_pg = 'KAKAOPAY';

// 주문금액과 결제금액이 일치하는지 체크
if($tno) {
    if((int)$i_price !== (int)$pg_price) {
        $cancel_msg = '결제금액 불일치';
        switch($od_pg) {
            case 'lg':
                include MS_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'easypay':
                include MS_MSHOP_PATH.'/easypay/easypay_cancel.php';
                break;
            case 'inicis':
                include MS_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']               = $tno;
                $_REQUEST['Amt']               = $amount;
                $_REQUEST['CancelMsg']         = $cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include MS_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            case 'kcp':
                include MS_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }

        die("Receipt Amount Error");
    }
}

$od_escrow = 0;
if($escw_yn == 'Y')
    $od_escrow = 1;

// 복합과세 금액
$od_tax_mny = round($i_price / 1.1);
$od_vat_mny = $i_price - $od_tax_mny;
$od_free_mny = 0;
if($default['de_tax_flag_use']) {
    $od_tax_mny = (int)$_POST['comm_tax_mny'];
    $od_vat_mny = (int)$_POST['comm_vat_mny'];
    $od_free_mny = (int)$_POST['comm_free_mny'];
}

// 주문서에 UPDATE
$sql = " update shop_order
            set deposit_name = '$od_deposit_name'
			  , receipt_time = '$od_receipt_time'
			  , bank		 = '$od_bank_account'
			  , dan			 = '$od_status'
			  , od_pg		 = '$od_pg'
			  , od_tno		 = '$od_tno'
			  , od_app_no	 = '$od_app_no'
			  , od_escrow	 = '$od_escrow'
			  , od_tax_mny	 = '$od_tax_mny'
			  , od_vat_mny	 = '$od_vat_mny'
			  , od_free_mny	 = '$od_free_mny'
		  where od_id = '$od_id'";
$result = sql_query($sql, false);

if($result) {
	// 장바구니 상태변경
	$sql = " update shop_cart set ct_select = '1' where od_id = '$od_id' ";
	sql_query($sql, false);
} else {
	// 주문정보 UPDATE 오류시 결제 취소
    if($tno) {
        $cancel_msg = '주문상태 변경 오류';
        switch($od_pg) {
            case 'lg':
                include MS_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'easypay':
                include MS_MSHOP_PATH.'/easypay/easypay_cancel.php';
                break;
            case 'inicis':
                include MS_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']               = $tno;
                $_REQUEST['Amt']               = $amount;
                $_REQUEST['CancelMsg']         = $cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include MS_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            case 'kcp':
                include MS_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }
    }

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>'.strtoupper($od_pg).'를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

// 회원이면서 쇼핑포인트를 사용했다면 테이블에 사용을 추가
if($is_member && $i_usepoint) {
	insert_point($member['id'], (-1) * $i_usepoint, "주문번호 $od_id 결제");
}

// 쿠폰사용내역기록
if($is_member) {
	$sql = "select * from shop_order where od_id='$od_id'";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		if($row['coupon_price']) {
			$sql = "update shop_coupon_log 
					   set mb_use = '1',
						   od_no = '$row[od_no]',
						   cp_udate	= '".MS_TIME_YMDHIS."'
					 where lo_id = '$row[coupon_lo_id]' ";
			sql_query($sql);
		}
	}
}

	if($od['dan'] != '1'){
		// 주문완료 문자전송
		icode_order_sms_send($od['cellphone'], '2', $od_id);
	}

// 메일발송
if($od['email']) {
	$subject1 = get_text($od['name'])."님 주문이 정상적으로 처리되었습니다.";
	$subject2 = get_text($od['name'])." 고객님께서 신규주문을 신청하셨습니다.";

	ob_start();
	include_once(MS_SHOP_PATH.'/orderformupdate_mail.php');
	$content = ob_get_contents();
	ob_end_clean();

	// 주문자에게 메일발송
	mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

	// 관리자에게 메일발송
	if($super['email'] != $od['email']) {
		mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
	}
}

// 주문 정보 임시 데이터 삭제
$sql = " delete from shop_order_data where od_id = '$od_id' and dt_pg = '$od_pg' ";
sql_query($sql);

// 주문번호제거
set_session('ss_order_id', '');

// 장바구니 session 삭제
set_session('ss_cart_id', '');

// orderinquiryview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id.MS_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
set_session('ss_orderview_uid', $uid);

goto_url(MS_MSHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&uid='.$uid);
?>

<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

/*******************************************************************
 * 7. DB연동 실패 시 강제취소                                      *
 *                                                                 *
 * 지불 결과를 DB 등에 저장하거나 기타 작업을 수행하다가 실패하는  *
 * 경우, 아래의 코드를 참조하여 이미 지불된 거래를 취소하는 코드를 *
 * 작성합니다.                                                     *
 *******************************************************************/
$resultMap = get_session('PAYREQ_MAP');
$cancelFlag = "true";

// $cancelFlag를 "ture"로 변경하는 condition 판단은 개별적으로
// 수행하여 주십시오.

if($cancelFlag == "true")
{
    include_once(MS_MSHOP_PATH.'/settle_easypay.inc.php');

    $easyPay = new EasyPay_Client; // 전문처리용 Class (library에서 정의됨)

    $easyPay->clearup_msg();

    $easyPay->set_home_dir($g_home_dir);
    $easyPay->set_gw_url($g_gw_url);
    $easyPay->set_gw_port($g_gw_port);
    $easyPay->set_log_dir($g_log_dir);
    $easyPay->set_log_level($g_log_level);
    $easyPay->set_cert_file($g_cert_file);

    $client_ip = $easyPay->get_remote_addr();    // [필수]결제고객 IP

    $easyPay->clearup_msg();

    $tr_cd = $TRAN_CD_NOR_MGR;
    $mgr_data = $easyPay->set_easypay_item("mgr_data");
    if ( $r_escrow_yn != "Y" )
    {
        $easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype"      , "40"   );
    }
    else
    {
        $easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype"      , "61"   );
        $easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype"     , "ES02" );
    }
    $easyPay->set_easypay_deli_us( $mgr_data, "org_cno"         , $resultMap['cno']     );
    $easyPay->set_easypay_deli_us( $mgr_data, "order_no"        , $resultMap['order_no']  );
    $easyPay->set_easypay_deli_us( $mgr_data, "req_ip"          , $client_ip );
    $easyPay->set_easypay_deli_us( $mgr_data, "req_id"          , "MALL_R_TRANS" );
    $easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg"         , "DB 처리 실패로 망취소"  );

    $easyPay->easypay_exec($g_mall_id, $tr_cd, $order_no, $client_ip, $opt);
    $res_cd      = $easyPay->_easypay_resdata["res_cd"     ];    // 응답코드
    $res_msg     = $easyPay->_easypay_resdata["res_msg"    ];    // 응답메시지
    $r_cno       = $easyPay->_easypay_resdata["cno"        ];    // PG거래번호
    $r_canc_date = $easyPay->_easypay_resdata["canc_date"  ];    // 취소일시

}

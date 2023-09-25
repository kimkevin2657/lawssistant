<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');
include_once(WZB_PLUGIN_PATH.'/gender/inicis/config.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if(!$wzpconfig['pn_pg_test']) {
    switch ($_SERVER['REMOTE_ADDR']) {
        case '203.238.37.3' :
        case '203.238.37.15' :
        case '203.238.37.16' :
        case '203.238.37.25' :
        case '39.115.212.9' :
        case '211.219.96.165' :
        case '118.129.210.25' :
        case '183.109.71.153' :
        case '61.34.174.42' :
            break;
        default :
            $super_admin = get_admin('super');
            $egpcs_str = "ENV[" . serialize($_ENV) . "] "
                       . "GET[" . serialize($_GET) . "]"
                       . "POST[" . serialize($_POST) . "]"
                       . "COOKIE[" . serialize($_COOKIE) . "]"
                       . "SESSION[" . serialize($_SESSION) . "]";
            mailer('경고', 'waring', $super_admin['mb_email'], '올바르지 않은 접속 보고', "{$_SERVER['SCRIPT_NAME']} 에 {$_SERVER['REMOTE_ADDR']} 이 ".G5_TIME_YMDHIS." 에 접속을 시도하였습니다.\n\n" . $egpcs_str, 2);
            exit;
    }
}

$log_txt = date('Y-m-d H:i:s', time());
foreach($_REQUEST as $uk=>$uv) {
    $log_txt .= "|".$uk."=".$uv;
}
$log_dir = G5_DATA_PATH.'/pglog'; 

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir($log_dir, G5_DIR_PERMISSION);
@chmod($log_dir, G5_DIR_PERMISSION);

$log_file = fopen($log_dir."/vBankMobileLog.txt", "a");
fwrite($log_file, $log_txt."\r\n");
fclose($log_file);

// 이니시스 NOTI 서버에서 받은 Value
$P_TID;				// 거래번호
$P_MID;				// 상점아이디
$P_AUTH_DT;			// 승인일자
$P_STATUS;			// 거래상태 (00:성공, 01:실패)
$P_TYPE;			// 지불수단
$P_OID;				// 상점주문번호
$P_FN_CD1;			// 금융사코드1
$P_FN_CD2;			// 금융사코드2
$P_FN_NM;			// 금융사명 (은행명, 카드사명, 이통사명)
$P_AMT;				// 거래금액
$P_UNAME;			// 결제고객성명
$P_RMESG1;			// 결과코드
$P_RMESG2;			// 결과메시지
$P_NOTI;			// 노티메시지(상점에서 올린 메시지)
$P_AUTH_NO;			// 승인번호
$P_SRC_CODE;        // 앱연동 결제구분


$P_TID      = $_POST['P_TID'];
$P_MID      = $_POST['P_MID'];
$P_AUTH_DT  = $_POST['P_AUTH_DT'];
$P_STATUS   = $_POST['P_STATUS'];
$P_TYPE     = $_POST['P_TYPE'];
$P_OID      = $_POST['P_OID'];
$P_FN_CD1   = $_POST['P_FN_CD1'];
$P_FN_CD2   = $_POST['P_FN_CD2'];
$P_FN_NM    = $_POST['P_FN_NM'];
$P_AMT      = $_POST['P_AMT'];
$P_UNAME    = $_POST['P_UNAME'];
$P_RMESG1   = $_POST['P_RMESG1'];
$P_RMESG2   = $_POST['P_RMESG2'];
$P_NOTI     = $_POST['P_NOTI'];
$P_AUTH_NO  = $_POST['P_AUTH_NO'];
$P_SRC_CODE = $_POST['P_SRC_CODE'];


//WEB 방식의 경우 가상계좌 채번 결과 무시 처리
//(APP 방식의 경우 해당 내용을 삭제 또는 주석 처리 하시기 바랍니다.)
if($P_TYPE == "VBANK")	//결제수단이 가상계좌이며
{
   if($P_STATUS != "02") //입금통보 "02" 가 아니면(가상계좌 채번 : 00 또는 01 경우)
   {
        echo "OK";
        return;
   }

   // 입금결과 처리
    $query = " select bk_ix, od_id, bk_misu from {$g5['wzb_booking_table']} where od_id = '$P_OID' and bk_tno = '$P_TID' and bk_status != '완료' ";
    $bk = sql_fetch($query);

    if($bk['od_id']) {
        
        $bk_receipt_price   = (int)$P_AMT;
        $bk_misu            = $bk['bk_misu'] - $bk_receipt_price;
        $bk_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $P_AUTH_DT);

        $query = " update {$g5['wzb_booking_table']}
                    set bk_receipt_price    = '{$bk_receipt_price}',
                        bk_receipt_time     = '{$bk_receipt_time}',
                        bk_pg_price         = '{$bk_receipt_price}',
                        bk_misu             = '{$bk_misu}',
                        bk_status           = '완료'
                  where bk_ix = '{$bk['bk_ix']}' ";
        $result = sql_query($query);

        $query = "update {$g5['wzb_room_status_table']} set rms_status = '완료' where bk_ix = '{$bk['bk_ix']}' ";
        sql_query($query);
    }

    if ($result) {
        die("OK");
    }
    else {
        die("FAIL");
    }
}

// 결과 incis log 테이블 기록
if($P_TYPE == 'BANK' || $P_SRC_CODE == 'A') {
    $sql = " insert into g5_wzb_inicis_log
                set oid       = '$P_OID',
                    P_TID     = '$P_TID',
                    P_MID     = '$P_MID',
                    P_AUTH_DT = '$P_AUTH_DT',
                    P_STATUS  = '$P_STATUS',
                    P_TYPE    = '$P_TYPE',
                    P_OID     = '$P_OID',
                    P_FN_NM   = '".iconv_utf8($P_FN_NM)."',
                    P_AUTH_NO = '$P_AUTH_NO',
                    P_AMT     = '$P_AMT',
                    P_RMESG1  = '".iconv_utf8($P_RMESG1)."' ";
    @sql_query($sql);
}

die("OK");
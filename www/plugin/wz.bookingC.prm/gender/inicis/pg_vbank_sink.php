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

$log_file = fopen($log_dir."/vBankLog.txt", "a");
fwrite($log_file, $log_txt."\r\n");
fclose($log_file);

$msg_id = $msg_id;             //메세지 타입
$no_tid = $no_tid;             //거래번호
$no_oid = $no_oid;             //상점 주문번호
$id_merchant = $id_merchant;   //상점 아이디
$cd_bank = $cd_bank;           //거래 발생 기관 코드
$cd_deal = $cd_deal;           //취급 기관 코드
$dt_trans = $dt_trans;         //거래 일자
$tm_trans = $tm_trans;         //거래 시간
$no_msgseq = $no_msgseq;       //전문 일련 번호
$cd_joinorg = $cd_joinorg;     //제휴 기관 코드

$dt_transbase = $dt_transbase; //거래 기준 일자
$no_transeq = $no_transeq;     //거래 일련 번호
$type_msg = $type_msg;         //거래 구분 코드
$cl_close = $cl_close;         //마감 구분코드
$cl_kor = $cl_kor;             //한글 구분 코드
$no_msgmanage = $no_msgmanage; //전문 관리 번호
$no_vacct = $no_vacct;         //가상계좌번호
$amt_input = $amt_input;       //입금금액
$amt_check = $amt_check;       //미결제 타점권 금액
$nm_inputbank = $nm_inputbank; //입금 금융기관명
$nm_input = $nm_input;         //입금 의뢰인
$dt_inputstd = $dt_inputstd;   //입금 기준 일자
$dt_calculstd = $dt_calculstd; //정산 기준 일자
$flg_close = $flg_close;       //마감 전화

//가상계좌채번시 현금영수증 자동발급신청시에만 전달
$dt_cshr      = $dt_cshr;       //현금영수증 발급일자
$tm_cshr      = $tm_cshr;       //현금영수증 발급시간
$no_cshr_appl = $no_cshr_appl;  //현금영수증 발급번호
$no_cshr_tid  = $no_cshr_tid;   //현금영수증 발급TID

// 입금결과 처리
$query = " select bk_ix, od_id, bk_misu from {$g5['wzb_booking_table']} where od_id = '$no_oid' and bk_app_no = '$no_vacct' and bk_status != '완료' ";
$bk = sql_fetch($query);

if($bk['od_id']) {
    
    $bk_receipt_price   = (int)$amt_input;
    $bk_misu            = $bk['bk_misu'] - $bk_receipt_price;
    $bk_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $dt_trans.$tm_trans);

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
    die("DB Error");
}
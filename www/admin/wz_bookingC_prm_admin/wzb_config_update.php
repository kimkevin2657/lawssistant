<?php
$sub_menu = '790100';
include_once('./_common.php');

check_demo();

//auth_check($auth[$sub_menu], "w");

/* if ($is_admin)
    alert('최고관리자만 접근 가능합니다.'); */

//check_admin_token();

if (!$bo_table) { alert('게시판 TABLE명은 반드시 입력하세요.'); }
if (!preg_match("/^([A-Za-z0-9_]{1,20})$/", $bo_table)) { alert('게시판 TABLE명은 공백없이 영문자, 숫자, _ 만 사용 가능합니다. (20자 이내)'); }
if (!$_POST['bo_subject']) { alert('게시판 제목을 입력하세요.'); }

$is_update_table = true;
$is_new_table    = false; // 테이블을 새로 생성해야되는지의 여부
if (trim($_POST['bo_table_before']) != trim($_POST['bo_table'])) { 
    $is_new_table = true;
} 

if ($is_new_table) { 
    $row = sql_fetch(" select bo_table, bo_skin from {$g5['board_table']} where bo_table = '$bo_table' ");

    if (!$row['bo_table']) {

        $is_update_table = false;

        $board_path = G5_DATA_PATH.'/file/'.$bo_table;

        // 게시판 디렉토리 생성
        @mkdir($board_path, G5_DIR_PERMISSION);
        @chmod($board_path, G5_DIR_PERMISSION);

        // 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
        $file = $board_path . '/index.php';
        $f = @fopen($file, 'w');
        @fwrite($f, '');
        @fclose($f);
        @chmod($file, G5_FILE_PERMISSION);

        $sql = " insert into {$g5['board_table']}
                    set bo_table            = '{$bo_table}',
                        bo_count_write      = '0',
                        bo_count_comment    = '0',
                        gr_id               = '{$_POST['gr_id']}',
                        bo_subject          = '{$_POST['bo_subject']}',
                        bo_list_level       = '1',
                        bo_read_level       = '1',
                        bo_write_level      = '10',
                        bo_reply_level      = '10',
                        bo_comment_level    = '10',
                        bo_html_level       = '10',
                        bo_link_level       = '10',
                        bo_count_modify     = '1',
                        bo_count_delete     = '1',
                        bo_upload_level     = '1',
                        bo_download_level   = '1',
                        bo_skin             = '".WZB_BO_SKIN."',
                        bo_mobile_skin      = '".WZB_BO_MOBILE_SKIN."',
                        bo_include_head     = '_head.php',
                        bo_include_tail     = '_tail.php',
                        bo_page_rows        = '{$config['cf_page_rows']}',
                        bo_mobile_page_rows = '{$config['cf_page_rows']}'
                        ";
        sql_query($sql);

        // 게시판 테이블 생성
        $file = file('../sql_write.sql');
        $sql = implode($file, "\n");

        $create_table = $g5['write_prefix'] . $bo_table;

        // sql_board.sql 파일의 테이블명을 변환
        $source = array('/__TABLE_NAME__/', '/;/');
        $target = array($create_table, '');
        $sql = preg_replace($source, $target, $sql);
        sql_query($sql, FALSE);
    }
} 

if ($is_update_table) {
    $sql = " update {$g5['board_table']}
                set gr_id               = '{$_POST['gr_id']}',
                    bo_subject          = '{$_POST['bo_subject']}'
            where bo_table = '{$bo_table}'               
                    ";
    sql_query($sql, true);
}

$sql_common = " ,
                cps_sms_receive = '{$cps_sms_receive}',
                cps_sms1_use_user = '".(int)$cps_sms1_use_user."',
                cps_sms1_con_user = '".$cps_sms1_con_user."',
                cps_sms2_use_user = '".(int)$cps_sms2_use_user."',
                cps_sms2_con_user = '".$cps_sms2_con_user."',
                cps_sms3_use_user = '".(int)$cps_sms3_use_user."',
                cps_sms3_con_user = '".$cps_sms3_con_user."',
                cps_sms1_use_adm = '".(int)$cps_sms1_use_adm."',
                cps_sms1_con_adm = '".$cps_sms1_con_adm."',
                cps_sms2_use_adm = '".(int)$cps_sms2_use_adm."',
                cps_sms2_con_adm = '".$cps_sms2_con_adm."',
                cps_sms3_use_adm = '".(int)$cps_sms3_use_adm."',
                cps_sms3_con_adm = '".$cps_sms3_con_adm."'
            ";

$sql = " update {$g5['wzb_config_table']}
            set bo_table                = '{$_POST['bo_table']}',
                pn_bank_info            = '{$_POST['pn_bank_info']}',
                pn_con_notice           = '{$_POST['pn_con_notice']}',
                pn_con_info             = '{$_POST['pn_con_info']}',
                pn_con_checkinout       = '{$_POST['pn_con_checkinout']}',
                pn_con_refund           = '{$_POST['pn_con_refund']}',
                pn_max_booking_expire   = '".(int)$_POST['pn_max_booking_expire']."',
                pn_wating_time          = '".(int)$_POST['pn_wating_time']."',
                pn_bank_use             = '".(int)$_POST['pn_bank_use']."',
                pn_onstore_use          = '".(int)$_POST['pn_onstore_use']."',
                pn_reserv_price_avg     = '".(int)$_POST['pn_reserv_price_avg']."',
                pn_pg_service           = '".$_POST['pn_pg_service']."',
                pn_pg_card_use          = '".$_POST['pn_pg_card_use']."',
                pn_pg_dbank_use         = '".$_POST['pn_pg_dbank_use']."',
                pn_pg_vbank_use         = '".$_POST['pn_pg_vbank_use']."',
                pn_pg_hp_use            = '".$_POST['pn_pg_hp_use']."',
                pn_pg_mid               = '".$_POST['pn_pg_mid']."',
                pn_pg_site_key          = '".$_POST['pn_pg_site_key']."',
                pn_pg_sign_key          = '".$_POST['pn_pg_sign_key']."',
                pn_pg_test              = '".(int)$_POST['pn_pg_test']."',
                pn_is_pay               = '".(int)$_POST['pn_is_pay']."',
                pn_result_state         = '".$_POST['pn_result_state']."'
                $sql_common
            ";
sql_query($sql);


$cp_ix          = 1;
$cp_term_day    = isset($_POST['cp_term_day'])  ? preg_replace('/[^0-9]/', '', $_POST['cp_term_day'])   : "";

$sql = " update {$g5['wzb_corp_table']} set cp_term_day = '{$cp_term_day}' where cp_ix = '{$cp_ix}' ";
sql_query($sql);

goto_url('./wzb_booking_list2.php?code=wzb_config', false);

?>
